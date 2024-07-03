<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendRecoveryToken extends Mailable
{
    use Queueable;
    use SerializesModels;

    public string $email;
    public string $token;

    public function __construct(string $email, string $token)
    {
        $this->email = $email;
        $this->token = $token;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            to: $this->email,
            subject: 'Password reset',
        );
    }

    public function build(): self
    {
        return $this->markdown('emails.user-password-reset', ['token' => $this->token]);
    }
}
