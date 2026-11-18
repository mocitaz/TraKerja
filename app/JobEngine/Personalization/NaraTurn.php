<?php

namespace App\JobEngine\Personalization;

use App\Models\SavedJobSearch;
use App\Models\User;

/** What happened during one Nara reply: which vacancies were offered (and may become cards), the latest search, alerts. */
final class NaraTurn
{
    /** @var array<int, int> */
    public array $offered = [];

    /** @var array{criteria: array, label: string, shown: array<int, int>}|null */
    public ?array $search = null;

    public bool $alertSaved = false;

    /** Interview practice touched during this reply (its progress becomes the progress card). */
    public ?\App\Models\NaraInterview $interview = null;

    /** @var array<int, int> vacancy ids compared during this reply (rendered as the comparison table) */
    public array $compared = [];

    /** @param callable(string): void|null $onStatus  called with a short status text when a tool starts */
    public function __construct(public $onStatus = null) {}

    /** @param array<int, int> $ids */
    public function offer(array $ids): void
    {
        $this->offered = array_values(array_unique([...$this->offered, ...array_map('intval', $ids)]));
    }

    public function status(string $text): void
    {
        if ($this->onStatus) {
            ($this->onStatus)($text);
        }
    }

    /** Shape the reply formatter expects: offered job ids, and whether to offer "save this search". @return array<string, mixed> */
    public function asJobs(NaraJobFinder $finder, User $user): array
    {
        $offer = false;
        if ($this->search !== null && ! $this->alertSaved) {
            $filters = $finder->savedSearchFilters($this->search['criteria']);
            // Offered once: not when the filters are too broad to alert on, nor when this search is already saved.
            $offer = VacancyFilters::narrows($filters)
                && ! SavedJobSearch::where('user_id', $user->id)->where('fingerprint', hash('sha256', json_encode($filters)))->exists();
        }

        return ['jobs' => array_map(fn ($id) => ['id' => $id], $this->offered), 'label' => $this->search['label'] ?? '', 'can_save_search' => $offer];
    }
}
