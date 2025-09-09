<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\SMS\Student;
use App\Models\SMS\TrainingBatch;
use App\Models\SMS\Leave;
use App\Models\SMS\Attendance;
use App\Models\SMS\Performance;
use App\Models\SMS\MedicalRecord;
use App\Models\SMS\Award;
use App\Models\SMS\Warning;
use App\Models\SMS\Assessment;
use App\Models\SMS\Graduation;
use App\Models\SMS\Posting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SMSController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display SMS dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_students' => Student::count(),
            'active_students' => Student::where('is_active', true)->count(),
            'total_batches' => TrainingBatch::count(),
            'active_batches' => TrainingBatch::where('status', 'active')->count(),
            'pending_leaves' => Leave::where('status', 'pending')->count(),
            'today_attendance' => Attendance::where('date', today())->count(),
            'medical_records' => MedicalRecord::count(),
            'recent_graduations' => Graduation::where('graduation_date', '>=', now()->subDays(30))->count(),
        ];

        $recentStudents = Student::with('batch')
            ->latest()
            ->limit(10)
            ->get();

        $recentLeaves = Leave::with(['student', 'leaveType'])
            ->latest()
            ->limit(5)
            ->get();

        return view('sms.dashboard', compact('stats', 'recentStudents', 'recentLeaves'));
    }

    /**
     * Display students list
     */
    public function students(Request $request)
    {
        $query = Student::with('batch');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        if ($request->filled('company')) {
            $query->where('company', $request->company);
        }

        if ($request->filled('platoon')) {
            $query->where('platoon', $request->platoon);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $students = $query->paginate(20);
        $batches = TrainingBatch::active()->get();
        $companies = Student::distinct()->pluck('company')->filter();
        $platoons = Student::distinct()->pluck('platoon')->filter();

        return view('sms.students.index', compact('students', 'batches', 'companies', 'platoons'));
    }

    /**
     * Show student details
     */
    public function showStudent($id)
    {
        $student = Student::with([
            'batch',
            'leaves.leaveType',
            'attendances',
            'performances.performanceField',
            'medicalRecords',
            'awards',
            'warnings',
            'assessments.subject',
            'documents'
        ])->findOrFail($id);

        return view('sms.students.show', compact('student'));
    }

    /**
     * Show create student form
     */
    public function createStudent()
    {
        $batches = TrainingBatch::active()->get();
        return view('sms.students.create', compact('batches'));
    }

    /**
     * Store new student
     */
    public function storeStudent(Request $request)
    {
        $request->validate([
            'student_id' => 'required|unique:sms_students,student_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:sms_students,email',
            'national_id' => 'required|unique:sms_students,national_id',
            'contact_no' => 'required|string',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|date',
            'batch_id' => 'nullable|exists:sms_training_batches,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photo->storeAs('public/student_photos', $photoName);
            $data['photo'] = 'student_photos/' . $photoName;
        }

        // Calculate age
        if ($request->date_of_birth) {
            $data['age'] = \Carbon\Carbon::parse($request->date_of_birth)->age;
        }

        $student = Student::create($data);

        return redirect()->route('sms.students.show', $student->id)
            ->with('success', 'Student created successfully.');
    }

    /**
     * Show edit student form
     */
    public function editStudent($id)
    {
        $student = Student::findOrFail($id);
        $batches = TrainingBatch::active()->get();
        return view('sms.students.edit', compact('student', 'batches'));
    }

    /**
     * Update student
     */
    public function updateStudent(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'student_id' => 'required|unique:sms_students,student_id,' . $id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:sms_students,email,' . $id,
            'national_id' => 'required|unique:sms_students,national_id,' . $id,
            'contact_no' => 'required|string',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|date',
            'batch_id' => 'nullable|exists:sms_training_batches,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($student->photo && \Storage::exists('public/' . $student->photo)) {
                \Storage::delete('public/' . $student->photo);
            }
            
            $photo = $request->file('photo');
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photo->storeAs('public/student_photos', $photoName);
            $data['photo'] = 'student_photos/' . $photoName;
        }

        // Calculate age
        if ($request->date_of_birth) {
            $data['age'] = \Carbon\Carbon::parse($request->date_of_birth)->age;
        }

        $student->update($data);

        return redirect()->route('sms.students.show', $student->id)
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Delete student
     */
    public function deleteStudent($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('sms.students.index')
            ->with('success', 'Student deleted successfully.');
    }

    /**
     * Display leaves management
     */
    public function leaves(Request $request)
    {
        $query = Leave::with(['student', 'leaveType']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $leaves = $query->latest()->paginate(20);
        $students = Student::active()->get();

        return view('sms.leaves.index', compact('leaves', 'students'));
    }

    /**
     * Display attendance management
     */
    public function attendance(Request $request)
    {
        $query = Attendance::with('student');

        if ($request->filled('date')) {
            $query->where('date', $request->date);
        } else {
            $query->where('date', today());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('batch_id')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('batch_id', $request->batch_id);
            });
        }

        $attendances = $query->paginate(50);
        $batches = TrainingBatch::active()->get();

        return view('sms.attendance.index', compact('attendances', 'batches'));
    }

    /**
     * Display performance management
     */
    public function performance(Request $request)
    {
        $query = Performance::with(['student', 'performanceField']);

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $performances = $query->latest()->paginate(20);
        $students = Student::active()->get();

        return view('sms.performance.index', compact('performances', 'students'));
    }

    /**
     * Display medical information
     */
    public function medical(Request $request)
    {
        $query = MedicalRecord::with('student');

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $medicalRecords = $query->latest()->paginate(20);
        $students = Student::active()->get();

        return view('sms.medical.index', compact('medicalRecords', 'students'));
    }

    /**
     * Display graduation management
     */
    public function graduation(Request $request)
    {
        $query = Graduation::with('student');

        if ($request->filled('status')) {
            $query->where('graduation_status', $request->status);
        }

        $graduations = $query->latest()->paginate(20);

        return view('sms.graduation.index', compact('graduations'));
    }

    /**
     * Display postings management
     */
    public function postings(Request $request)
    {
        $query = Posting::with('student');

        if ($request->filled('posting_type')) {
            $query->where('posting_type', $request->posting_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $postings = $query->latest()->paginate(20);

        return view('sms.postings.index', compact('postings'));
    }
}
