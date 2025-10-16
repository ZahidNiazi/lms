<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\TrainingBatch;
use App\Models\StudentTrainingEnrollment;
use App\Models\Deployment;
use App\Models\Interview;
use App\Models\CertificateProgram;
use App\Models\StudentCertificate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\StudentNotification;
use Twilio\Rest\Client;
use App\Models\Utility;
use Illuminate\Support\Facades\Log;

class NationalServiceLMSController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        // Check if user is super admin
        if (Auth::user()->type !== 'super admin') {
            abort(403, 'Unauthorized access');
        }

        $data = $this->getDashboardData();

        return view('national-service-lms.dashboard', compact('data'));
    }

    private function getDashboardData()
    {
        $data = [];

        // Applicant Screening & Interview Process
        $data['total_applications'] = Student::count();
        $data['pending_review'] = Student::where('application_stage', 'pending')->count();
        $data['approved_for_interview'] = Student::where('application_stage', 'approved_for_interview')->count();
        $data['interview_scheduled'] = Student::where('application_stage', 'interview_scheduled')->count();
        $data['interview_completed'] = Student::where('application_stage', 'interview_completed')->count();
        $data['application_rejected'] = Student::where('application_stage', 'rejected')->count();

        // National Service Training Management
        $data['active_batches'] = TrainingBatch::where('status', 'active')->count();
        $data['students_not_reachable'] = Student::where('is_reachable', false)->count();
        $data['graduated_from_training'] = StudentTrainingEnrollment::where('status', 'completed')->count();
        $data['awaiting_training'] = StudentTrainingEnrollment::where('status', 'awaiting')->count();
        $data['currently_in_training'] = StudentTrainingEnrollment::where('status', 'in_training')->count();
        $data['training_terminated'] = StudentTrainingEnrollment::where('status', 'terminated')->count();

        // Post Graduation Deployment
        $data['deployed_to_mndf'] = Deployment::where('unit', 'mndf')->where('status', 'active')->count();
        $data['deployed_to_police'] = Deployment::where('unit', 'police')->where('status', 'active')->count();
        $data['deployed_to_other_units'] = Deployment::where('unit', 'other')->where('status', 'active')->count();
        $data['certificate_programs'] = CertificateProgram::where('status', 'active')->count();
        $data['under_age_18'] = Student::where('is_under_age_18', true)->count();

        // Control Center
        $data['active_users'] = User::where('is_enable_login', true)->count();
        $data['active_students'] = Student::where('status', 'approved')->count();

        // Recent Activities
        $data['recent_applications'] = Student::with('profile')
            ->latest()
            ->limit(10)
            ->get();

        $data['recent_interviews'] = Interview::with('student')
            ->latest()
            ->limit(10)
            ->get();

        $data['recent_deployments'] = Deployment::with('student')
            ->latest()
            ->limit(10)
            ->get();

        return $data;
    }

    public function students(Request $request)
    {
        $query = Student::with(['profile', 'addresses', 'parentDetail', 'documents']);

        // Filter by application stage
        if ($request->filled('stage')) {
            $query->where('application_stage', $request->stage);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by reachability
        if ($request->filled('reachable')) {
            $query->where('is_reachable', $request->reachable);
        }

        $students = $query->latest()->paginate(20);

        return view('national-service-lms.students.index', compact('students'));
    }

    public function studentShow(Student $student)
    {
        $student->load(['profile', 'addresses', 'parentDetail', 'documents', 'trainingEnrollments.trainingBatch', 'deployments', 'interviews', 'certificates.certificateProgram']);

        return view('national-service-lms.students.show', compact('student'));
    }

    public function updateApplicationStage(Request $request, Student $student)
    {
        $request->validate([
            'application_stage' => 'required|in:pending,under_review,approved_for_interview,interview_scheduled,interview_completed,approved,rejected',
            'rejection_reason' => 'nullable|string|max:500'
        ]);

        $student->update([
            'application_stage' => $request->application_stage,
            'rejection_reason' => $request->rejection_reason
        ]);

        if ($request->application_stage === 'approved') {
            $student->update(['status' => 'approved']);
        } elseif ($request->application_stage === 'rejected') {
            $student->update(['status' => 'rejected']);
        }
        $application = $student->jobPortalApplication;
        $stageLabel = ucfirst(str_replace('_', ' ', $request->application_stage));
        StudentNotification::create([
                'student_id' => $student->id,
                'application_id' => $application->id,
                'type' => 'info',
                'title' => $stageLabel ?: 'New Message from National Service LMS',
                'message' => 'Your application status has been updated to ' .$stageLabel,
                'metadata' => [
                    'application_number' => $application->application_number,
                    //'communication_id' => $communication->id,
                    'sent_by' => Auth::user()->name
                ]
            ]);

        try {
            $settings = Utility::settings();

            $twilioSid   = $settings['twilio_sid']   ?? null;
            $twilioToken = $settings['twilio_token'] ?? null;
            $twilioFrom  = $settings['twilio_from']  ?? null;
            $mobileNo = $student->profile->mobile_no ?? null;
            if ($twilioSid && $twilioToken && $twilioFrom && !empty($mobileNo)) {
                $client = new Client($twilioSid, $twilioToken);

                $messageBody = "Hello {$student->first_name}, your application status has been updated to {$stageLabel}.";

                $client->messages->create($mobileNo, [
                    'from' => $twilioFrom,
                    'body' => $messageBody,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Twilio SMS failed: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Application stage updated successfully');
    }

    public function trainingBatches()
    {
        $batches = TrainingBatch::withCount('enrollments')->latest()->paginate(20);

        return view('national-service-lms.training.batches.index', compact('batches'));
    }

    public function createTrainingBatch()
    {
        return view('national-service-lms.training.batches.create');
    }

    public function storeTrainingBatch(Request $request)
    {
        $request->validate([
            'batch_name' => 'required|string|max:255',
            'batch_code' => 'required|string|max:50|unique:training_batches',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string'
        ]);

        TrainingBatch::create($request->all());

        return redirect()->route('national-service-lms.training.batches')->with('success', 'Training batch created successfully');
    }

    public function interviews()
    {
        $interviews = Interview::with('student')->latest()->paginate(20);

        // Get students eligible for interviews
        $eligibleStudents = Student::whereIn('application_stage', ['pending', 'under_review', 'approved_for_interview'])
            ->where('status', '!=', 'rejected')
            ->get();

        return view('national-service-lms.interviews.index', compact('interviews', 'eligibleStudents'));
    }

    public function scheduleInterview(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'scheduled_at' => 'required|date|after:now',
            'interviewer_name' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        Interview::create($request->all());

        // Update student application stage
        $student = Student::find($request->student_id);
        $student->update(['application_stage' => 'interview_scheduled']);

        return redirect()->back()->with('success', 'Interview scheduled successfully');
    }

    public function updateInterviewResult(Request $request, Interview $interview)
    {
        $request->validate([
            'result' => 'required|in:pass,fail,pending',
            'feedback' => 'nullable|string'
        ]);

        $interview->update($request->all());

        // Update student application stage based on result
        if ($request->result === 'pass') {
            $interview->student->update(['application_stage' => 'interview_completed']);
        } elseif ($request->result === 'fail') {
            $interview->student->update(['application_stage' => 'rejected']);
        }

        return redirect()->back()->with('success', 'Interview result updated successfully');
    }

    public function deployments()
    {
        $deployments = Deployment::with('student')->latest()->paginate(20);

        return view('national-service-lms.deployments.index', compact('deployments'));
    }

    public function createDeployment()
    {
        $students = Student::where('status', 'approved')->get();

        return view('national-service-lms.deployments.create', compact('students'));
    }

    public function storeDeployment(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'unit' => 'required|in:mndf,police,other',
            'unit_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'deployment_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        Deployment::create($request->all());

        return redirect()->route('national-service-lms.deployments')->with('success', 'Deployment created successfully');
    }

    public function certificatePrograms()
    {
        $programs = CertificateProgram::withCount('studentCertificates')->latest()->paginate(20);

        return view('national-service-lms.certificates.programs.index', compact('programs'));
    }

    public function createCertificateProgram()
    {
        return view('national-service-lms.certificates.programs.create');
    }

    public function storeCertificateProgram(Request $request)
    {
        $request->validate([
            'program_name' => 'required|string|max:255',
            'description' => 'required|string',
            'duration_months' => 'required|integer|min:1',
            'cost' => 'required|numeric|min:0'
        ]);

        CertificateProgram::create($request->all());

        return redirect()->route('national-service-lms.certificates.programs')->with('success', 'Certificate program created successfully');
    }

    public function reports()
    {
        // Generate various reports
        $monthlyApplications = Student::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->get();

        $applicationStages = Student::selectRaw('application_stage, COUNT(*) as count')
            ->groupBy('application_stage')
            ->get();

        $deploymentByUnit = Deployment::selectRaw('unit, COUNT(*) as count')
            ->where('status', 'active')
            ->groupBy('unit')
            ->get();

        return view('national-service-lms.reports.index', compact('monthlyApplications', 'applicationStages', 'deploymentByUnit'));
    }
}
