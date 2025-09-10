<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class ContractActive extends Notification
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
        return (new MailMessage)
            ->subject('🎉 Kontrak Anda Berhasil Diperbarui')
            ->greeting('Halo Sobat,')
            ->line('Kami punya kabar baik untuk Anda! 🎊')
            ->line('Kontrak Anda telah **resmi diperbarui** dan sekarang sudah aktif di sistem.')
            ->line('Segera cek dan tanda tangan jika anda setuju, untuk detailnya melalui aplikasi Kinerja PT. SAC.')
            ->action('👉 Buka Aplikasi Kinerja PT. SAC', 'https://absensi-sac.sac-po.com')
            ->line('Jika Anda mengalami kendala saat masuk, silakan hubungi tim dukungan kami.')
            ->line('---')
            ->line('*Email ini dikirim secara otomatis. Mohon untuk tidak membalas.*')
            ->salutation('Tetap semangat & sukses selalu, Tim Kinerja PT. SAC')
            ->success();

    }
}