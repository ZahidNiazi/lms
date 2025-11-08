<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\SMS\Student;
use App\Models\Student as MainStudent;
use App\Models\SMS\TrainingBatch;
use App\Models\TrainingBatch as MainTrainingBatch;
use App\Models\SMS\Leave;
use App\Models\SMS\Attendance;
use App\Models\SMS\Performance;
use App\Models\SMS\MedicalRecord;
use App\Models\SMS\SmsAcademic;
use App\Models\SMS\SmsObservation;
use App\Models\SMS\Award;
use App\Models\SMS\Warning;
use App\Models\SMS\Assessment;
use App\Models\SMS\Graduation;
use App\Models\SMS\Posting;
use App\Models\Atoll;
use App\Models\Island;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Utility;

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
        $query = Student::with('batch','company','platoon');
        //$query = MainStudent::query();
        // Apply filters
        //dd($query);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                //$q->where('name', 'like', "%{$search}%")
                  $q->Where('first_name', 'like', "%{$search}%")
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
        $companies = \App\Models\Company::pluck('name')->filter();
        $platoons = \App\Models\Platoon::pluck('name')->filter();
        $atolls = DB::table('atolls')->get();

        return view('sms.students.index', compact('students', 'batches', 'companies', 'platoons', 'atolls'));
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
            'documents',
            'AcademiclRecords',
            'Observation',
            'permanentAtoll', 'permanentIsland',
            'presentAtoll', 'presentIsland',
            'parentAtoll', 'parentIsland',
            'company','platoon'
        ])->findOrFail($id);
//dd($student);
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
            $path = $request->file('photo')->store('student-profiles', 'public');
            $data['photo'] = $path;
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
        
        $student = Student::with('medicalRecords','AcademiclRecords','Observation')->findOrFail($id);
        $batches = MainTrainingBatch::all();
        //dd($batches);
        $comapnies = DB::table('companies')->get();
        $platoons = DB::table('platoons')->get();
        $atolls =  Atoll::all(); // Add this
        $islands = Island::all();
        //dd($comapnies, $platoons);
        return view('sms.students.edit', compact('student', 'batches','comapnies','platoons','atolls','islands'));
    }

    /**
     * Update student
     */
    public function updateStudent(Request $request, $id)
    { //dd($request->all());
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
            //'batch_id' => 'nullable|exists:sms_training_batches,id',
            'parent_email' => 'nullable|email',
            // 'permanent_atoll' => 'nullable|exists:atolls,id',
            // 'permanent_island' => 'nullable|exists:islands,id',
            // 'present_atoll' => 'nullable|exists:atolls,id',
            // 'present_island' => 'nullable|exists:islands,id',
            // 'parent_atoll' => 'nullable|exists:atolls,id',
            // 'parent_island' => 'nullable|exists:islands,id',
            //'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        
        $data = [];
        
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if (!empty($student->photo)) {
                $oldPath = storage_path('app/public/uploads/students/' . $student->photo);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Validate file
            $request->validate([
                'photo' => 'mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            // Generate unique filename
            $fileName = time() . '-photo.' . $request->file('photo')->getClientOriginalExtension();

            // Store file in storage/app/public/uploads/students
            $path = $request->file('photo')->storeAs('uploads/students', $fileName, 'public');

            // Save only filename in DB (optional but clean)
            $data['photo'] = $fileName;
        }




        // Calculate age
        if ($request->date_of_birth) {
            $data['age'] = \Carbon\Carbon::parse($request->date_of_birth)->age;
        }

        $data = array_merge($data,[
            'martial_status' => $request->martial_status,
            'kids' => $request->kids ?? NULL,
            'permanent_atoll_id' => $request->permanent_atoll,
            'permanent_island_id' => $request->permanent_island,
            'present_atoll_id' => $request->present_atoll,
            'present_island_id' => $request->present_island,
            'parent_atoll_id' => $request->parent_atoll,
            'parent_island_id' => $request->parent_island,
            'date_of_joining' => $request->date_of_joining ? \Carbon\Carbon::parse($request->date_of_joining)->format('Y-m-d') : null,
            'service_duration' => $request->service_duration,
            'blood_group' => $request->blood_group,
            'pay_amount' => $request->pay_amount
        ]);
        //dd( $data);
        $student->update($data);

        if (
            $request->filled('medical_condition') ||
            $request->filled('medical_severity_level') ||
            $request->filled('medical_notes')
        ) {
            \App\Models\SMS\MedicalRecord::updateOrCreate(
                ['student_id' => $student->id],
                [
                    'medical_condition' => $request->medical_condition,
                    'medical_severity_level' => $request->medical_severity_level,
                    'medical_notes' => $request->medical_notes,
                ]
            );
        }

        //  --- Handle Academic Record ---
        if (
            $request->filled('document_type') ||
            $request->filled('institution') ||
            $request->filled('start_date') ||
            $request->filled('end_date') ||
            $request->filled('result')
        ) {
            \App\Models\SMS\SmsAcademic::updateOrCreate(
                ['student_id' => $student->id],
                [
                    'document_type' => $request->document_type,
                    'institution' => $request->institution,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'result' => $request->result,
                ]
            );
        }

        //  --- Handle Observation Record ---
        if (
            $request->filled('observation_type') ||
            $request->filled('severity_level') ||
            $request->filled('observation_notes')
        ) {
            \App\Models\SMS\SmsObservation::updateOrCreate(
                ['student_id' => $student->id],
                [
                    'observation_type' => $request->observation_type,
                    'severity_level' => $request->severity_level,
                    'observation_notes' => $request->observation_notes,
                ]
            );
        }

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
        // Fetch from main students table, only those with a batch assigned via training enrollments
        $students = MainStudent::whereIn('status', ['active', 'approved', 'pending'])
            ->whereHas('trainingEnrollments')
            ->orderBy('name')
            ->get();

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
        //dd($query);
        // if ($request->filled('student_id')) {
        //     $query->where('student_id', $request->student_id);
        // }

        $medicalRecords = $query->latest()->paginate(20);
        // $students = Student::active()->get();

        // $studentsWithMedicalRecords = MedicalRecord::pluck('student_id');

        // $studentsWithoutMedicalRecords = Student::whereNotIn('id', $studentsWithMedicalRecords)->get();

        //return view('sms.medical.index', compact('medicalRecords', 'students', 'studentsWithoutMedicalRecords'));
        return view('sms.medical.index', compact('medicalRecords'));

    }

    public function storeMedical(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:sms_students,id',
            'record_date' => 'required|date',
            'description' => 'required|string',
            'attachments' => 'nullable|string',
        ]);

        MedicalRecord::create($request->all());

        return redirect()->route('sms.medical.index')->with('success', 'Medical record created successfully.');
    }

    public function showMedical($id)
    {
        $medicalRecord = MedicalRecord::with('student')->findOrFail($id);
        return response()->json($medicalRecord);
    }

    public function updateMedical(Request $request, $id)
    {
        $request->validate([
            'record_date' => 'required|date',
            'description' => 'required|string',
            'attachments' => 'nullable|string',
        ]);

        $medicalRecord = MedicalRecord::findOrFail($id);
        $medicalRecord->update($request->all());

        return redirect()->route('sms.medical.index')->with('success', 'Medical record updated successfully.');
    }

    public function destroyMedical($id)
    {
        $medicalRecord = MedicalRecord::findOrFail($id);
        $medicalRecord->delete();

        return redirect()->route('sms.medical.index')->with('success', 'Medical record deleted successfully.');
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

    public function storeStudentProfile(Request $request)
    {//dd($request->all());
        // $request->validate([
        //     //'student_id' => 'required|exists:students,id',
        //     'full_name' => 'required|string|max:255',
        //     'email' => 'required|email|max:255',
        //     'nid' => 'required|string|max:20',
        //     'mobile_no' => 'required|string|max:20',
        //     'dob' => 'required|date|before:today',
        //     'permanent_atoll' => 'required|string|max:255',
        //     'permanent_island' => 'required|string|max:255',
        //     'permanent_district' => 'required|string|max:255',
        //     'permanent_address' => 'required|string|max:500',
        //     'present_atoll' => 'required|string|max:255',
        //     'present_island' => 'required|string|max:255',
        //     'present_district' => 'required|string|max:255',
        //     'present_address' => 'required|string|max:500',
        //     'parent_name' => 'required|string|max:255',
        //     'parent_relation' => 'required|string|max:255',
        //     'parent_atoll' => 'required|string|max:255',
        //     'parent_island' => 'required|string|max:255',
        //     'parent_address' => 'required|string|max:500',
        //     'parent_mobile_no' => 'required|string|max:20',
        //     'parent_email' => 'nullable|email|max:255',
        // ]);

        // $validated = $request->validate([
        //    // 'full_name' => 'required|string|max:255',
        //     'email'     => 'required|email|unique:students,email',
        //     'dob'       => 'required|date',
        // ]);

        // if ($request->hasFile('profile_picture')) {
        //     $destinationPath = public_path('sms_students_profile');

        //     // Create directory if not exists
        //     if (!file_exists($destinationPath)) {
        //         mkdir($destinationPath, 0777, true);
        //     }

        //     // Generate unique filename
        //     $file = $request->file('profile_picture');
        //     $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        //     // Move file to public/sms_students_profile
        //     $file->move($destinationPath, $fileName);

        //     // Save relative path to DB (so we can easily access it later)
        //     $data['photo'] = 'sms_students_profile/' . $fileName;

        //     // Optional: Delete old photo if exists
        //     // if ($student->photo && file_exists(public_path($student->photo))) {
        //     //     unlink(public_path($student->photo));
        //     // }
        // }

        $data = [];
        if ($request->hasFile('profile_picture')) {

            $validation = [
                'mimes:' . 'png,jpg,jpeg,gif',
                'max:' . '2048',
            ];

            // File name pattern: student-temp-photo.extension
            $fileName = time() . '-photo.' . $request->file('profile_picture')->getClientOriginalExtension();
            $dir = 'uploads/students/';

            // Use Utility helper (handles local/S3/Wasabi)
            $path = Utility::upload_file($request, 'profile_picture', $fileName, $dir, $validation);

            if ($path['flag'] == 1) { 
                $data['photo'] = $fileName; // store file name only in DB
                //dd($data['photo']);
            } else { //dd('flag off');
                return redirect()->back()->with('error', __($path['msg']));
            }
        }

        $student = Student::create([
            'first_name'  => $request->first_name,
            'last_name' => $request->last_name,
            'email'             =>  $request->email,
            'photo'          => $data['photo'],
            'student_id' => rand(100000, 999999),
            'date_of_birth'     => $request->dob,
            'national_id' =>  $request->nid,
            'contact_no' => $request->mobile_no,
            'present_atoll_id' => $request->present_atoll,
            'present_island_id' => $request->present_island,
            'present_district' => $request->present_district,
            'present_address_name' => $request->present_address,

            'permanent_atoll_id' => $request->permanent_atoll,
            'permanent_island_id' => $request->permanent_island,
            'permanent_district' => $request->permanent_district,
            'permanent_address_name' => $request->permanent_address,

            'parent_name' => $request->parent_name,
            'parent_relationship' => $request->parent_relation,
            'parent_email' => $request->parent_email,
            'parent_contact_no' => $request->parent_mobile_no,
            'parent_address' => $request->parent_address,
            'parent_atoll_id' => $request->parent_atoll,
            'parent_island_id' => $request->parent_island,
            //'by_admin' => true,
        ]);
        $medicalRecord = MedicalRecord::create([
            'student_id' => $student->id,
            'medical_condition' => $request->medical_condition,
            'medical_severity_level' => $request->medical_severity_level,
            'medical_notes' => $request->medical_notes,
        ]);

        $smsAcademic = SmsAcademic::create([
            'student_id'    => $student->id,
            'document_type' => $request->document_type,
            'institution'   => $request->institution,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
            'result'        => $request->result,
        ]);

        $smsObservation = SmsObservation::create([
            'student_id'    => $student->id,
            'observation_type' => $request->observation_type,
            'severity_level'   => $request->severity_level,
            'observation_notes'    => $request->observation_notes,
        ]);

        // $age = \Carbon\Carbon::parse($request->dob)->age;
        // $isUnder18 = $age < 18;

        // DB::transaction(function () use ($request, $student, $isUnder18) {
        //     // Profile
        //     $student->profile()->updateOrCreate(
        //         ['student_id' => $student->id],
        //         [
        //             'first_name' => $request->first_name,
        //             'last_name' => $request->last_name,
        //             'nid' => $request->nid,
        //             'mobile_no' => $request->mobile_no,
        //             'dob' => $request->dob,
        //         ]
        //     );

        //     // Permanent address
        //     $student->addresses()->updateOrCreate(
        //         ['student_id' => $student->id, 'type' => 'permanent'],
        //         [
        //             'atoll' => $request->permanent_atoll,
        //             'island' => $request->permanent_island,
        //             'district' => $request->permanent_district,
        //             'address' => $request->permanent_address,
        //         ]
        //     );

        //     // Present address
        //     $student->addresses()->updateOrCreate(
        //         ['student_id' => $student->id, 'type' => 'present'],
        //         [
        //             'atoll' => $request->present_atoll,
        //             'island' => $request->present_island,
        //             'district' => $request->present_district,
        //             'address' => $request->present_address,
        //         ]
        //     );

        //     // Parent details
        //     $student->parentDetail()->updateOrCreate(
        //         ['student_id' => $student->id],
        //         [
        //             'name' => $request->parent_name,
        //             'relation' => $request->parent_relation,
        //             'atoll' => $request->parent_atoll,
        //             'island' => $request->parent_island,
        //             'address' => $request->parent_address,
        //             'mobile_no' => $request->parent_mobile_no,
        //             'email' => $request->parent_email,
        //         ]
        //     );

        //     // Update student main record
        //     $student->update([
        //         'profile_completed' => true,
        //         'is_under_age_18' => $isUnder18,
        //         'application_stage' => 'profile_completed',
        //     ]);
        // });

        return redirect()->route('sms.students.index')->with('success', 'Student profile saved successfully!');
    }

}
