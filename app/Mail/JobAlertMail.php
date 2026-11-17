<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/** Daily digest of new vacancies for a user's saved searches (TraKerja 2.0, Fase 6). */
class JobAlertMail extends Mailable
{
    use Queueable;

    public function __construct(public User $user, public array $searches) {}

    public function envelope(): Envelope
    {
        $total = array_sum(array_column($this->searches, 'total'));

        return new Envelope(subject: trans_choice('emails.job_alert.subject', $total, ['count' => $total], $this->user->preferredLocale()));
    }

    public function content(): Content
    {
        return new Content(view: 'emails.job_alert', with: ['user' => $this->user, 'searches' => $this->searches]);
    }
}
