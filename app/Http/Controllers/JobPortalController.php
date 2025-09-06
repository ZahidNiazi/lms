<?php

namespace App\Http\Controllers;

use App\Models\JobPortalApplication;
use App\Models\ApplicationReview;
use App\Models\TrainingBatch;
use App\Models\JobPortalInterviewSchedule;
use App\Models\InterviewResult;
use App\Models\NotificationTemplate;
use App\Models\ApplicationCommunication;
use App\Models\PoliceDisVetting;
use App\Models\InterviewLocation;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class JobPortalController extends Controller
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
     * Display the main job portal dashboard
     */
    public function index()
    {
        $stats = $this->getDashboardStats();
        $recentApplications = JobPortalApplication::with(['student.profile', 'student.addresses'])
            ->latest()
            ->limit(10)
            ->get();
        
        return view('job-portal.dashboard', compact('stats', 'recentApplications'));
    }

    /**
     * Display applications list with filtering
     */
    public function applications(Request $request)
    {
        $query = JobPortalApplication::with(['student.profile', 'student.addresses', 'reviews']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('application_number', 'like', "%{$search}%");
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $applications = $query->paginate(20);
        $statuses = $this->getApplicationStatuses();
        
        return view('job-portal.applications.index', compact('applications', 'statuses'));
    }

    /**
     * Show application details
     */
    public function showApplication($id)
    {
        $application = JobPortalApplication::with([
            'student.profile',
            'student.addresses',
            'student.documents',
            'student.parentDetail',
            'reviews.reviewer',
            'interviewSchedules',
            'interviewResults',
            'communications',
            'vetting'
        ])->findOrFail($id);

        $reviewTypes = ['document_verification', 'basic_criteria_check', 'final_approval'];
        $interviewStages = ['medical', 'fitness_swimming', 'fitness_run', 'aptitude_test', 'physical_interview'];
        
        return view('job-portal.applications.show', compact('application', 'reviewTypes', 'interviewStages'));
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats()
    {
        return [
            'total_applications' => JobPortalApplication::count(),
            'pending_review' => JobPortalApplication::where('status', 'pending_review')->count(),
            'document_review' => JobPortalApplication::where('status', 'document_review')->count(),
            'approved' => JobPortalApplication::where('status', 'approved')->count(),
            'rejected' => JobPortalApplication::where('status', 'rejected')->count(),
            'interview_scheduled' => JobPortalApplication::where('status', 'interview_scheduled')->count(),
            'selected' => JobPortalApplication::where('status', 'selected')->count(),
            'batch_assigned' => JobPortalApplication::where('status', 'batch_assigned')->count(),
            'needs_resubmission' => JobPortalApplication::requiresResubmission()->count(),
            'this_month' => JobPortalApplication::whereMonth('created_at', now()->month)->count(),
            'last_month' => JobPortalApplication::whereMonth('created_at', now()->subMonth()->month)->count()
        ];
    }

    /**
     * Get application statuses for dropdown
     */
    private function getApplicationStatuses()
    {
        return [
            'pending_review' => 'Pending Review',
            'document_review' => 'Document Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'interview_scheduled' => 'Interview Scheduled',
            'interview_completed' => 'Interview Completed',
            'selected' => 'Selected',
            'batch_assigned' => 'Batch Assigned',
            'training_started' => 'Training Started',
            'training_completed' => 'Training Completed',
            'deployed' => 'Deployed'
        ];
    }
}