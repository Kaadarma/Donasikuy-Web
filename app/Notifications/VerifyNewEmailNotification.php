<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyNewEmailNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $name,
        public string $verifyUrl
    ) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Verifikasi Email Baru Donasikuy')
            ->greeting('Halo ' . $this->name . '!')
            ->line('Kamu baru saja meminta untuk mengganti email akun Donasikuy.')
            ->action('Verifikasi Email Baru', $this->verifyUrl)
            ->line('Jika kamu tidak merasa melakukan perubahan ini, abaikan email ini.');
    }
}

