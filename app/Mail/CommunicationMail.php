<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CommunicationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $messageContent;
    public $studentName;
    public $applicationNumber;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $message, $studentName, $applicationNumber)
    {
        $this->subject = $subject;
        $this->messageContent = $message;
        $this->studentName = $studentName;
        $this->applicationNumber = $applicationNumber;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject ?: 'Message from National Service LMS',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.communication',
            with: [
                'subject' => $this->subject,
                'messageContent' => $this->messageContent,
                'studentName' => $this->studentName,
                'applicationNumber' => $this->applicationNumber,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
