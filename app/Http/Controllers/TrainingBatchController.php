<?php

namespace App\Http\Controllers;

use App\Models\TrainingBatch;
use App\Models\JobPortalApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TrainingBatchController extends Controller
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
     * Display a listing of training batches
     */
    public function index()
    {
        $batches = TrainingBatch::withCount(['applications' => function($query) {
            $query->where('status', 'batch_assigned');
        }])->latest()->paginate(20);

        return view('job-portal.batches.index', compact('batches'));
    }

    /**
     * Show the form for creating a new batch
     */
    public function create()
    {
        return view('job-portal.batches.create');
    }

    /**
     * Store a newly created batch
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'batch_name' => 'required|string|max:255',
            'batch_code' => 'required|string|max:255|unique:training_batches',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'capacity' => 'required|integer|min:1|max:500',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $batch = TrainingBatch::create([
            'batch_name' => $request->batch_name,
            'batch_code' => $request->batch_code,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'capacity' => $request->capacity,
            'reserve_capacity' => ceil($request->capacity * 0.15), // 15% reserve
            'description' => $request->description,
            'created_by' => Auth::id(),
            'status' => 'planning'
        ]);

        return redirect()->route('job-portal.batches.index')
            ->with('success', 'Training batch created successfully.');
    }

    /**
     * Display the specified batch
     */
    public function show($id)
    {
        $batch = TrainingBatch::with(['applications.student.profile'])->findOrFail($id);
        $applications = $batch->applications()->with(['student.profile', 'student.addresses'])->paginate(20);
        
        return view('job-portal.batches.show', compact('batch', 'applications'));
    }

    /**
     * Show the form for editing the batch
     */
    public function edit($id)
    {
        $batch = TrainingBatch::findOrFail($id);
        return view('job-portal.batches.edit', compact('batch'));
    }

    /**
     * Update the specified batch
     */
    public function update(Request $request, $id)
    {
        $batch = TrainingBatch::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'batch_name' => 'required|string|max:255',
            'batch_code' => 'required|string|max:255|unique:training_batches,batch_code,' . $id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'capacity' => 'required|integer|min:1|max:500',
            'status' => 'required|in:planning,open,full,in_progress,completed,cancelled',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $batch->update([
            'batch_name' => $request->batch_name,
            'batch_code' => $request->batch_code,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'capacity' => $request->capacity,
            'reserve_capacity' => ceil($request->capacity * 0.15),
            'status' => $request->status,
            'description' => $request->description
        ]);

        return redirect()->route('job-portal.batches.index')
            ->with('success', 'Training batch updated successfully.');
    }

    /**
     * Auto-assign students to batches based on application number and capacity
     */
    public function autoAssignStudents(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'batch_id' => 'required|exists:training_batches,id',
            'assignment_type' => 'required|in:by_application_number,by_approval_date,manual_selection'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $batch = TrainingBatch::findOrFail($request->batch_id);
        $selectedStudents = $request->input('selected_students', []);
        
        // Get available students for assignment
        $availableStudents = JobPortalApplication::where('status', 'selected')
            ->whereNull('batch_id')
            ->with(['student.profile']);

        if ($request->assignment_type === 'by_application_number') {
            $availableStudents->orderBy('application_number');
        } elseif ($request->assignment_type === 'by_approval_date') {
            $availableStudents->orderBy('updated_at', 'asc');
        }

        $students = $availableStudents->get();

        // Calculate assignment numbers
        $currentEnrollment = $batch->applications()->count();
        $availableSlots = $batch->capacity - $currentEnrollment;
        $reserveSlots = $batch->reserve_capacity ?? ceil($batch->capacity * 0.15);
        $mainSlots = $availableSlots - $reserveSlots;

        $assignedCount = 0;
        $assignedStudents = [];

        foreach ($students as $student) {
            if ($assignedCount >= $mainSlots) {
                break;
            }

            // Check if student was manually selected or auto-assigned
            if ($request->assignment_type === 'manual_selection' && !in_array($student->id, $selectedStudents)) {
                continue;
            }

            // Assign student to batch
            $student->update([
                'batch_id' => $batch->id,
                'status' => 'batch_assigned',
                'assigned_by' => Auth::id(),
                'assigned_at' => now(),
                'assignment_type' => $request->assignment_type
            ]);

            $assignedStudents[] = $student;
            $assignedCount++;
        }

        // Update batch status if full
        if ($batch->applications()->count() >= $batch->capacity) {
            $batch->update(['status' => 'full']);
        } elseif ($batch->status === 'planning') {
            $batch->update(['status' => 'open']);
        }

        return redirect()->back()->with('success', "Successfully assigned {$assignedCount} students to batch.");
    }

    /**
     * Get students available for batch assignment
     */
    public function getAvailableStudents(Request $request)
    {
        $students = JobPortalApplication::where('status', 'selected')
            ->whereNull('batch_id')
            ->with(['student.profile', 'student.addresses'])
            ->orderBy('application_number')
            ->get();

        return response()->json($students);
    }

    /**
     * Remove student from batch
     */
    public function removeStudent(Request $request, $batchId, $applicationId)
    {
        $batch = TrainingBatch::findOrFail($batchId);
        $application = JobPortalApplication::findOrFail($applicationId);

        if ($application->batch_id !== $batch->id) {
            return redirect()->back()->with('error', 'Student is not assigned to this batch.');
        }

        $application->update([
            'batch_id' => null,
            'status' => 'selected',
            'assigned_by' => null,
            'assigned_at' => null,
            'assignment_type' => null
        ]);

        // Update batch status if it was full
        if ($batch->status === 'full') {
            $batch->update(['status' => 'open']);
        }

        return redirect()->back()->with('success', 'Student removed from batch successfully.');
    }

    /**
     * Batch assignment dashboard
     */
    public function assignmentDashboard()
    {
        $batches = TrainingBatch::withCount(['applications' => function($query) {
            $query->where('status', 'batch_assigned');
        }])->get();

        $availableStudents = JobPortalApplication::where('status', 'selected')
            ->whereNull('batch_id')
            ->count();

        $totalSelected = JobPortalApplication::where('status', 'selected')->count();
        $totalAssigned = JobPortalApplication::where('status', 'batch_assigned')->count();

        $stats = [
            'total_batches' => $batches->count(),
            'active_batches' => $batches->where('status', '!=', 'completed')->count(),
            'available_students' => $availableStudents,
            'total_selected' => $totalSelected,
            'total_assigned' => $totalAssigned,
            'assignment_rate' => $totalSelected > 0 ? round(($totalAssigned / $totalSelected) * 100, 1) : 0
        ];

        return view('job-portal.batches.assignment-dashboard', compact('batches', 'stats'));
    }

    /**
     * Generate batch assignment report
     */
    public function generateAssignmentReport($batchId)
    {
        $batch = TrainingBatch::with(['applications.student.profile', 'applications.student.addresses'])->findOrFail($batchId);
        
        $report = [
            'batch_info' => $batch,
            'enrollment_stats' => [
                'total_capacity' => $batch->capacity,
                'current_enrollment' => $batch->applications->count(),
                'reserve_capacity' => $batch->reserve_capacity,
                'available_slots' => $batch->capacity - $batch->applications->count(),
                'enrollment_percentage' => $batch->capacity > 0 ? round(($batch->applications->count() / $batch->capacity) * 100, 1) : 0
            ],
            'students' => $batch->applications->map(function($app) {
                return [
                    'application_number' => $app->application_number,
                    'name' => $app->student->name,
                    'email' => $app->student->email,
                    'phone' => $app->student->profile->mobile_no ?? 'N/A',
                    'nid' => $app->student->profile->nid ?? 'N/A',
                    'assigned_at' => $app->assigned_at ? $app->assigned_at->format('M d, Y H:i') : 'N/A',
                    'assignment_type' => $app->assignment_type ?? 'N/A'
                ];
            })
        ];

        return response()->json($report);
    }
}