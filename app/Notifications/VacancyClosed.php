<?php

namespace App\Notifications;

use App\Mail\VacancyClosedMail;
use App\Models\User;
use Illuminate\Notifications\Notification;

/** A vacancy the user applied to (still in progress) or saved has closed at its source (TraKerja 2.0, Fase 6). */
class VacancyClosed extends Notification
{
    /** @param 'applied'|'saved' $relation */
    public function __construct(public int $vacancyId, public string $title, public ?string $company, public string $relation, public ?int $applicationId = null) {}

    public function via(User $notifiable): array
    {
        return $notifiable->wantsEmailNotification('job_closed') ? ['database', 'mail'] : ['database'];
    }

    public function toMail(User $notifiable): VacancyClosedMail
    {
        return (new VacancyClosedMail($notifiable, $this->title, $this->company, $this->relation, $this->applicationId))->to($notifiable->email);
    }

    public function toArray(User $notifiable): array
    {
        return [
            'kind' => 'vacancy_closed',
            'vacancy_id' => $this->vacancyId,
            'title' => $this->title,
            'company' => $this->company,
            'relation' => $this->relation,
            'application_id' => $this->applicationId,
        ];
    }
}
