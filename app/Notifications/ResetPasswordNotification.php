<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(public string $token)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('Reset Password Akun SIMAGANG')
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Kami menerima permintaan untuk mereset password akun SIMAGANG Anda.')
            ->line('Silakan klik tombol di bawah ini untuk membuat password baru.')
            ->action('Reset Password', $url)
            ->line('Link reset password ini hanya berlaku dalam waktu terbatas.')
            ->line('Jika Anda tidak meminta reset password, abaikan email ini.');
    }
}