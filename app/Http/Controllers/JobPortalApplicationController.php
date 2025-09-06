<?php

namespace App\Http\Controllers;

use App\Models\JobPortalApplication;
use App\Models\ApplicationReview;
use App\Models\TrainingBatch;
use App\Models\JobPortalInterviewSchedule;
use App\Models\InterviewResult;
use App\Models\NotificationTemplate;
use App\Models\ApplicationCommunication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JobPortalApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->type !== 'super admin') {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
            return $next($request);
        });
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
            $this->sendApplicationStatusNotification($application, $newStatus, $request->comments);
        });

        return redirect()->back()->with('success', 'Application reviewed successfully.');
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
    private function sendApplicationStatusNotification($application, $status, $message = null)
    {
        $template = NotificationTemplate::byTypeAndTrigger('email', 'application_status_update')->first();
        
        if ($template) {
            $data = [
                'student_name' => $application->student->name,
                'application_number' => $application->application_number,
                'status' => ucfirst(str_replace('_', ' ', $status)),
                'message' => $message ?? '',
                'date' => now()->format('M d, Y')
            ];

            $rendered = $template->renderTemplate($data);
            
            ApplicationCommunication::create([
                'application_id' => $application->id,
                'type' => 'email',
                'subject' => $rendered['subject'],
                'message' => $rendered['body'],
                'recipient_info' => [
                    'name' => $application->student->name,
                    'email' => $application->student->email
                ],
                'sent_by' => Auth::id()
            ]);
        }
    }
}
