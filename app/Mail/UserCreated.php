<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserCreated extends Mailable
{
    use Queueable;
    use SerializesModels;

    private User $creator;

    private string $password;

    private User $user;

    public function __construct(User $user, User $creator)
    {
        $this->creator = $creator;
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('user.mail.user_created.subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.'.app()->getLocale().'.user-created',
            with: [
                'creator' => $this->creator,
                'user' => $this->user,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
