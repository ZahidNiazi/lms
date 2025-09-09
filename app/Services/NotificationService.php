<?php

namespace App\Services;

use App\Models\NotificationTemplate;
use App\Models\JobPortalApplication;
use App\Models\Student;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send notification based on trigger event
     */
    public function sendNotification($triggerEvent, $applicationId, $additionalData = [])
    {
        try {
            $application = JobPortalApplication::with('student.profile')->findOrFail($applicationId);
            $template = NotificationTemplate::where('trigger_event', $triggerEvent)
                ->where('is_active', true)
                ->first();

            if (!$template) {
                Log::warning("No active template found for trigger event: {$triggerEvent}");
                return false;
            }

            $data = $this->prepareNotificationData($application, $template, $additionalData);

            if ($template->type === 'email') {
                return $this->sendEmail($application->student, $template, $data);
            } elseif ($template->type === 'sms') {
                return $this->sendSMS($application->student, $template, $data);
            }

            return false;
        } catch (\Exception $e) {
            Log::error("Failed to send notification: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Prepare notification data with variables
     */
    private function prepareNotificationData($application, $template, $additionalData)
    {
        $student = $application->student;
        $profile = $student->profile;
        
        $data = [
            'student_name' => $profile ? $profile->first_name . ' ' . $profile->last_name : $student->name,
            'application_number' => $application->application_number,
            'student_email' => $student->email,
            'student_phone' => $profile ? $profile->mobile_no : null,
        ];

        // Add additional data
        $data = array_merge($data, $additionalData);

        return $data;
    }

    /**
     * Send email notification
     */
    private function sendEmail($student, $template, $data)
    {
        try {
            $subject = $this->replaceVariables($template->subject, $data);
            $body = $this->replaceVariables($template->body, $data);

            // For now, we'll log the email. In production, you would use Laravel Mail
            Log::info("Email notification sent", [
                'to' => $student->email,
                'subject' => $subject,
                'body' => $body
            ]);

            // TODO: Implement actual email sending
            // Mail::to($student->email)->send(new NotificationMail($subject, $body));

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send SMS notification
     */
    private function sendSMS($student, $template, $data)
    {
        try {
            $profile = $student->profile;
            if (!$profile || !$profile->mobile_no) {
                Log::warning("No mobile number found for student: {$student->id}");
                return false;
            }

            $message = $this->replaceVariables($template->body, $data);

            // For now, we'll log the SMS. In production, you would use SMS service
            Log::info("SMS notification sent", [
                'to' => $profile->mobile_no,
                'message' => $message
            ]);

            // TODO: Implement actual SMS sending
            // SMS::to($profile->mobile_no)->send($message);

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send SMS: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Replace variables in template content
     */
    private function replaceVariables($content, $data)
    {
        foreach ($data as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }
        return $content;
    }

    /**
     * Send application received notification
     */
    public function sendApplicationReceived($applicationId)
    {
        return $this->sendNotification('application_received', $applicationId);
    }

    /**
     * Send application approved notification
     */
    public function sendApplicationApproved($applicationId)
    {
        return $this->sendNotification('application_approved', $applicationId);
    }

    /**
     * Send application rejected notification
     */
    public function sendApplicationRejected($applicationId, $rejectionReason)
    {
        return $this->sendNotification('application_rejected', $applicationId, [
            'rejection_reason' => $rejectionReason
        ]);
    }

    /**
     * Send interview scheduled notification
     */
    public function sendInterviewScheduled($applicationId, $interviewData)
    {
        try {
            $application = JobPortalApplication::with('student.profile')->findOrFail($applicationId);
            $template = NotificationTemplate::where('trigger_event', 'interview_scheduled')
                ->where('is_active', true)
                ->first();

            if (!$template) {
                Log::warning("No active template found for trigger event: interview_scheduled");
                return false;
            }

            $data = $this->prepareNotificationData($application, $template, $interviewData);

            // Create ApplicationCommunication record
            \App\Models\ApplicationCommunication::create([
                'application_id' => $application->id,
                'type' => 'email',
                'subject' => $this->replaceVariables($template->subject, $data),
                'message' => $this->replaceVariables($template->body, $data),
                'status' => 'sent',
                'recipient_info' => [
                    'name' => $application->student->name,
                    'email' => $application->student->email
                ],
                'sent_at' => now(),
                'sent_by' => auth()->id()
            ]);

            // Also send via email if configured
            if ($template->type === 'email') {
                return $this->sendEmail($application->student, $template, $data);
            } elseif ($template->type === 'sms') {
                return $this->sendSMS($application->student, $template, $data);
            }

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send interview scheduled notification: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send batch assignment notification
     */
    public function sendBatchAssigned($applicationId, $batchData)
    {
        return $this->sendNotification('batch_assigned', $applicationId, $batchData);
    }
}

