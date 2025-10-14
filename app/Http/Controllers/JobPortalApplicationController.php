<?php

namespace App\Http\Controllers;

use App\Models\JobPortalApplication;
use App\Models\ApplicationReview;
use App\Models\TrainingBatch;
use App\Models\JobPortalInterviewSchedule;
use App\Models\InterviewResult;
use App\Models\NotificationTemplate;
use App\Models\ApplicationCommunication;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class JobPortalApplicationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->type !== 'super admin') {
                return redirect()->route('job-portal.dashboard')->with('error', __('Permission Denied. Only super admin can access this area.'));
            }
            return $next($request);
        });
        $this->notificationService = $notificationService;
    }

    /**
     * Review application (document verification, basic criteria, etc.)
     */
    public function reviewApplication(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'review_type' => 'required|in:document_verification,basic_criteria_check,final_approval,rejection',
            'status' => 'required|in:pending,approved,rejected,needs_resubmission',
            'comments' => 'nullable|string',
            'document_issues' => 'nullable|array',
            'missing_documents' => 'nullable|array',
            'requires_resubmission' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $application = JobPortalApplication::findOrFail($id);

        DB::transaction(function () use ($request, $application) {
            // Create review record
            $review = ApplicationReview::create([
                'application_id' => $application->id,
                'reviewer_id' => Auth::id(),
                'review_type' => $request->review_type,
                'status' => $request->status,
                'comments' => $request->comments,
                'document_issues' => $request->document_issues,
                'missing_documents' => $request->missing_documents,
                'requires_resubmission' => $request->boolean('requires_resubmission'),
                'reviewed_at' => now()
            ]);

            // Update application status based on review
            $newStatus = $this->determineApplicationStatus($request->review_type, $request->status);
            $application->update([
                'status' => $newStatus,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'documents_verified' => $request->review_type === 'document_verification' && $request->status === 'approved',
                'basic_criteria_met' => $request->review_type === 'basic_criteria_check' && $request->status === 'approved',
                'rejection_reason' => $request->status === 'rejected' ? $request->comments : null,
                'remarks' => $request->comments
            ]);

            // Send notification to student
            $this->sendApplicationStatusNotification($application, $newStatus, $request->comments, $request->review_type);

            // If application is selected, create SMS student record and send service offer letters
            if ($newStatus === 'selected') {
                $this->createSMSStudentRecord($application);
                $this->sendServiceOfferLetters($application, $request->comments);
            }
        });

        return redirect()->back()->with('success', 'Application reviewed successfully.');
    }

    /**
     * Create SMS student record when application is selected
     */
    private function createSMSStudentRecord($application)
    {
        $student = $application->student;
        $profile = $student->profile;
        
        // Generate SMS Student ID
        $smsStudentId = 'SMS-' . date('Y') . '-' . str_pad(\App\Models\SMSStudent::count() + 1, 4, '0', STR_PAD_LEFT);

        \App\Models\SMSStudent::create([
            'job_portal_application_id' => $application->id,
            'student_id' => $smsStudentId,
            'first_name' => $profile->first_name ?? $student->name,
            'last_name' => $profile->last_name ?? '',
            'name_in_dhivehi' => $profile->name_in_dhivehi ?? null,
            'email' => $student->email,
            'national_id' => $profile->nid ?? '',
            'contact_no' => $profile->mobile_no ?? '',
            'gender' => $profile->gender ?? 'male',
            'blood_group' => $profile->blood_group ?? null,
            'date_of_birth' => $profile->dob ?? now()->subYears(20),
            'age' => $profile->dob ? \Carbon\Carbon::parse($profile->dob)->age : 20,
            'parent_name' => $student->parentDetail->parent_name ?? null,
            'parent_relationship' => $student->parentDetail->relationship ?? null,
            'parent_email' => $student->parentDetail->email ?? null,
            'parent_contact_no' => $student->parentDetail->contact_no ?? null,
            'parent_address' => $student->parentDetail->address ?? null,
            'batch_id' => $application->batch_id,
            'application_date' => $application->created_at->toDateString(),
            'applicant_number' => $application->application_number,
            'status' => 'active'
        ]);
    }

    /**
     * Determine application status based on review
     */
    private function determineApplicationStatus($reviewType, $reviewStatus)
    {
        if ($reviewStatus === 'rejected') {
            return 'rejected';
        }

        if ($reviewStatus === 'needs_resubmission') {
            return 'document_review';
        }

        switch ($reviewType) {
            case 'document_verification':
                return $reviewStatus === 'approved' ? 'document_review' : 'pending_review';
            case 'basic_criteria_check':
                return $reviewStatus === 'approved' ? 'approved' : 'document_review';
            case 'final_approval':
                return $reviewStatus === 'approved' ? 'approved' : 'rejected';
            default:
                return 'pending_review';
        }
    }

    /**
     * Send application status notification
     */
    private function sendApplicationStatusNotification($application, $status, $message = null, $reviewType = null)
    {
        try {
            $reviewerName = Auth::user()->name ?? 'System Administrator';
            
            // Send email using the new ApplicationReviewMail
            Mail::to($application->student->email)->send(
                new \App\Mail\ApplicationReviewMail(
                    $application, 
                    $reviewType ?? 'general_update', 
                    $status, 
                    $message, 
                    $reviewerName
                )
            );
            
            // Create communication record
            ApplicationCommunication::create([
                'application_id' => $application->id,
                'type' => 'email',
                'subject' => $this->getEmailSubject($reviewType, $status, $application->application_number),
                'message' => $message ?? 'Application status has been updated.',
                'recipient_info' => [
                    'name' => $application->student->name,
                    'email' => $application->student->email
                ],
                'sent_by' => Auth::id(),
                'status' => 'sent',
                'sent_at' => now()
            ]);
            
            // Create student notification
            \App\Models\StudentNotification::create([
                'student_id' => $application->student_id,
                'application_id' => $application->id,
                'type' => $this->getNotificationType($status),
                'title' => $this->getNotificationTitle($reviewType, $status),
                'message' => $message ?? 'Your application status has been updated.',
                'is_read' => false,
                'metadata' => [
                    'review_type' => $reviewType,
                    'status' => $status,
                    'application_number' => $application->application_number
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to send application status notification: ' . $e->getMessage());
            
            // Still create communication record even if email fails
            ApplicationCommunication::create([
                'application_id' => $application->id,
                'type' => 'email',
                'subject' => $this->getEmailSubject($reviewType, $status, $application->application_number),
                'message' => $message ?? 'Application status has been updated.',
                'recipient_info' => [
                    'name' => $application->student->name,
                    'email' => $application->student->email
                ],
                'sent_by' => Auth::id(),
                'status' => 'failed',
                'sent_at' => now()
            ]);
        }
    }

    /**
     * Render template with data
     */
    private function renderTemplate($template, $data)
    {
        foreach ($data as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;
    }
    
    /**
     * Get email subject based on review type and status
     */
    private function getEmailSubject($reviewType, $status, $applicationNumber)
    {
        // Handle status-based subjects first
        switch ($status) {
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
        switch ($reviewType) {
            case 'document_verification':
                if ($status === 'approved') {
                    return "✅ Document Verification Complete - Application #{$applicationNumber}";
                } elseif ($status === 'rejected') {
                    return "❌ Document Verification Failed - Application #{$applicationNumber}";
                } else {
                    return "📋 Document Verification Update - Application #{$applicationNumber}";
                }
                
            case 'basic_criteria_check':
                if ($status === 'approved') {
                    return "✅ Basic Criteria Approved - Application #{$applicationNumber}";
                } elseif ($status === 'rejected') {
                    return "❌ Basic Criteria Not Met - Application #{$applicationNumber}";
                } else {
                    return "📋 Basic Criteria Review Update - Application #{$applicationNumber}";
                }
                
            case 'final_approval':
                if ($status === 'approved') {
                    return "🎉 Congratulations! Application Approved - Application #{$applicationNumber}";
                } elseif ($status === 'rejected') {
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
     * Get notification type based on status
     */
    private function getNotificationType($status)
    {
        switch ($status) {
            case 'approved':
            case 'selected':
            case 'interview_scheduled':
            case 'batch_assigned':
            case 'training_started':
            case 'training_completed':
            case 'deployed':
                return 'success';
            case 'rejected':
                return 'error';
            case 'needs_resubmission':
            case 'interview_completed':
                return 'warning';
            default:
                return 'info';
        }
    }
    
    /**
     * Get notification title based on review type and status
     */
    private function getNotificationTitle($reviewType, $status)
    {
        // Handle status-based titles first
        switch ($status) {
            case 'interview_scheduled':
                return "📅 Interview Scheduled";
            case 'interview_completed':
                return "📋 Interview Completed";
            case 'selected':
                return "🎉 Application Selected";
            case 'batch_assigned':
                return "🎯 Batch Assigned";
            case 'training_started':
                return "🚀 Training Started";
            case 'training_completed':
                return "🎓 Training Completed";
            case 'deployed':
                return "🎯 Service Deployed";
        }
        
        // Handle review type-based titles
        $typeText = ucwords(str_replace('_', ' ', $reviewType ?? 'application'));
        
        switch ($status) {
            case 'approved':
                return "✅ {$typeText} Approved";
            case 'rejected':
                return "❌ {$typeText} Not Approved";
            case 'needs_resubmission':
                return "⚠️ {$typeText} Needs Resubmission";
            default:
                return "📋 {$typeText} Update";
        }
    }

    /**
     * Update application status
     */
    public function updateApplicationStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending_review,document_review,approved,rejected,interview_scheduled,interview_completed,selected,batch_assigned,training_started,training_completed,deployed',
            'remarks' => 'nullable|string',
            'rejection_reason' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $application = JobPortalApplication::findOrFail($id);

        DB::transaction(function () use ($request, $application) {
            $application->update([
                'status' => $request->status,
                'remarks' => $request->remarks,
                'rejection_reason' => $request->rejection_reason,
                'updated_by' => Auth::id(),
                'updated_at' => now()
            ]);

            // Send notification
            $this->sendApplicationStatusNotification($application, $request->status, $request->remarks, $request->status);
        });

        return redirect()->back()->with('success', 'Application status updated successfully.');
    }


    /**
     * Show schedule interview form
     */
    public function showScheduleInterviewForm($id)
    {
        $application = JobPortalApplication::with(['student.profile', 'student.addresses'])->findOrFail($id);
        $interviewLocations = \App\Models\InterviewLocation::all();
        $interviewTypes = ['medical', 'fitness_swimming', 'fitness_run', 'aptitude_test', 'physical_interview'];
        
        return view('job-portal.applications.schedule-interview', compact('application', 'interviewLocations', 'interviewTypes'));
    }

    /**
     * Schedule interview
     */
    public function scheduleInterview(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'interview_date' => 'required|date|after:today',
            'interview_time' => 'required',
            'location_id' => 'required|exists:interview_locations,id',
            'interview_type' => 'required|in:medical,fitness_swimming,fitness_run,aptitude_test,physical_interview',
            'instructions' => 'nullable|string',
            'dress_code' => 'nullable|string',
            'travel_arrangements' => 'nullable|string',
            'accommodation_arrangements' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $application = JobPortalApplication::findOrFail($id);

        DB::transaction(function () use ($request, $application) {
            // Create interview schedule
            JobPortalInterviewSchedule::create([
                'application_id' => $application->id,
                'interview_date' => $request->interview_date,
                'interview_time' => $request->interview_time,
                'location_id' => $request->location_id,
                'interview_type' => $request->interview_type,
                'instructions' => $request->instructions,
                'dress_code' => $request->dress_code,
                'travel_arrangements' => $request->travel_arrangements,
                'accommodation_arrangements' => $request->accommodation_arrangements,
                'scheduled_by' => Auth::id(),
                'status' => 'scheduled'
            ]);

            // Update application status
            $application->update([
                'status' => 'interview_scheduled',
                'updated_by' => Auth::id()
            ]);

            // Send notification
            $this->sendInterviewNotification($application, $request);
        });

        return redirect()->back()->with('success', 'Interview scheduled successfully.');
    }

    /**
     * Send interview notification
     */
    private function sendInterviewNotification($application, $request)
    {
        try {
            // Get interview location details
            $location = \App\Models\InterviewLocation::find($request->location_id);
            $locationName = $location ? $location->name : 'Interview Location';
            $locationAddress = $location ? $location->getFullAddress() : '';
            
            // Format interview type for display
            $interviewTypeDisplay = ucfirst(str_replace('_', ' ', $request->interview_type));
            
            // Create interview details for email template
            $interviewDetails = [
                'type' => $interviewTypeDisplay,
                'date' => \Carbon\Carbon::parse($request->interview_date)->format('M d, Y'),
                'time' => $request->interview_time,
                'location' => $locationName . ($locationAddress ? ' - ' . $locationAddress : ''),
                'instructions' => $request->instructions
            ];

            // Send email using the new ApplicationReviewMail
            $reviewerName = Auth::user()->name ?? 'System Administrator';
            Mail::to($application->student->email)->send(
                new \App\Mail\ApplicationReviewMail(
                    $application, 
                    'interview_scheduled', 
                    'interview_scheduled', 
                    $request->instructions, 
                    $reviewerName
                )
            );
            
            // Create communication record
            ApplicationCommunication::create([
                'application_id' => $application->id,
                'type' => 'email',
                'subject' => "📅 Interview Scheduled - Application #{$application->application_number}",
                'message' => $request->instructions ?? 'Your interview has been scheduled.',
                'recipient_info' => [
                    'name' => $application->student->name,
                    'email' => $application->student->email
                ],
                'sent_by' => Auth::id(),
                'status' => 'sent',
                'sent_at' => now()
            ]);
            
            // Create student notification
            \App\Models\StudentNotification::create([
                'student_id' => $application->student_id,
                'application_id' => $application->id,
                'type' => 'success',
                'title' => '📅 Interview Scheduled',
                'message' => $request->instructions ?? 'Your interview has been scheduled.',
                'is_read' => false,
                'metadata' => [
                    'interview_type' => $request->interview_type,
                    'interview_date' => $request->interview_date,
                    'interview_time' => $request->interview_time,
                    'location' => $locationName,
                    'application_number' => $application->application_number
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error("Failed to send interview notification: " . $e->getMessage());
        }
    }

    /**
     * Record interview result
     */
    public function recordInterviewResult(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'medical_result' => 'nullable|in:passed,failed',
            'fitness_result' => 'nullable|in:passed,failed',
            'swimming_result' => 'nullable|in:passed,failed',
            'running_result' => 'nullable|in:passed,failed',
            'aptitude_result' => 'nullable|in:passed,failed',
            'aptitude_score' => 'nullable|numeric|min:0|max:100',
            'physical_interview_result' => 'nullable|in:passed,failed',
            'overall_result' => 'required|in:passed,failed',
            'comments' => 'nullable|string',
            'marks' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $application = JobPortalApplication::findOrFail($id);

        DB::transaction(function () use ($request, $application) {
            // Create interview result
            InterviewResult::create([
                'application_id' => $application->id,
                'stage' => 'overall',
                'result' => $request->overall_result === 'passed' ? 'pass' : 'fail',
                'comments' => $request->comments,
                'detailed_scores' => [
                    'medical' => $request->medical_result,
                    'fitness' => $request->fitness_result,
                    'swimming' => $request->swimming_result,
                    'running' => $request->running_result,
                    'aptitude' => $request->aptitude_result,
                    'aptitude_score' => $request->aptitude_score,
                    'physical_interview' => $request->physical_interview_result
                ],
                'evaluator_id' => Auth::id(),
                'evaluated_at' => now()
            ]);

            // Update application status
            $newStatus = $request->overall_result === 'passed' ? 'selected' : 'rejected';
            $application->update([
                'status' => $newStatus,
                'interview_completed_at' => now(),
                'updated_by' => Auth::id()
            ]);

            // Send notification
            $this->sendInterviewResultNotification($application, $request->overall_result, $request->comments);
            
            // If selected, send service offer letters
            if ($newStatus === 'selected') {
                $this->sendServiceOfferLetters($application, $request->comments);
            }
        });

        return redirect()->back()->with('success', 'Interview result recorded successfully.');
    }

    /**
     * Assign to batch
     */
    public function assignToBatch(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'batch_id' => 'required|exists:training_batches,id',
            'assignment_type' => 'required|in:automatic,manual'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $application = JobPortalApplication::findOrFail($id);
        $batch = TrainingBatch::findOrFail($request->batch_id);

        // Check batch capacity
        if ($batch->applications()->count() >= $batch->capacity) {
            return redirect()->back()->with('error', 'Batch is at full capacity.');
        }

        DB::transaction(function () use ($request, $application, $batch) {
            $application->update([
                'batch_id' => $request->batch_id,
                'status' => 'batch_assigned',
                'assigned_by' => Auth::id(),
                'assigned_at' => now(),
                'assignment_type' => $request->assignment_type
            ]);

            // Send notification
            $this->sendBatchAssignmentNotification($application, $batch);
        });

        return redirect()->back()->with('success', 'Student assigned to batch successfully.');
    }

    /**
     * Send batch assignment notification
     */
    private function sendBatchAssignmentNotification($application, $batch)
    {
        try {
            $batchData = [
                'batch_name' => $batch->batch_name,
                'batch_code' => $batch->batch_code,
                'start_date' => $batch->start_date->format('M d, Y'),
                'end_date' => $batch->end_date->format('M d, Y')
            ];

            $this->notificationService->sendBatchAssigned($application->id, $batchData);
        } catch (\Exception $e) {
            \Log::error("Failed to send batch assignment notification: " . $e->getMessage());
        }
    }


    /**
     * Send interview result notification
     */
    private function sendInterviewResultNotification($application, $result, $comments)
    {
        try {
            $reviewerName = Auth::user()->name ?? 'System Administrator';
            $status = $result === 'passed' ? 'selected' : 'rejected';
            
            // Send email using the new ApplicationReviewMail
            Mail::to($application->student->email)->send(
                new \App\Mail\ApplicationReviewMail(
                    $application, 
                    'interview_completed', 
                    $status, 
                    $comments, 
                    $reviewerName
                )
            );
            
            // Create communication record
            ApplicationCommunication::create([
                'application_id' => $application->id,
                'type' => 'email',
                'subject' => "📋 Interview Results - Application #{$application->application_number}",
                'message' => $comments ?? 'Your interview results are available.',
                'recipient_info' => [
                    'name' => $application->student->name,
                    'email' => $application->student->email
                ],
                'sent_by' => Auth::id(),
                'status' => 'sent',
                'sent_at' => now()
            ]);
            
            // Create student notification
            \App\Models\StudentNotification::create([
                'student_id' => $application->student_id,
                'application_id' => $application->id,
                'type' => $result === 'passed' ? 'success' : 'error',
                'title' => $result === 'passed' ? '🎉 Interview Passed' : '❌ Interview Not Successful',
                'message' => $comments ?? 'Your interview results are available.',
                'is_read' => false,
                'metadata' => [
                    'interview_result' => $result,
                    'application_number' => $application->application_number
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to send interview result notification: ' . $e->getMessage());
        }
    }

    /**
     * Send service offer letter and joining instructions when student is selected
     */
    private function sendServiceOfferLetters($application, $comments = null)
    {
        try {
            $reviewerName = Auth::user()->name ?? 'System Administrator';
            
            // Send Service Offer Letter
            Mail::to($application->student->email)->send(
                new \App\Mail\ApplicationReviewMail(
                    $application, 
                    'service_offer_letter', 
                    'selected', 
                    $comments, 
                    $reviewerName
                )
            );
            
            // Send Joining Instructions
            Mail::to($application->student->email)->send(
                new \App\Mail\ApplicationReviewMail(
                    $application, 
                    'joining_instructions', 
                    'selected', 
                    $comments, 
                    $reviewerName
                )
            );
            
            // Create communication records
            ApplicationCommunication::create([
                'application_id' => $application->id,
                'type' => 'email',
                'subject' => "🎉 Service Offer Letter - Application #{$application->application_number}",
                'message' => 'Service offer letter has been sent to the student.',
                'recipient_info' => [
                    'name' => $application->student->name,
                    'email' => $application->student->email
                ],
                'sent_by' => Auth::id(),
                'status' => 'sent',
                'sent_at' => now()
            ]);
            
            ApplicationCommunication::create([
                'application_id' => $application->id,
                'type' => 'email',
                'subject' => "📋 Joining Instructions - Application #{$application->application_number}",
                'message' => 'Joining instructions have been sent to the student.',
                'recipient_info' => [
                    'name' => $application->student->name,
                    'email' => $application->student->email
                ],
                'sent_by' => Auth::id(),
                'status' => 'sent',
                'sent_at' => now()
            ]);
            
            // Create student notifications
            \App\Models\StudentNotification::create([
                'student_id' => $application->student_id,
                'application_id' => $application->id,
                'type' => 'success',
                'title' => '🎉 Service Offer Letter Received',
                'message' => 'Your service offer letter has been sent. Please check your email.',
                'is_read' => false,
                'metadata' => [
                    'type' => 'service_offer_letter',
                    'application_number' => $application->application_number
                ]
            ]);
            
            \App\Models\StudentNotification::create([
                'student_id' => $application->student_id,
                'application_id' => $application->id,
                'type' => 'success',
                'title' => '📋 Joining Instructions Received',
                'message' => 'Your joining instructions have been sent. Please check your email.',
                'is_read' => false,
                'metadata' => [
                    'type' => 'joining_instructions',
                    'application_number' => $application->application_number
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to send service offer letters: ' . $e->getMessage());
        }
    }


}
