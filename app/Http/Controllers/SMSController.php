<?php

namespace App\Http\Controllers;

use App\Models\JobPortalApplication;
use App\Models\Student;
use App\Models\TrainingBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SMSController extends Controller
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
     * SMS Dashboard
     */
    public function index()
    {
        $stats = $this->getDashboardStats();
        $recentActivities = $this->getRecentActivities();

        return view('sms.dashboard', compact('stats', 'recentActivities'));
    }

    /**
     * Student Management
     */
    public function students(Request $request)
    {
        $query = Student::whereHas('jobPortalApplication', function($q) {
            $q->whereIn('status', ['selected', 'batch_assigned', 'training_started', 'training_completed', 'deployed']);
        })->with(['profile', 'jobPortalApplication.batch']);

        // Apply filters
        if ($request->filled('batch_id')) {
            $query->whereHas('jobPortalApplication', function($q) use ($request) {
                $q->where('batch_id', $request->batch_id);
            });
        }

        if ($request->filled('status')) {
            $query->whereHas('jobPortalApplication', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('profile', function($profileQuery) use ($search) {
                      $profileQuery->where('first_name', 'like', "%{$search}%")
                                   ->orWhere('last_name', 'like', "%{$search}%")
                                   ->orWhere('nid', 'like', "%{$search}%");
                  });
            });
        }

        $students = $query->paginate(20);
        $batches = TrainingBatch::where('status', 'active')->get();
        $statuses = ['selected', 'batch_assigned', 'training_started', 'training_completed', 'deployed'];

        return view('sms.students.index', compact('students', 'batches', 'statuses'));
    }

    /**
     * Show individual student details
     */
    public function showStudent($id)
    {
        $student = Student::with([
            'profile',
            'jobPortalApplication.batch',
            'addresses',
            'parentDetail',
            'documents'
        ])->findOrFail($id);

        // Check if student has SMS record
        $smsStudent = \App\Models\SMSStudent::where('job_portal_application_id', $student->jobPortalApplication->id)->first();

        return view('sms.students.show', compact('student', 'smsStudent'));
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats()
    {
        return [
            'total_students' => Student::whereHas('jobPortalApplication', function($q) {
                $q->whereIn('status', ['selected', 'batch_assigned', 'training_started', 'training_completed', 'deployed']);
            })->count(),
            'active_batches' => TrainingBatch::where('status', 'active')->count(),
            'students_in_training' => Student::whereHas('jobPortalApplication', function($q) {
                $q->where('status', 'training_started');
            })->count(),
            'graduated_students' => Student::whereHas('jobPortalApplication', function($q) {
                $q->where('status', 'training_completed');
            })->count(),
            'deployed_students' => Student::whereHas('jobPortalApplication', function($q) {
                $q->where('status', 'deployed');
            })->count(),
            'under_18_students' => Student::whereHas('jobPortalApplication', function($q) {
                $q->whereIn('status', ['selected', 'batch_assigned', 'training_started', 'training_completed', 'deployed']);
            })->where('is_under_age_18', true)->count()
        ];
    }

    /**
     * Leave Management
     */
    public function leaves(Request $request)
    {
        $query = \App\Models\LeaveRequest::with(['smsStudent']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('smsStudent', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        $leaveRequests = $query->latest()->paginate(20);
        $statuses = ['pending', 'approved', 'rejected'];
        $leaveTypes = ['sick', 'personal', 'emergency', 'official', 'medical'];

        return view('sms.leaves.index', compact('leaveRequests', 'statuses', 'leaveTypes'));
    }

    /**
     * Process leave request
     */
    public function processLeave(Request $request, $id)
    {
        $leaveRequest = \App\Models\LeaveRequest::findOrFail($id);

        $validator = \Validator::make($request->all(), [
            'status' => 'required|in:approved,rejected',
            'admin_remarks' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $leaveRequest->update([
            'status' => $request->status,
            'admin_remarks' => $request->admin_remarks,
            'processed_by' => Auth::id(),
            'processed_at' => now()
        ]);

        return redirect()->back()->with('success', 'Leave request processed successfully.');
    }

    /**
     * Attendance Management
     */
    public function attendance(Request $request)
    {
        $query = \App\Models\AttendanceRecord::with(['smsStudent']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->where('attendance_date', $request->date);
        }

        if ($request->filled('batch_id')) {
            $query->whereHas('smsStudent', function($q) use ($request) {
                $q->where('batch_id', $request->batch_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('smsStudent', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        $attendanceRecords = $query->latest('attendance_date')->paginate(20);
        $statuses = ['present', 'absent', 'late', 'leave', 'medical_excuse', 'official_leave'];
        $batches = \App\Models\TrainingBatch::where('status', 'active')->get();

        return view('sms.attendance.index', compact('attendanceRecords', 'statuses', 'batches'));
    }

    /**
     * Mark attendance
     */
    public function markAttendance(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'sms_student_id' => 'required|exists:sms_students,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,late,leave,medical_excuse,official_leave',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'reasons' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        \App\Models\AttendanceRecord::updateOrCreate(
            [
                'sms_student_id' => $request->sms_student_id,
                'attendance_date' => $request->attendance_date
            ],
            [
                'status' => $request->status,
                'check_in_time' => $request->check_in_time,
                'check_out_time' => $request->check_out_time,
                'reasons' => $request->reasons,
                'marked_by' => Auth::id()
            ]
        );

        return redirect()->back()->with('success', 'Attendance marked successfully.');
    }

    /**
     * Performance Management
     */
    public function performance(Request $request)
    {
        $query = \App\Models\PerformanceRecord::with(['smsStudent']);

        // Apply filters
        if ($request->filled('performance_type')) {
            $query->where('performance_type', $request->performance_type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('smsStudent', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        $performanceRecords = $query->latest()->paginate(20);
        $performanceTypes = ['skills', 'counselling', 'pay_steepens', 'statements', 'performance_indicator', 'observation'];

        return view('sms.performance.index', compact('performanceRecords', 'performanceTypes'));
    }

    /**
     * Add performance record
     */
    public function addPerformance(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'sms_student_id' => 'required|exists:sms_students,id',
            'performance_type' => 'required|string',
            'title' => 'required|string',
            'description' => 'required|string',
            'comments' => 'nullable|string',
            'score' => 'nullable|numeric',
            'record_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        \App\Models\PerformanceRecord::create([
            'sms_student_id' => $request->sms_student_id,
            'performance_type' => $request->performance_type,
            'title' => $request->title,
            'description' => $request->description,
            'comments' => $request->comments,
            'score' => $request->score,
            'record_date' => $request->record_date,
            'recorded_by' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Performance record added successfully.');
    }

    /**
     * Medical Information
     */
    public function medical(Request $request)
    {
        $query = \App\Models\MedicalRecord::with(['smsStudent']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('smsStudent', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        $medicalRecords = $query->latest()->paginate(20);

        return view('sms.medical.index', compact('medicalRecords'));
    }

    /**
     * Add medical record
     */
    public function addMedicalRecord(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'sms_student_id' => 'required|exists:sms_students,id',
            'current_medical_status' => 'required|string',
            'medical_excuses' => 'nullable|string',
            'remarks' => 'nullable|string',
            'record_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $smsStudent = \App\Models\SMSStudent::findOrFail($request->sms_student_id);

        \App\Models\MedicalRecord::create([
            'sms_student_id' => $request->sms_student_id,
            'student_id' => $smsStudent->student_id,
            'rank' => $smsStudent->rank,
            'name' => $smsStudent->full_name,
            'current_medical_status' => $request->current_medical_status,
            'medical_excuses' => $request->medical_excuses,
            'remarks' => $request->remarks,
            'record_date' => $request->record_date,
            'recorded_by' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Medical record added successfully.');
    }

    /**
     * Graduation Management
     */
    public function graduation(Request $request)
    {
        $query = \App\Models\Graduation::with(['smsStudent']);

        // Apply filters
        if ($request->filled('posting_status')) {
            $query->where('posting_status', $request->posting_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('smsStudent', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        $graduations = $query->latest()->paginate(20);
        $postingStatuses = ['pending', 'posted_to_police', 'posted_to_mndf', 'posted_to_other_units'];

        return view('sms.graduation.index', compact('graduations', 'postingStatuses'));
    }

    /**
     * Process graduation
     */
    public function processGraduation(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'sms_student_id' => 'required|exists:sms_students,id',
            'graduation_date' => 'required|date',
            'graduation_remarks' => 'nullable|string',
            'is_under_18' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $smsStudent = \App\Models\SMSStudent::findOrFail($request->sms_student_id);

        // Generate certificate number
        $certificateNumber = 'NS-CERT-' . date('Y') . '-' . str_pad(\App\Models\Graduation::count() + 1, 4, '0', STR_PAD_LEFT);

        \App\Models\Graduation::create([
            'sms_student_id' => $request->sms_student_id,
            'graduation_date' => $request->graduation_date,
            'certificate_number' => $certificateNumber,
            'graduation_remarks' => $request->graduation_remarks,
            'is_under_18' => $request->boolean('is_under_18'),
            'posting_status' => $request->boolean('is_under_18') ? 'posted_to_other_units' : 'pending',
            'graduated_by' => Auth::id()
        ]);

        // Update student status
        $smsStudent->update(['status' => 'graduated']);

        return redirect()->back()->with('success', 'Student graduated successfully.');
    }

    /**
     * Postings Management
     */
    public function postings(Request $request)
    {
        $query = \App\Models\Posting::with(['smsStudent']);

        // Apply filters
        if ($request->filled('posting_type')) {
            $query->where('posting_type', $request->posting_type);
        }

        if ($request->filled('unit_name')) {
            $query->where('unit_name', 'like', "%{$request->unit_name}%");
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('smsStudent', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        $postings = $query->latest()->paginate(20);
        $postingTypes = ['police', 'mndf', 'other_units'];
        $unitNames = ['Police', 'MNDF', 'CDSS', 'EME', 'ME'];

        return view('sms.postings.index', compact('postings', 'postingTypes', 'unitNames'));
    }

    /**
     * Create posting
     */
    public function createPosting(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'sms_student_id' => 'required|exists:sms_students,id',
            'posting_type' => 'required|in:police,mndf,other_units',
            'unit_name' => 'required|string',
            'position' => 'nullable|string',
            'posting_date' => 'required|date',
            'posting_remarks' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        \App\Models\Posting::create([
            'sms_student_id' => $request->sms_student_id,
            'posting_type' => $request->posting_type,
            'unit_name' => $request->unit_name,
            'position' => $request->position,
            'posting_date' => $request->posting_date,
            'posting_remarks' => $request->posting_remarks,
            'posted_by' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Posting created successfully.');
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities()
    {
        return [
            ['type' => 'graduation', 'message' => 'Student John Doe graduated from Batch 2025-01', 'time' => '2 hours ago'],
            ['type' => 'leave', 'message' => 'Leave request approved for Jane Smith', 'time' => '4 hours ago'],
            ['type' => 'attendance', 'message' => 'Attendance marked for Batch 2025-01', 'time' => '6 hours ago'],
            ['type' => 'posting', 'message' => 'Student Mike Johnson posted to MNDF', 'time' => '1 day ago'],
        ];
    }
}
