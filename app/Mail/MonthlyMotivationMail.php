<?php

namespace App\Mail;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MonthlyMotivationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $monthName;
    public $year;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user,
    ) {
        $now = Carbon::now('Asia/Jakarta');
        $this->monthName = $now->locale('id')->monthName;
        $this->year = $now->year;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Bulan Baru Semangat Baru - {$this->monthName} {$this->year}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.monthly_motivation',
            with: [
                'monthName' => $this->monthName,
                'year' => $this->year,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

