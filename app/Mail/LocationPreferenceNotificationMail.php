<?php

namespace App\Mail;

use App\Models\Student;
use App\Models\JobPortalApplication;
use App\Models\InterviewLocation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LocationPreferenceNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $application;
    public $location;
    public $preferenceReason;

    /**
     * Create a new message instance.
     */
    public function __construct(Student $student, JobPortalApplication $application, InterviewLocation $location, $preferenceReason = null)
    {
        $this->student = $student;
        $this->application = $application;
        $this->location = $location;
        $this->preferenceReason = $preferenceReason;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📍 Interview Location Preference Submitted - Application #' . $this->application->application_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.location-preference-notification',
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


