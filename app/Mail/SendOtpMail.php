<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Kode OTP untuk verifikasi email baru admin (dikirim ke email BARU, bukan email lama). */
class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $otpCode,
        public string $adminName,
        public int $expiresInMinutes,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Kode verifikasi email: {$this->otpCode} — " . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.otp');
    }
}
