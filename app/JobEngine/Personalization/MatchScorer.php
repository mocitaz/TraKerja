<?php

namespace App\JobEngine\Personalization;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * CV-to-job match score, 0–100 (TraKerja 2.0, Fase 6, premium).
 * 70% meaning (embedding similarity of CV and vacancy, rescaled) + 30% skills the vacancy lists that the CV has.
 * Calibrated on local data: cosine ≈ 0.37 for unrelated jobs and ≈ 0.72 for the best fits.
 */
class MatchScorer
{
    private const COS_FLOOR = 0.42;

    private const COS_CEIL = 0.70;

    public function __construct(private Embeddings $embeddings) {}

    /**
     * @param  iterable<\App\Models\Vacancy>  $vacancies
     * @return array<int, array{score: int, label: string, matched: array<int, string>, missing: array<int, string>, level_gap: bool}>
     */
    public function forVacancies(User $user, iterable $vacancies): array
    {
        $profile = $this->embeddings->forUser($user);
        if ($profile === null) {
            return [];
        }
        $ids = collect($vacancies)->pluck('id')->all();
        $vectors = DB::table('vacancy_embeddings')->whereIn('vacancy_id', $ids)->pluck('vector', 'vacancy_id');
        $skills = self::userSkills($user);
        $years = self::experienceYears($user);

        $out = [];
        foreach ($vacancies as $vacancy) {
            if (! isset($vectors[$vacancy->id])) {
                continue;
            }
            $out[$vacancy->id] = self::score(Embeddings::cosine($profile, Embeddings::unpack($vectors[$vacancy->id])), (array) $vacancy->skills, $skills, $vacancy->seniority, $years);
        }

        return $out;
    }

    /**
     * Visible vacancy ids ordered best match first, for the "Best match" sort. Cached per CV version.
     *
     * @return array<int, int>
     */
    public function rankedIds(User $user): array
    {
        $profile = $this->embeddings->forUser($user);
        if ($profile === null) {
            return [];
        }
        $version = DB::table('user_embeddings')->where('user_id', $user->id)->value('text_hash').'|'.DB::table('vacancy_embeddings')->max('updated_at');

        return Cache::remember('match-rank:'.$user->id.':'.md5($version), 600, function () use ($user, $profile) {
            $skills = self::userSkills($user);
            $years = self::experienceYears($user);
            $scores = [];
            DB::table('vacancy_embeddings')
                ->join('vacancies', 'vacancies.id', '=', 'vacancy_embeddings.vacancy_id')
                ->where('vacancies.status', 'active')->whereNull('vacancies.merged_into_id')->where('vacancies.scam_flag', '!=', 'suspected')
                ->select('vacancies.id', 'vacancies.skills', 'vacancies.seniority', 'vacancy_embeddings.vector')
                ->orderBy('vacancies.id')
                ->chunk(1000, function ($rows) use (&$scores, $profile, $skills, $years) {
                    foreach ($rows as $row) {
                        $scores[$row->id] = self::score(Embeddings::cosine($profile, Embeddings::unpack($row->vector)), (array) json_decode((string) $row->skills, true), $skills, $row->seniority, $years)['score'];
                    }
                });
            arsort($scores);

            return array_keys($scores);
        });
    }

    /** Years of experience a level usually asks for; below it the score is reduced. */
    private const LEVEL_YEARS = ['senior' => 3, 'lead' => 5, 'manager' => 5];

    /** @param array<int, string> $jobSkills  @param array<int, string> $userSkills  (lowercase) */
    public static function score(float $cosine, array $jobSkills, array $userSkills, ?string $seniority = null, ?float $years = null): array
    {
        $semantic = max(0.0, min(1.0, ($cosine - self::COS_FLOOR) / (self::COS_CEIL - self::COS_FLOOR)));
        $matched = $missing = [];
        foreach ($jobSkills as $skill) {
            $s = mb_strtolower(trim((string) $skill));
            if ($s === '') {
                continue;
            }
            $hit = false;
            foreach ($userSkills as $mine) {
                if ($s === $mine || (mb_strlen($mine) >= 3 && mb_strlen($s) >= 3 && (str_contains($s, $mine) || str_contains($mine, $s)))) {
                    $hit = true;
                    break;
                }
            }
            $hit ? $matched[] = $skill : $missing[] = $skill;
        }
        $listed = count($matched) + count($missing);
        $overlap = $listed ? count($matched) / min($listed, 6) : null;
        $value = $overlap === null ? $semantic : 0.7 * $semantic + 0.3 * min(1.0, $overlap);
        $levelGap = $years !== null && isset(self::LEVEL_YEARS[$seniority]) && $years < self::LEVEL_YEARS[$seniority];
        $score = (int) round(100 * $value * ($levelGap ? 0.8 : 1.0));

        return [
            'score' => $score,
            'label' => match (true) { $score >= 75 => 'strong', $score >= 55 => 'good', default => 'weak' },
            'matched' => array_slice($matched, 0, 4),
            'missing' => array_slice($missing, 0, 4),
            'level_gap' => $levelGap,
        ];
    }

    /** Total years across work experiences (overlaps counted once per month is not needed at this precision). */
    public static function experienceYears(User $user): ?float
    {
        $months = $user->experiences()->get(['start_date', 'end_date', 'is_current'])
            ->sum(fn ($e) => $e->start_date ? $e->start_date->diffInMonths(($e->is_current || ! $e->end_date) ? now() : $e->end_date) : 0);

        return $user->experiences()->exists() ? round($months / 12, 1) : null;
    }

    /** @return array<int, string> */
    public static function userSkills(User $user): array
    {
        return $user->skills()->pluck('skill_name')->map(fn ($s) => mb_strtolower(trim((string) $s)))->filter()->unique()->values()->all();
    }
}
