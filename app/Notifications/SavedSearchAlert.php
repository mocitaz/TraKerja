<?php

namespace App\Notifications;

use App\Mail\JobAlertMail;
use App\Models\User;
use Illuminate\Notifications\Notification;

/**
 * New vacancies for one or more of a user's saved searches (TraKerja 2.0, Fase 6). One digest per run.
 *
 * @param  array<int, array{name: string, total: int, search_id: int, jobs: array<int, array{title: string, company: ?string, location: ?string, url: string}>}>  $searches
 */
class SavedSearchAlert extends Notification
{
    public function __construct(public array $searches) {}

    public function via(User $notifiable): array
    {
        return $notifiable->wantsEmailNotification('job_alerts') ? ['database', 'mail'] : ['database'];
    }

    public function toMail(User $notifiable): JobAlertMail
    {
        return (new JobAlertMail($notifiable, $this->searches))->to($notifiable->email);
    }

    public function toArray(User $notifiable): array
    {
        $total = array_sum(array_column($this->searches, 'total'));

        return [
            'kind' => 'job_alert',
            'total' => $total,
            'searches' => array_map(fn ($s) => ['name' => $s['name'], 'total' => $s['total'], 'search_id' => $s['search_id']], $this->searches),
            'first' => $this->searches[0]['jobs'][0] ?? null,
        ];
    }
}
