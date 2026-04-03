<?php

namespace App\Mail;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubmissionCompleted extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Submission $submission,
        public string $pathInternal,
        public string $pathExternal,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME')),
            subject: __('submission.mail.completed.subject', [
                'form_type' => $this->submission->form->name,
                'machine' => $this->submission->stockItem->machine->name,
                'stock_id' => $this->submission->stockItem->stock_id,
            ]),
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'mail.' . app()->getLocale() . '.submission-completed',
            with: [
                'submission' => $this->submission,
            ],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->pathInternal),
            Attachment::fromPath($this->pathExternal),
        ];
    }
}
