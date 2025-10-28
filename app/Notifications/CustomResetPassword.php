<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class CustomResetPassword extends ResetPassword
{

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('[TraKerja] Password Reset Request')
            ->view('emails.password-reset', [
                'actionUrl' => $this->resetUrl($notifiable),
            ]);
    }

    protected function resetUrl($notifiable)
    {
        return URL::route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], true);
    }
}


