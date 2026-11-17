<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/** A vacancy the user applied to or saved has closed at its source (TraKerja 2.0, Fase 6). */
class VacancyClosedMail extends Mailable
{
    use Queueable;

    public function __construct(public User $user, public string $title, public ?string $company, public string $relation, public ?int $applicationId = null) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: __('emails.vacancy_closed.subject', ['title' => $this->title], $this->user->preferredLocale()));
    }

    public function content(): Content
    {
        return new Content(view: 'emails.vacancy_closed', with: [
            'user' => $this->user, 'title' => $this->title, 'company' => $this->company,
            'relation' => $this->relation, 'applicationId' => $this->applicationId,
        ]);
    }
}
