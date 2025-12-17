<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PreRegisterVerifyNotification extends Notification
{
    use Queueable;

    public string $name;
    public string $verifyUrl;

    public function __construct(string $name, string $verifyUrl)
    {
        $this->name = $name;
        $this->verifyUrl = $verifyUrl;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Verifikasi Email Donasikuy')
            ->greeting('Halo ' . $this->name)
            ->line('Kami sudah menerima pendaftaran akun kamu di Donasikuy.')
            ->line('Klik tombol di bawah untuk memverifikasi email kamu.')
            ->action('Verifikasi Email', $this->verifyUrl)
            ->line('Link ini akan kadaluarsa dalam 30 menit.')
            ->salutation('Salam hangat, Tim Donasikuy');
    }
}
