<?php

namespace App\JobEngine\Intelligence;

use App\Models\Vacancy;
use Illuminate\Support\Facades\DB;

/** Folds a duplicate vacancy into the canonical one, keeping every user reference intact. */
class VacancyMerger
{
    /** Fields copied from the duplicate only when the canonical vacancy has no value yet. */
    private const FILLABLE = ['region_id', 'location_text', 'country', 'salary_min', 'salary_max', 'salary_currency', 'salary_period',
        'employment_type', 'work_mode', 'seniority', 'education_min', 'valid_through', 'date_posted', 'sector_id', 'major_id', 'summary', 'skills'];

    public function merge(Vacancy $duplicate, Vacancy $into, string $method = 'fuzzy', float $score = 1.0): Vacancy
    {
        if ($duplicate->is($into)) {
            return $into;
        }

        return DB::transaction(function () use ($duplicate, $into, $method, $score) {
            // Audit trail: which listings moved, from where, why. jobs:unmerge reverses exactly this.
            foreach (DB::table('vacancy_listings')->where('vacancy_id', $duplicate->id)->pluck('id') as $listingId) {
                DB::table('dedup_reviews')->insert([
                    'vacancy_listing_id' => $listingId, 'candidate_vacancy_id' => $into->id, 'score' => $score, 'status' => 'merged',
                    'score_detail' => json_encode(['from_vacancy_id' => $duplicate->id, 'method' => $method]),
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
            DB::table('vacancy_listings')->where('vacancy_id', $duplicate->id)->update(['vacancy_id' => $into->id, 'match_method' => $method]);
            DB::table('job_applications')->where('vacancy_id', $duplicate->id)->update(['vacancy_id' => $into->id]);
            // A user may have bookmarked both: keep one bookmark.
            $already = DB::table('job_bookmarks')->where('vacancy_id', $into->id)->pluck('user_id');
            DB::table('job_bookmarks')->where('vacancy_id', $duplicate->id)->whereIn('user_id', $already)->delete();
            DB::table('job_bookmarks')->where('vacancy_id', $duplicate->id)->update(['vacancy_id' => $into->id]);
            DB::table('vacancy_reports')->where('vacancy_id', $duplicate->id)->update(['vacancy_id' => $into->id]);

            $copied = [];
            foreach (self::FILLABLE as $field) {
                if ($into->{$field} === null && $duplicate->{$field} !== null) {
                    $into->{$field} = $duplicate->{$field};
                    $copied[] = $field;
                }
            }
            // Remember which fields came from the duplicate, so unmerge can clear them again.
            DB::table('dedup_reviews')->where('status', 'merged')->where('candidate_vacancy_id', $into->id)
                ->whereIn('vacancy_listing_id', DB::table('vacancy_listings')->where('vacancy_id', $into->id)->pluck('id'))
                ->get()->filter(fn ($r) => (json_decode((string) $r->score_detail, true)['from_vacancy_id'] ?? null) === $duplicate->id)
                ->each(fn ($r) => DB::table('dedup_reviews')->where('id', $r->id)->update([
                    'score_detail' => json_encode(json_decode((string) $r->score_detail, true) + ['copied_fields' => $copied]),
                ]));
            if ($duplicate->status === 'active') {
                $into->status = 'active';
                $into->closed_at = null;
                $into->closed_reason = null;
            }
            $into->first_seen_at = min($into->first_seen_at ?? now(), $duplicate->first_seen_at ?? now());
            $into->listings_count = $into->listings()->count();
            $into->save();

            $duplicate->update(['status' => 'archived', 'closed_reason' => 'merged', 'closed_at' => now(), 'merged_into_id' => $into->id, 'listings_count' => 0]);

            return $into->refresh();
        });
    }

    /** Reverse a merge recorded by merge(): move the listings back and reactivate the duplicate. */
    public function unmerge(Vacancy $duplicate): int
    {
        return DB::transaction(function () use ($duplicate) {
            $rows = DB::table('dedup_reviews')->where('status', 'merged')->get()
                ->filter(fn ($r) => (json_decode((string) $r->score_detail, true)['from_vacancy_id'] ?? null) === $duplicate->id);
            foreach ($rows as $row) {
                DB::table('vacancy_listings')->where('id', $row->vacancy_listing_id)->update(['vacancy_id' => $duplicate->id, 'match_method' => 'new']);
                DB::table('dedup_reviews')->where('id', $row->id)->update(['status' => 'separate', 'reviewed_at' => now(), 'updated_at' => now()]);
            }
            $duplicate->update(['status' => 'active', 'closed_reason' => null, 'closed_at' => null, 'merged_into_id' => null,
                'listings_count' => $duplicate->listings()->count(), 'primary_listing_id' => $duplicate->listings()->min('id')]);
            foreach ($rows->groupBy('candidate_vacancy_id') as $intoId => $intoRows) {
                $into = Vacancy::find($intoId);
                if (! $into) {
                    continue;
                }
                // Clear values that only came from the duplicate.
                $copied = $intoRows->flatMap(fn ($r) => json_decode((string) $r->score_detail, true)['copied_fields'] ?? [])->unique();
                $into->update($copied->mapWithKeys(fn ($f) => [$f => null])->all() + [
                    'listings_count' => $into->listings()->count(), 'primary_listing_id' => $into->listings()->min('id'),
                ]);
            }

            return $rows->count();
        });
    }
}
