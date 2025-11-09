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
                return redirect()->route('job-portal.dashboard')->with('error', __('Permission Denied. Only super admin can access this area.'));
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

        // Get recent activities
        $recentActivities = $this->getRecentActivities();

        return view('job-portal.dashboard', compact('stats', 'recentApplications', 'recentActivities'));
    }

    /**
     * Vetting Management
     */
    public function vetting(Request $request)
    {
        $query = \App\Models\VettingRecord::with(['application.student.profile']);

        // Apply filters
        if ($request->filled('vetting_type')) {
            $query->where('vetting_type', $request->vetting_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('application.student.profile', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('nid', 'like', "%{$search}%");
            });
        }

        $vettingRecords = $query->latest()->paginate(20);
        $vettingTypes = ['police', 'dis', 'both'];
        $statuses = ['pending', 'in_progress', 'completed', 'failed'];

        return view('job-portal.vetting.index', compact('vettingRecords', 'vettingTypes', 'statuses'));
    }

    /**
     * Create vetting record
     */
    public function createVetting(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'application_id' => 'required|exists:job_portal_applications,id',
            'vetting_type' => 'required|in:police,dis,both',
            'police_remarks' => 'nullable|string',
            'dis_remarks' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        \App\Models\VettingRecord::create([
            'application_id' => $request->application_id,
            'vetting_type' => $request->vetting_type,
            'status' => 'pending',
            'police_remarks' => $request->police_remarks,
            'dis_remarks' => $request->dis_remarks,
            'processed_by' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Vetting record created successfully.');
    }

    /**
     * Update vetting status
     */
    public function updateVetting(Request $request, $id)
    {
        $vetting = \App\Models\VettingRecord::findOrFail($id);

        $validator = \Validator::make($request->all(), [
            'police_cleared' => 'boolean',
            'dis_cleared' => 'boolean',
            'police_remarks' => 'nullable|string',
            'dis_remarks' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $vetting->update([
            'police_cleared' => $request->boolean('police_cleared'),
            'dis_cleared' => $request->boolean('dis_cleared'),
            'police_remarks' => $request->police_remarks,
            'dis_remarks' => $request->dis_remarks,
            'police_cleared_date' => $request->boolean('police_cleared') ? now()->toDateString() : null,
            'dis_cleared_date' => $request->boolean('dis_cleared') ? now()->toDateString() : null,
            'status' => $vetting->isCompleted() ? 'completed' : 'in_progress',
            'processed_by' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Vetting status updated successfully.');
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
        try {
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

            // Get data for modals
            $interviewLocations = \App\Models\InterviewLocation::all();
            $trainingBatches = \App\Models\TrainingBatch::where('status', 'active')->get();

            return view('job-portal.applications.show', compact('application', 'reviewTypes', 'interviewStages', 'interviewLocations', 'trainingBatches'));
        } catch (\Exception $e) {
            \Log::error('Error in showApplication: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('job-portal.dashboard')->with('error', 'Error loading application: ' . $e->getMessage());
        }
    }

    /**
     * Download student document (admin access)
     */
    public function downloadDocument($documentId)
    {
        try {
            $document = \App\Models\StudentDocument::findOrFail($documentId);

            // Check if file exists
            $filePath = storage_path('app/public/' . $document->file_path);
            if (!file_exists($filePath)) {
                return redirect()->back()->with('error', 'Document file not found.');
            }

            return response()->download($filePath, $document->original_name);
        } catch (\Exception $e) {
            \Log::error('Error downloading document: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error downloading document: ' . $e->getMessage());
        }
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
     * Get recent activities for dashboard
     */
    private function getRecentActivities()
    {
        $activities = collect();

        // Recent applications
        $recentApplications = JobPortalApplication::with(['student.profile'])
            ->latest()
            ->limit(5)
            ->get();

        foreach ($recentApplications as $application) {
            $studentName = $application->student->profile
                ? $application->student->profile->first_name . ' ' . $application->student->profile->last_name
                : $application->student->name;

            $activities->push([
                'type' => 'application',
                'icon' => 'bi-person-plus',
                'icon_color' => 'var(--primary-blue)',
                'title' => "New application from {$studentName}",
                'subtitle' => "Application #{$application->application_number}",
                'time' => $application->created_at,
                'status' => $application->status,
            ]);
        }

        // Recent status changes
        $recentStatusChanges = JobPortalApplication::with(['student.profile'])
            ->whereNotNull('updated_at')
            ->where('updated_at', '>', now()->subDays(7))
            ->whereColumn('created_at', '!=', 'updated_at')
            ->latest('updated_at')
            ->limit(5)
            ->get();

        foreach ($recentStatusChanges as $application) {
            $studentName = $application->student->profile
                ? $application->student->profile->first_name . ' ' . $application->student->profile->last_name
                : $application->student->name;

            $statusColors = [
                'approved' => 'var(--success-green)',
                'selected' => 'var(--success-green)',
                'interview_scheduled' => 'var(--warning-yellow)',
                'batch_assigned' => 'var(--info-blue)',
                'rejected' => 'var(--danger-red)',
            ];

            $statusIcons = [
                'approved' => 'bi-check-circle',
                'selected' => 'bi-check-circle',
                'interview_scheduled' => 'bi-calendar',
                'batch_assigned' => 'bi-people',
                'rejected' => 'bi-x-circle',
            ];

            $activities->push([
                'type' => 'status_change',
                'icon' => $statusIcons[$application->status] ?? 'bi-info-circle',
                'icon_color' => $statusColors[$application->status] ?? 'var(--primary-blue)',
                'title' => "Application status updated to " . ucfirst(str_replace('_', ' ', $application->status)),
                'subtitle' => "{$studentName} - #{$application->application_number}",
                'time' => $application->updated_at,
                'status' => $application->status,
            ]);
        }

        // Recent batches
        $recentBatches = TrainingBatch::latest()
            ->limit(3)
            ->get();

        foreach ($recentBatches as $batch) {
            $activities->push([
                'type' => 'batch',
                'icon' => 'bi-collection',
                'icon_color' => 'var(--primary-blue)',
                'title' => "New batch created: {$batch->batch_name}",
                'subtitle' => "Capacity: {$batch->capacity} students",
                'time' => $batch->created_at,
                'status' => $batch->status,
            ]);
        }

        // Recent interview schedules
        $recentInterviews = JobPortalInterviewSchedule::with(['application.student.profile'])
            ->latest()
            ->limit(3)
            ->get();

        foreach ($recentInterviews as $interview) {
            $studentName = $interview->application->student->profile
                ? $interview->application->student->profile->first_name . ' ' . $interview->application->student->profile->last_name
                : $interview->application->student->name;

            $activities->push([
                'type' => 'interview',
                'icon' => 'bi-calendar-check',
                'icon_color' => 'var(--warning-yellow)',
                'title' => "Interview scheduled for {$studentName}",
                'subtitle' => $interview->interview_date->format('M d, Y') . ' at ' . $interview->interview_time->format('h:i A'),
                'time' => $interview->created_at,
                'status' => $interview->status,
            ]);
        }

        // Sort by time and limit to 10 most recent
        return $activities->sortByDesc('time')->take(10)->values();
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

    /**
     * Display a listing of training batches
     */
    public function batches()
    {
        $batches = TrainingBatch::withCount('applications')->latest()->paginate(20);
        //dd($batches);
        return view('job-portal.batches.index', compact('batches'));
    }

    /**
     * Show the form for creating a new training batch
     */
    public function createBatch()
    {
        return view('job-portal.batches.create');
    }

    /**
     * Store a newly created training batch
     */
    public function storeBatch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'batch_code' => 'required|string|max:50|unique:training_batches,batch_code',
            'batch_name' => 'required|string|max:255',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'capacity' => 'required|integer|min:1|max:500',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $batch = TrainingBatch::create([
            'batch_code' => $request->batch_code,
            'batch_name' => $request->batch_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'capacity' => $request->capacity,
            'description' => $request->description,
            'status' => 'active',
        ]);

        return redirect()->route('job-portal.batches.index')
            ->with('success', 'Training batch created successfully.');
    }

    /**
     * Display the specified training batch
     */
    public function showBatch($id)
    {
        $batch = TrainingBatch::with(['applications.student'])->findOrFail($id);
        return view('job-portal.batches.show', compact('batch'));
    }

    /**
     * Show the form for editing the specified training batch
     */
    public function editBatch($id)
    {
        $batch = TrainingBatch::findOrFail($id);
        return view('job-portal.batches.edit', compact('batch'));
    }

    /**
     * Update the specified training batch
     */
    public function updateBatch(Request $request, $id)
    {
        $batch = TrainingBatch::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'batch_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'capacity' => 'required|integer|min:1|max:500',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:planning,active,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $batch->update([
            'batch_name' => $request->batch_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'capacity' => $request->capacity,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('job-portal.batches.index')
            ->with('success', 'Training batch updated successfully.');
    }

    /**
     * Remove the specified training batch
     */
    public function deleteBatch($id)
    {
        $batch = TrainingBatch::findOrFail($id);

        // Check if batch has applications
        if ($batch->applications()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete batch with assigned applications.');
        }

        $batch->delete();

        return redirect()->route('job-portal.batches.index')
            ->with('success', 'Training batch deleted successfully.');
    }

    /**
     * Display batch assignment dashboard
     */
    public function batchAssignmentDashboard()
    {
        $batches = TrainingBatch::withCount('applications')
            ->where('status', '!=', 'completed')
            ->latest()
            ->get();

        $availableStudents = \App\Models\JobPortalApplication::with('student')
            ->where('status', 'approved')
            ->whereNull('batch_id')
            ->get();

        // Calculate stats for the dashboard
        $totalBatches = TrainingBatch::count();
        $activeBatches = TrainingBatch::where('status', '!=', 'completed')->count();
        $availableStudentsCount = $availableStudents->count();
        $totalSelected = \App\Models\JobPortalApplication::where('status', 'approved')->count();
        $totalAssigned = \App\Models\JobPortalApplication::whereNotNull('batch_id')->count();
        $assignmentRate = $totalSelected > 0 ? round(($totalAssigned / $totalSelected) * 100, 1) : 0;

        $stats = [
            'total_batches' => $totalBatches,
            'active_batches' => $activeBatches,
            'available_students' => $availableStudentsCount,
            'assignment_rate' => $assignmentRate,
            'total_selected' => $totalSelected,
            'total_assigned' => $totalAssigned,
        ];

        return view('job-portal.batches.assignment-dashboard', compact('batches', 'availableStudents', 'stats'));
    }

    /**
     * Get available students for batch assignment
     */
    public function getAvailableStudents(Request $request)
    {
        $batchId = $request->get('batch_id');

        $students = \App\Models\JobPortalApplication::with(['student.profile', 'batch'])
            ->whereIn('status', ['approved', 'selected', 'interview_scheduled', 'batch_assigned'])
            ->get()
            ->map(function ($application) use ($batchId) {
                $student = $application->student;
                $profile = $student->profile;

                return [
                    'id' => $application->id,
                    'name' => $profile ? $profile->first_name . ' ' . $profile->last_name : $student->name,
                    'application_number' => $application->application_number,
                    'email' => $student->email,
                    'phone' => $profile ? $profile->mobile_no : null,
                    'status' => $application->status,
                    'batch_id' => $application->batch_id,
                    'batch_name' => $application->batch ? $application->batch->batch_name : null,
                    'is_assigned' => !is_null($application->batch_id),
                    'is_assigned_to_current_batch' => $application->batch_id == $batchId,
                ];
            });

        return response()->json($students);
    }

    /**
     * Auto assign students to batch
     */
    public function autoAssignToBatch(Request $request, $id)
    {
        $batch = TrainingBatch::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'assignment_type' => 'required|in:by_application_number,by_approval_date,manual_selection',
            'student_ids' => 'required_if:assignment_type,manual_selection|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $query = \App\Models\JobPortalApplication::with('student')
            ->where('status', 'approved')
            ->whereNull('batch_id');

        switch ($request->assignment_type) {
            case 'by_application_number':
                $query->orderBy('application_number');
                break;
            case 'by_approval_date':
                $query->orderBy('reviewed_at');
                break;
            case 'manual_selection':
                $query->whereIn('id', $request->student_ids);
                break;
        }

        $students = $query->get();
        $assignedCount = 0;
        $reserveCount = 0;
        $mainCapacity = (int) ($batch->capacity * 0.85); // 85% for main slots
        $reserveCapacity = $batch->capacity - $mainCapacity; // 15% for reserves

        foreach ($students as $application) {
            if ($assignedCount < $mainCapacity) {
                // Assign to main slots
                $application->update([
                    'batch_id' => $batch->id,
                    'batch_position' => $assignedCount + 1,
                    'is_reserve' => false
                ]);
                $assignedCount++;
            } elseif ($reserveCount < $reserveCapacity) {
                // Assign to reserve slots
                $application->update([
                    'batch_id' => $batch->id,
                    'batch_position' => $assignedCount + $reserveCount + 1,
                    'is_reserve' => true
                ]);
                $reserveCount++;
            } else {
                break; // Batch is full
            }
        }

        return redirect()->route('job-portal.batches.assignment.dashboard')
            ->with('success', "Successfully assigned {$assignedCount} students to main slots and {$reserveCount} to reserve slots in batch {$batch->batch_name}.");
    }
}
