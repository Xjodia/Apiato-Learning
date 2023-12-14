<?php

namespace App\Containers\AppSection\Product\Mails;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProductExporterMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    protected string $file;
    protected string $emailName;
    protected string $filePath;
    public function __construct($file, $emailName, $filePath) {
        $this->file = $file;
        $this->emailName = $emailName;
        $this->filePath = $filePath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Product Export',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'ship::product-export-email',
            with: [
                'file' => $this->file,
                'emailName' => $this->emailName
            ],
        );
    }
    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->filePath)
        ];
    }
}
