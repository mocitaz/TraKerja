<?php

namespace App\JobEngine\Intelligence;

use App\Helpers\CategoryHelper;
use App\JobEngine\Ai\AiGateway;
use App\JobEngine\Normalize\PiiScrubber;
use App\JobEngine\Normalize\RegionResolver;
use App\JobEngine\Vocabulary;
use App\Models\Vacancy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Completes a vacancy with gpt-5-nano (TraKerja 2.0, Fase 3). Rules:
 * - structured source data is never overwritten: the AI only fills empty fields;
 * - every answer is validated (enums, plausible salary, resolvable city); an invalid answer is
 *   retried once with gpt-5-mini, and a second failure leaves the fields empty;
 * - the summary is rewritten by the AI but scrubbed for personal data again;
 * - scam rules always run, with or without AI.
 */
class AiEnricher
{
    private const INSTRUCTIONS = "Kamu melengkapi data lowongan kerja Indonesia. Isi JSON sesuai skema.\n"
        ."- Jangan mengarang: kota, provinsi, gaji, pendidikan diisi null bila tidak tertulis.\n"
        ."- sector, seniority, dan skills boleh disimpulkan dari isi lowongan.\n"
        ."- seniority dari tanggung jawab dan judul: intern (magang/trainee), entry (staff, fresh graduate), mid, senior, lead, manager.\n"
        ."- Gaji dalam angka penuh dan mata uang aslinya; period sesuai teks (month bila per bulan).\n"
        ."- summary: maksimal 2 kalimat bahasa Indonesia tentang pekerjaan dan syarat utama, TANPA nomor HP, email, atau nama orang.\n"
        ."- skills: maksimal 8 keterampilan konkret.\n"
        ."- scam_risk high hanya bila ada tanda penipuan (biaya pendaftaran, transfer, kerja dari HP gaji harian tanpa syarat).";

    public function __construct(private AiGateway $ai, private RegionResolver $regions) {}

    /** @return 'enriched'|'skipped_budget'|'failed' */
    public function enrich(Vacancy $vacancy): string
    {
        $listing = $vacancy->listings()->whereNotNull('description')->orderByDesc('last_seen_at')->first()
            ?? $vacancy->listings()->first();
        $description = $listing?->description ?? $vacancy->summary;

        $rules = ScamDetector::check($vacancy->title, $description);
        $input = Str::limit(implode("\n", array_filter([
            'Judul: '.$vacancy->title,
            'Perusahaan: '.$vacancy->company?->name,
            $vacancy->location_text ? 'Lokasi: '.$vacancy->location_text : null,
            'Deskripsi: '.preg_replace('/\s+/u', ' ', (string) $description),
        ])), 3500, '');

        $context = ['vacancy_listing_id' => $listing?->id];
        $answer = $this->ai->structured('extract', self::INSTRUCTIONS, $input, $this->schema(), $context);
        if ($answer !== null && ! $this->valid($answer)) {
            $answer = $this->ai->structured('extract', self::INSTRUCTIONS, $input, $this->schema(), $context, fallback: true);
            $answer = $answer !== null && $this->valid($answer) ? $answer : null;
        }

        $filled = [];
        $updates = ['enriched_at' => now()];
        if ($answer) {
            $sectorId = $answer['sector'] !== 'Lainnya' ? DB::table('taxonomy_sectors')->where('name', $answer['sector'])->value('id') : null;
            $candidates = [
                'sector_id' => $sectorId,
                'seniority' => $answer['seniority'],
                'education_min' => $answer['education_min'],
                'employment_type' => $answer['employment_type'],
                'work_mode' => $answer['work_mode'],
                'region_id' => $answer['city'] || $answer['province'] ? $this->regions->resolve($answer['city'], $answer['province']) : null,
                'skills' => $answer['skills'] ?: null,
            ];
            if ($vacancy->salary_min === null && $answer['salary_min'] !== null) {
                $candidates += ['salary_min' => $answer['salary_min'], 'salary_max' => $answer['salary_max'],
                    'salary_currency' => $answer['salary_currency'], 'salary_period' => $answer['salary_period']];
            }
            foreach ($candidates as $field => $value) {
                if ($value !== null && $vacancy->{$field} === null) {
                    $updates[$field] = $value;
                    $filled[] = $field;
                }
            }
            if (! $vacancy->is_internship && $answer['is_internship']) {
                $updates['is_internship'] = true;
                $filled[] = 'is_internship';
            }
            $summary = trim((string) PiiScrubber::scrub($answer['summary']));
            if ($summary !== '') {
                $updates['summary'] = Str::limit($summary, 500);
                $filled[] = 'summary';
            }
            if ($answer['scam_risk'] === 'high') {
                $rules['reasons'][] = 'ai: '.$answer['scam_risk'];
                $rules['flag'] = 'suspected';
            }
        }

        if ($vacancy->scam_flag !== 'confirmed') {
            $updates['scam_flag'] = $rules['flag'];
        }
        $updates['ai_filled'] = $filled ?: null;
        $vacancy->update($updates);

        return $answer ? 'enriched' : ($this->ai->spentToday() >= (float) config('services.openai.jobs.daily_budget_usd') ? 'skipped_budget' : 'failed');
    }

    private function valid(array $a): bool
    {
        foreach (['seniority' => Vocabulary::SENIORITY, 'education_min' => Vocabulary::EDUCATION,
            'employment_type' => Vocabulary::EMPLOYMENT_TYPES, 'work_mode' => Vocabulary::WORK_MODES] as $field => $allowed) {
            if (($a[$field] ?? null) !== null && ! in_array($a[$field], $allowed, true)) {
                return false;
            }
        }
        if ($a['salary_min'] !== null) {
            $max = $a['salary_max'] ?? $a['salary_min'];
            if ($a['salary_min'] <= 0 || $max < $a['salary_min']) {
                return false;
            }
            if (($a['salary_currency'] ?? 'IDR') === 'IDR' && ($a['salary_period'] ?? 'month') === 'month'
                && ($a['salary_min'] < 300_000 || $max > 1_000_000_000)) {
                return false;
            }
        }

        return mb_strlen((string) $a['summary']) <= 600 && count($a['skills']) <= 8;
    }

    private function schema(): array
    {
        $nullable = fn (array $enum) => ['type' => ['string', 'null'], 'enum' => array_merge($enum, [null])];

        $properties = [
            'sector' => ['type' => 'string', 'enum' => array_merge(CategoryHelper::getSektors(), ['Lainnya'])],
            'seniority' => $nullable(Vocabulary::SENIORITY),
            'education_min' => $nullable(Vocabulary::EDUCATION),
            'employment_type' => $nullable(Vocabulary::EMPLOYMENT_TYPES),
            'work_mode' => $nullable(Vocabulary::WORK_MODES),
            'is_internship' => ['type' => 'boolean'],
            'city' => ['type' => ['string', 'null']],
            'province' => ['type' => ['string', 'null']],
            'salary_min' => ['type' => ['integer', 'null']],
            'salary_max' => ['type' => ['integer', 'null']],
            'salary_currency' => ['type' => ['string', 'null']],
            'salary_period' => $nullable(Vocabulary::SALARY_PERIODS),
            'summary' => ['type' => 'string'],
            'skills' => ['type' => 'array', 'items' => ['type' => 'string']],
            'scam_risk' => ['type' => 'string', 'enum' => ['low', 'medium', 'high']],
        ];

        return ['type' => 'object', 'additionalProperties' => false, 'required' => array_keys($properties), 'properties' => $properties];
    }
}
