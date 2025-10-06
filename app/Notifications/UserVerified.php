<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class UserVerified extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Ensure $data is an array
        $data = $notifiable->data;
        if (is_string($data)) {
            $data = json_decode($data, true) ?? [];
        }

        $name = $data['name'] ?? 'User';
        $password = $data['pw'] ?? '';
        // dd($data, $name);

        return (new MailMessage)
            ->subject('Akun Kinerja SAC Anda Telah Aktif')
            ->greeting('Halo, Sobat!')
            ->line('Selamat! Akun Kinerja SAC Anda kini sudah aktif dan dapat digunakan untuk mengakses sistem kami.')
            ->lineIf(!empty($name), new HtmlString('<strong>Username:</strong> ' . $name))
            ->lineIf(!empty($password), new HtmlString('<strong>Password:</strong> ' . $password))
            ->line('')
            ->action('Buka Aplikasi Kinerja SAC', 'https://absensi-sac.sac-po.com')
            ->line('')
            ->line('Jika Anda mengalami kesulitan saat masuk, silakan hubungi tim dukungan kami.')
            ->line('*Email ini dikirim secara otomatis. Mohon untuk tidak membalas.*')
            ->salutation('Tetap semangat dan sukses selalu,  
            Tim Kinerja SAC')
            ->success();
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
