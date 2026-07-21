<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable;
    use SerializesModels;

    private string $resetUrl;

    private User $user;

    public function __construct(User $user, string $resetUrl)
    {
        $this->resetUrl = $resetUrl;
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('user.mail.reset_password.subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'mail.'.app()->getLocale().'.reset-password',
            with: [
                'reset_url' => $this->resetUrl,
                'user' => $this->user,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
