<?php

namespace App\JobEngine\Intelligence;

use App\JobEngine\Ai\AiGateway;
use App\JobEngine\Normalize\IndonesianText;
use App\JobEngine\Normalize\RegionResolver;
use App\Models\Vacancy;
use App\Models\VacancyListing;
use Illuminate\Support\Facades\DB;

/**
 * Finds the existing vacancy that a listing describes, across platforms (TraKerja 2.0, Fase 3):
 * 1. exact: the same apply URL (two boards pointing at one company ATS page);
 * 2. candidates: same company, compatible region, seen within 45 days;
 * 3. title score >= 0.90 and descriptions alike (SimHash >= 0.80) -> same vacancy;
 * 4. gray zone (title 0.72-0.90): gpt-5-nano judges; only a confident "same" merges,
 *    anything else is queued in dedup_reviews for a person.
 */
class Matcher
{
    public const STRONG_TITLE = 0.90;

    public const GRAY_TITLE = 0.72;

    public const SAME_DESCRIPTION = 0.80;

    public function __construct(private AiGateway $ai, private RegionResolver $regions) {}

    /**
     * @return array{vacancy: Vacancy, method: string, score: float}|null
     */
    public function find(VacancyListing $listing, int $companyId, ?int $regionId, string $title, ?int $excludeVacancyId = null, bool $useAi = true): ?array
    {
        if ($listing->apply_url_hash) {
            $exact = VacancyListing::query()
                ->where('apply_url_hash', $listing->apply_url_hash)
                ->whereNotNull('vacancy_id')
                ->when($listing->exists, fn ($q) => $q->whereKeyNot($listing->id))
                ->when($excludeVacancyId, fn ($q) => $q->where('vacancy_id', '!=', $excludeVacancyId))
                ->first();
            if ($exact && ($vacancy = Vacancy::find($exact->vacancy_id)) && $vacancy->status !== 'archived') {
                return ['vacancy' => $vacancy, 'method' => 'exact', 'score' => 1.0];
            }
        }

        $candidates = Vacancy::query()
            ->where('company_id', $companyId)
            ->whereIn('status', ['active', 'suspect', 'closed'])
            ->whereNull('merged_into_id')
            ->when($excludeVacancyId, fn ($q) => $q->whereKeyNot($excludeVacancyId))
            ->when($regionId, fn ($q) => $q->where(fn ($q) => $q->where('region_id', $regionId)->orWhereNull('region_id')))
            ->where(fn ($q) => $q->where('first_seen_at', '>=', now()->subDays(45))->orWhere('date_posted', '>=', now()->subDays(45)->toDateString()))
            ->limit(60)
            ->get();

        $gray = null;
        foreach ($candidates as $candidate) {
            if ($this->conflicts($title, $regionId, $candidate)) {
                continue;
            }
            $titleScore = TextSimilarity::titleScore($title, $candidate->title);
            if ($titleScore < self::GRAY_TITLE) {
                continue;
            }
            $descScore = $this->descriptionScore($listing, $candidate);
            if ($titleScore >= self::STRONG_TITLE && ($descScore === null || $descScore >= self::SAME_DESCRIPTION)) {
                return ['vacancy' => $candidate, 'method' => 'fuzzy', 'score' => $titleScore];
            }
            if (! $gray || $titleScore > $gray['title']) {
                $gray = ['vacancy' => $candidate, 'title' => $titleScore, 'description' => $descScore];
            }
        }

        if (! $gray) {
            return null;
        }

        // The AI only judges pairs whose region is known and equal; everything else needs a person.
        // (Template postings share identical descriptions and gpt-5-nano merged different cities.)
        $sameKnownRegion = $regionId !== null && $regionId === $gray['vacancy']->region_id;
        try {
            $verdict = $useAi && $sameKnownRegion ? $this->judge($listing, $title, $gray['vacancy']) : null;
        } catch (\Throwable) {
            $verdict = null; // AI trouble never blocks ingest; the pair goes to manual review
        }
        if (($verdict['same_job'] ?? false) && ($verdict['confidence'] ?? '') === 'high') {
            return ['vacancy' => $gray['vacancy'], 'method' => 'ai', 'score' => $gray['title']];
        }

        // One review per pair: skip if the reverse pair (their listing vs our vacancy) is already queued.
        $reverse = $listing->vacancy_id && DB::table('dedup_reviews')->where('status', 'pending')
            ->where('candidate_vacancy_id', $listing->vacancy_id)
            ->whereIn('vacancy_listing_id', $gray['vacancy']->listings()->pluck('id'))->exists();
        if ($listing->exists && ! $reverse) {
            DB::table('dedup_reviews')->updateOrInsert(
                ['vacancy_listing_id' => $listing->id, 'candidate_vacancy_id' => $gray['vacancy']->id],
                ['score' => $gray['title'], 'status' => 'pending', 'updated_at' => now(), 'created_at' => now(),
                    'score_detail' => json_encode(['title' => $gray['title'], 'description' => $gray['description'], 'ai' => $verdict])],
            );
        }

        return null;
    }

    /**
     * Hard rules that make two postings different jobs, checked before any score or AI:
     * different known regions, different places named in the titles ("SPG - Bantul" vs "SPG - Tegal",
     * "Customer Service (Palembang)" vs "(Malang)"), internship vs regular, or different seniority.
     */
    public function conflicts(string $title, ?int $regionId, Vacancy $candidate): bool
    {
        if ($regionId !== null && $candidate->region_id !== null && $regionId !== $candidate->region_id) {
            return true;
        }

        $intern = '/\b(magang|intern(ship)?|pkl|trainee)\b/i';
        if ((bool) preg_match($intern, $title) !== (bool) preg_match($intern, $candidate->title)) {
            return true;
        }

        $mine = IndonesianText::seniority($title);
        $theirs = IndonesianText::seniority($candidate->title);
        if ($mine !== null && $theirs !== null && $mine !== $theirs) {
            return true;
        }

        // Level numbers: "Engineer I" vs "Engineer II", "Staff 1" vs "Staff 2" are different roles.
        $level = fn (string $t) => preg_match('/\b(I{1,3}|IV|V|[1-5])\b(?!\s*(?:tahun|years?|yrs?|bulan|months?)\b)/u', $t, $m) ? $m[1] : null;
        $levelA = $level($title);
        $levelB = $level($candidate->title);
        if ($levelA !== $levelB && ($levelA !== null || $levelB !== null)) {
            return true;
        }

        // Place qualifiers: "( ... )" or " - ..." segments, plus any region named in the title.
        $places = fn (string $t) => array_values(array_unique(array_merge(
            $this->regions->mentioned($t),
            array_map(fn ($q) => TextSimilarity::normalizeTitle($q), $this->qualifiers($t)),
        )));
        $a = $places($title);
        $b = $places($candidate->title);
        $regionsA = array_filter($a, 'is_int');
        $regionsB = array_filter($b, 'is_int');
        if ($regionsA && $regionsB && ! array_intersect($regionsA, $regionsB)) {
            return true;
        }
        $qualA = array_diff(array_filter($a, 'is_string'), ['']);
        $qualB = array_diff(array_filter($b, 'is_string'), ['']);

        // Each side names something the other does not ("Turkish" vs "Czech", "Bantul" vs "Tegal"),
        // even when they share another qualifier such as "Livechat" or "(EI)".
        return array_diff($qualA, $qualB) && array_diff($qualB, $qualA);
    }

    /** Parenthesised parts and " - suffix" parts of a title, which usually name a place or branch. */
    private function qualifiers(string $title): array
    {
        preg_match_all('/\(([^)]{2,40})\)/u', $title, $paren);
        $dash = preg_match('/\s[-–|]\s([^-–|(]{2,40})/u', $title, $m) ? [$m[1]] : [];

        return array_map('trim', array_merge($paren[1], $dash));
    }

    private function descriptionScore(VacancyListing $listing, Vacancy $candidate): ?float
    {
        $mine = $listing->simhash ?? TextSimilarity::simhash($listing->description);
        $theirs = $candidate->listings()->whereNotNull('simhash')->pluck('simhash');
        if ($mine === null || $theirs->isEmpty()) {
            return null;
        }

        return $theirs->map(fn ($s) => TextSimilarity::simhashSimilarity((int) $mine, (int) $s))->max();
    }

    private function judge(VacancyListing $listing, string $title, Vacancy $candidate): ?array
    {
        $other = $candidate->listings()->whereNotNull('description')->first();
        $describe = fn (string $t, ?string $company, ?string $loc, ?string $desc) => "Judul: {$t}\nPerusahaan: {$company}\nLokasi: {$loc}\nDeskripsi: ".mb_substr((string) $desc, 0, 1200);

        return $this->ai->structured(
            'dedup_judge',
            'Tentukan apakah dua iklan berikut adalah lowongan yang SAMA (posisi, perusahaan, dan lokasi sama, mungkin ditulis ulang di platform berbeda). '
                .'Level berbeda (junior vs senior), lokasi berbeda, atau posisi berbeda berarti BUKAN lowongan yang sama. Jawab hati-hati; pilih confidence high hanya bila yakin.',
            "IKLAN A\n".$describe($title, $listing->raw_company, $listing->raw_location, $listing->description)
                ."\n\nIKLAN B\n".$describe($candidate->title, $candidate->company?->name, $candidate->location_text, $other?->description),
            ['type' => 'object', 'additionalProperties' => false, 'required' => ['same_job', 'confidence', 'reason'], 'properties' => [
                'same_job' => ['type' => 'boolean'],
                'confidence' => ['type' => 'string', 'enum' => ['high', 'medium', 'low']],
                'reason' => ['type' => 'string'],
            ]],
            ['vacancy_listing_id' => $listing->exists ? $listing->id : null],
        );
    }
}
