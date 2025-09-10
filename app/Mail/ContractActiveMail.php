<?php

namespace App\Mail;

use App\Models\UserAbsensi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContractActiveMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(UserAbsensi $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Kontrak Aktif - ' . $this->user->nama_lengkap)
                    ->view('emails.contract_active');
    }
}
