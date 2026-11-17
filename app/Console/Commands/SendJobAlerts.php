<?php

namespace App\Console\Commands;

use App\JobEngine\Personalization\VacancyFilters;
use App\Models\SavedJobSearch;
use App\Models\Vacancy;
use App\Notifications\SavedSearchAlert;
use Illuminate\Console\Command;

/**
 * Saved-search alerts (TraKerja 2.0, Fase 6): for each search with alerts on, the visible vacancies first
 * seen since the last alert, sent as one digest per user (in-app, plus email when the user allows it).
 */
class SendJobAlerts extends Command
{
    protected $signature = 'jobs:alerts {--dry-run : Count what would be sent without sending} {--user= : Only this user id}';

    protected $description = 'Send saved-search alerts for new matching vacancies';

    public function handle(): int
    {
        $searches = SavedJobSearch::with('user')->whereIn('alert_channel', ['email', 'inapp'])
            ->when($this->option('user'), fn ($q, $id) => $q->where('user_id', $id))
            ->get()
            ->filter(fn ($s) => $s->user && $s->user->notify_job_alerts && VacancyFilters::narrows((array) $s->filters) && $this->due($s));

        $sent = $matched = 0;
        foreach ($searches->groupBy('user_id') as $userSearches) {
            $user = $userSearches->first()->user;
            $digest = [];
            foreach ($userSearches as $search) {
                $since = $search->last_alerted_at ?? $search->updated_at;
                $query = VacancyFilters::apply(Vacancy::visible()->with(['company', 'region']), (array) $search->filters)
                    ->where('first_seen_at', '>', $since);
                $total = (clone $query)->count();
                if ($total > 0) {
                    $digest[] = [
                        'name' => $search->name, 'total' => $total, 'search_id' => $search->id,
                        'jobs' => $query->orderByDesc('first_seen_at')->limit(5)->get()->map(fn (Vacancy $v) => [
                            'title' => $v->title, 'company' => $v->company?->name, 'location' => $v->location,
                            'salary' => $v->salaryLabel($user->preferredLocale()), 'url' => $v->public_path,
                        ])->all(),
                    ];
                    $matched += $total;
                }
            }
            if ($this->option('dry-run')) {
                $this->line("user {$user->id}: ".count($digest).' searches with new jobs');

                continue;
            }
            if ($digest) {
                $user->notify(new SavedSearchAlert($digest));
                $sent++;
            }
            SavedJobSearch::whereIn('id', $userSearches->pluck('id'))->update(['last_alerted_at' => now()]);
        }

        $this->info(($this->option('dry-run') ? '[dry run] ' : '')."Alerts: {$sent} users notified, {$matched} new matches across {$searches->count()} due searches.");

        return self::SUCCESS;
    }

    private function due(SavedJobSearch $search): bool
    {
        if (! $search->last_alerted_at) {
            return true;
        }

        return $search->last_alerted_at->lte($search->alert_frequency === 'weekly' ? now()->subDays(6)->subHours(12) : now()->subHours(20));
    }
}
