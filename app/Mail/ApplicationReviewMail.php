<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationReviewMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $reviewType;
    public $status;
    public $comments;
    public $reviewerName;

    /**
     * Create a new message instance.
     */
    public function __construct($application, $reviewType, $status, $comments, $reviewerName)
    {
        $this->application = $application;
        $this->reviewType = $reviewType;
        $this->status = $status;
        $this->comments = $comments;
        $this->reviewerName = $reviewerName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->getSubject();
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $view = $this->getView();
        return new Content(
            view: $view,
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Get the appropriate subject based on review type and status
     */
    private function getSubject()
    {
        $applicationNumber = $this->application->application_number;
        $studentName = $this->application->student->name;
        
        // Handle status-based subjects first
        switch ($this->status) {
            case 'interview_scheduled':
                return "📅 Interview Scheduled - Application #{$applicationNumber}";
            case 'interview_completed':
                return "📋 Interview Results - Application #{$applicationNumber}";
            case 'selected':
                return "🎉 Congratulations! Application Selected - Application #{$applicationNumber}";
            case 'batch_assigned':
                return "🎯 Batch Assignment - Application #{$applicationNumber}";
            case 'training_started':
                return "🚀 Training Commenced - Application #{$applicationNumber}";
            case 'training_completed':
                return "🎓 Training Completed - Application #{$applicationNumber}";
            case 'deployed':
                return "🎯 Service Deployed - Application #{$applicationNumber}";
        }
        
        // Handle review type-based subjects
        switch ($this->reviewType) {
            case 'document_verification':
                if ($this->status === 'approved') {
                    return "✅ Document Verification Complete - Application #{$applicationNumber}";
                } elseif ($this->status === 'rejected') {
                    return "❌ Document Verification Failed - Application #{$applicationNumber}";
                } else {
                    return "📋 Document Verification Update - Application #{$applicationNumber}";
                }
                
            case 'basic_criteria_check':
                if ($this->status === 'approved') {
                    return "✅ Basic Criteria Approved - Application #{$applicationNumber}";
                } elseif ($this->status === 'rejected') {
                    return "❌ Basic Criteria Not Met - Application #{$applicationNumber}";
                } else {
                    return "📋 Basic Criteria Review Update - Application #{$applicationNumber}";
                }
                
            case 'final_approval':
                if ($this->status === 'approved') {
                    return "🎉 Congratulations! Application Approved - Application #{$applicationNumber}";
                } elseif ($this->status === 'rejected') {
                    return "❌ Application Not Selected - Application #{$applicationNumber}";
                } else {
                    return "📋 Final Review Update - Application #{$applicationNumber}";
                }
                
            case 'rejection':
                return "❌ Application Status Update - Application #{$applicationNumber}";
                
            default:
                return "📋 Application Review Update - Application #{$applicationNumber}";
        }
    }

    /**
     * Get the appropriate view based on review type and status
     */
    private function getView()
    {
        // Handle status-based views first
        switch ($this->status) {
            case 'interview_scheduled':
                return 'emails.application.interview-scheduled';
            case 'interview_completed':
                return 'emails.application.interview-completed';
            case 'selected':
                // Check if this is a service offer letter or joining instructions
                if ($this->reviewType === 'service_offer_letter') {
                    return 'emails.application.service-offer-letter';
                } elseif ($this->reviewType === 'joining_instructions') {
                    return 'emails.application.joining-instructions';
                } else {
                    return 'emails.application.final-approval'; // Default final approval template
                }
            case 'batch_assigned':
                return 'emails.application.batch-assigned';
            case 'training_started':
                return 'emails.application.training-started';
            case 'training_completed':
                return 'emails.application.training-completed';
            case 'deployed':
                return 'emails.application.deployed';
        }
        
        // Handle review type-based views
        switch ($this->reviewType) {
            case 'document_verification':
                return 'emails.application.document-verification';
                
            case 'basic_criteria_check':
                return 'emails.application.basic-criteria';
                
            case 'final_approval':
                return 'emails.application.final-approval';
                
            case 'rejection':
                return 'emails.application.rejection';
                
            default:
                return 'emails.application.general-update';
        }
    }
}
