<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResidentResetPasswordNotification extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
{
    $url = url(route('resident.password.reset', [
        'token' => $this->token,
        'email' => $notifiable->email,
    ], false));

    return (new MailMessage)
            ->subject('Reset Your Password')
            ->greeting('Good day!')
            ->line('We received a request to reset the password associated with your account.')
            ->line('If you made this request, please click the button below to proceed.')
            ->action('Reset Password', $url)
            ->line('If you did not request a password reset, no further action is required. Your account remains secure.')
            ->salutation('Sincerely,');
}

}
