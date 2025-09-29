<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ApplicationStatus;
use App\Models\Student;
use App\Models\StudentDocument;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index(){
        if (Auth::guard('student')->check()) {
            return redirect()->route('student.dashboard');
        }
        return view('landing-page.index');
    }

    // public function jobPortal(){
    //     $students =  Student::with(['profile','addresses'])->get();
    // //    $student =  StudentDocument::get();
    // //    $student =  StudentProfile::get();
    // //    $student =  ApplicationStatus::get();
    //      dd($students);
    //     // if (Auth::guard('student')->check()) {
    //     //     return redirect()->route('student.job_portal');
    //     // }
    //     return view('landing-page.student.job_portal');
    // }

    public function jobPortal() {
        return view('landing-page.job_portal_login');
    }

    // public function jobPortal(Request $request) {
    //     $request->authenticate();
    //     $request->session()->regenerate();
    //     $user = Auth::user();

    //     if($user->type === 'super admin') {
    //         dd('Super Admin Logged In', $user);
    //     }

    //     $students = Student::with(['profile', 'addresses'])->get();

    //     return view('landing-page.student.job_portal', compact('students'));
    // }

    public function contact(){
        if (Auth::guard('student')->check()) {
            return redirect()->route('student.dashboard');
        }
        return view('landing-page.student_contact');
    }

    public function jobApplications(){
        $students = Student::has('profile')->with(['profile', 'addresses', 'jobPortalApplication'])->get();
        $totalApplications = Student::has('profile')->count();
        return view('landing-page.student.job_portal', compact('students', 'totalApplications'));
    }

    public function profileForm()
    {
        $student = Auth::guard('student')->user();
        $profile = $student->profile;
        $permanentAddress = $student->addresses()->where('type', 'permanent')->first();
        $presentAddress = $student->addresses()->where('type', 'present')->first();
        $parentDetail = $student->parentDetail;
        $documents = $student->documents;

        return view('landing-page.student.profile-form', compact('student', 'profile', 'permanentAddress', 'presentAddress', 'parentDetail', 'documents'));
    }

    public function submitProfile(Request $request)
    {
        $student = Auth::guard('student')->user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'nid' => 'required|string|max:20',
            'mobile_no' => 'required|string|max:20',
            'dob' => 'required|date|before:today',
            'permanent_atoll' => 'required|string|max:255',
            'permanent_island' => 'required|string|max:255',
            'permanent_district' => 'required|string|max:255',
            'permanent_address' => 'required|string|max:500',
            'present_atoll' => 'required|string|max:255',
            'present_island' => 'required|string|max:255',
            'present_district' => 'required|string|max:255',
            'present_address' => 'required|string|max:500',
            'parent_name' => 'required|string|max:255',
            'parent_relation' => 'required|string|max:255',
            'parent_atoll' => 'required|string|max:255',
            'parent_island' => 'required|string|max:255',
            'parent_address' => 'required|string|max:500',
            'parent_mobile_no' => 'required|string|max:20',
            'parent_email' => 'nullable|email|max:255',
        ]);

        // Calculate age and check if under 18
        $age = \Carbon\Carbon::parse($request->dob)->age;
        $isUnder18 = $age < 18;

        DB::transaction(function () use ($request, $student, $isUnder18) {
            // Create or update profile
            $student->profile()->updateOrCreate(
                ['student_id' => $student->id],
                [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'nid' => $request->nid,
                    'mobile_no' => $request->mobile_no,
                    'dob' => $request->dob,
                ]
            );

            // Create or update permanent address
            $student->addresses()->updateOrCreate(
                ['student_id' => $student->id, 'type' => 'permanent'],
                [
                    'atoll' => $request->permanent_atoll,
                    'island' => $request->permanent_island,
                    'district' => $request->permanent_district,
                    'address' => $request->permanent_address,
                ]
            );

            // Create or update present address
            $student->addresses()->updateOrCreate(
                ['student_id' => $student->id, 'type' => 'present'],
                [
                    'atoll' => $request->present_atoll,
                    'island' => $request->present_island,
                    'district' => $request->present_district,
                    'address' => $request->present_address,
                ]
            );

            // Create or update parent details
            $student->parentDetail()->updateOrCreate(
                ['student_id' => $student->id],
                [
                    'name' => $request->parent_name,
                    'relation' => $request->parent_relation,
                    'atoll' => $request->parent_atoll,
                    'island' => $request->parent_island,
                    'address' => $request->parent_address,
                    'mobile_no' => $request->parent_mobile_no,
                    'email' => $request->parent_email,
                ]
            );

            // Update student profile completion status
            $student->update([
                'profile_completed' => true,
                'is_under_age_18' => $isUnder18,
                'application_stage' => 'profile_completed',
            ]);

            // Update job portal application status if it exists
            if ($student->jobPortalApplication) {
                $student->jobPortalApplication->update([
                    'status' => 'document_review' // Move to document review stage
                ]);
            } else {
                // Create job portal application if it doesn't exist (fallback)
                $applicationNumber = $this->generateUniqueApplicationNumber();

                \App\Models\JobPortalApplication::create([
                    'student_id' => $student->id,
                    'application_number' => $applicationNumber,
                    'status' => 'document_review',
                    'documents_verified' => false,
                    'basic_criteria_met' => false
                ]);
            }
        });

        return redirect()->route('student.dashboard')->with('success', 'Profile updated successfully! Your National Service application has been created.');
    }

    public function documents()
    {
        // Redirect to dashboard with documents tab active
        return redirect()->route('student.dashboard')->with('active_tab', 'documents');
    }

    public function storeDocument(Request $request)
    {
        $student = Auth::guard('student')->user();

        // Debug: Log request details
        \Log::info('Document upload request', [
            'student_id' => $student->id,
            'request_data' => $request->all(),
            'has_file' => $request->hasFile('file'),
            'file_info' => $request->hasFile('file') ? [
                'name' => $request->file('file')->getClientOriginalName(),
                'size' => $request->file('file')->getSize(),
                'mime' => $request->file('file')->getMimeType(),
            ] : null,
            'is_ajax' => $request->ajax(),
            'wants_json' => $request->wantsJson(),
            'accept_header' => $request->header('Accept'),
        ]);

        try {
            $request->validate([
                'type' => 'required|string|in:parent_consent,photo,nid_copy,school_leaving,olevel,alevel,police_report',
                'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'school_name' => 'nullable|string|max:255',
                'year' => 'nullable|string|max:10',
                'report_number' => 'nullable|string|max:100',
                'subjects' => 'nullable|string|max:500',
                'result' => 'nullable|string|max:255',
            ]);

            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('student_documents', $filename, 'public');

            $documentData = [
                'student_id' => $student->id,
                'type' => $request->type,
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
            ];

            // Add specific fields based on document type
            if ($request->school_name) $documentData['school_name'] = $request->school_name;
            if ($request->year) $documentData['year'] = $request->year;
            if ($request->report_number) $documentData['report_number'] = $request->report_number;
            if ($request->subjects) $documentData['subjects'] = $request->subjects;
            if ($request->result) $documentData['result'] = $request->result;

            StudentDocument::create($documentData);

            // Check if this is an AJAX request or if it's a fetch request
            if ($request->ajax() || $request->wantsJson() || $request->header('Accept') === 'application/json') {
                return response()->json([
                    'success' => true,
                    'message' => 'Document uploaded successfully!'
                ]);
            }

            return redirect()->route('student.dashboard')->with(['success' => 'Document uploaded successfully!', 'active_tab' => 'documents']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson() || $request->header('Accept') === 'application/json') {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson() || $request->header('Accept') === 'application/json') {
                return response()->json([
                    'success' => false,
                    'message' => 'Error uploading document: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error uploading document: ' . $e->getMessage());
        }
    }

    public function downloadDocument(StudentDocument $document)
    {
        $student = Auth::guard('student')->user();

        if ($document->student_id !== $student->id) {
            abort(403, 'Unauthorized access to document.');
        }

        return response()->download(storage_path('app/public/' . $document->file_path), $document->original_name);
    }

    public function destroyDocument(StudentDocument $document)
    {
        $student = Auth::guard('student')->user();

        if ($document->student_id !== $student->id) {
            if (request()->ajax() || request()->wantsJson() || request()->header('Accept') === 'application/json') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to document.'
                ], 403);
            }
            abort(403, 'Unauthorized access to document.');
        }

        try {
            // Delete file from storage
            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            $document->delete();

            // Check if this is an AJAX request
            if (request()->ajax() || request()->wantsJson() || request()->header('Accept') === 'application/json') {
                return response()->json([
                    'success' => true,
                    'message' => 'Document deleted successfully!'
                ]);
            }

            return redirect()->route('student.documents')->with('success', 'Document deleted successfully!');

        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson() || request()->header('Accept') === 'application/json') {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting document: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error deleting document: ' . $e->getMessage());
        }
    }

    public function downloadParentConsentForm()
    {
        // Generate and return parent consent form PDF
        // For now, return a simple response
        return response()->download(public_path('documents/parent_consent_form.pdf'));
    }

    public function applicationStatus()
    {
        $student = Auth::guard('student')->user();
        $jobPortalApplication = $student->jobPortalApplication;
        $status = $jobPortalApplication; // Use jobPortalApplication as status

        return view('landing-page.student.application-status', compact('student', 'jobPortalApplication', 'status'));
    }

    public function acknowledgeInterview(Request $request, $id)
    {
        $student = Auth::guard('student')->user();
        $interviewSchedule = \App\Models\JobPortalInterviewSchedule::findOrFail($id);

        // Verify the interview belongs to the student's application
        if ($interviewSchedule->application->student_id !== $student->id) {
            return redirect()->back()->with('error', 'Unauthorized access to interview schedule.');
        }

        $interviewSchedule->acknowledge();

        return redirect()->route('student.dashboard')->with('success', 'Interview schedule acknowledged successfully!');
    }

    public function dashboard()
    {
        $student = Auth::guard('student')->user();

        // Safely get relationships with null checks
        $profile = $student->profile ?? null;
        $permanentAddress = $student->addresses()->where('type', 'permanent')->first() ?? null;
        $presentAddress = $student->addresses()->where('type', 'present')->first() ?? null;
        $parentDetail = $student->parentDetail ?? null;
        $documents = $student->documents()->get()->keyBy('type');
        $jobPortalApplication = $student->jobPortalApplication()->with('preferredInterviewLocation')->first() ?? null;

        // Get interview schedule data
        $interviewSchedule = null;
        if ($jobPortalApplication) {
            $interviewSchedule = \App\Models\JobPortalInterviewSchedule::with('location')
                ->where('application_id', $jobPortalApplication->id)
                ->where('status', '!=', 'cancelled')
                ->latest()
                ->first();
        }

        // Check eligibility
        $eligibility = $this->checkEligibility($student);
        $ongoingPrograms = $this->getOngoingPrograms();

        // Get notifications
        $notifications = $student->notifications()
            ->with('application')
            ->latest()
            ->limit(10)
            ->get();

        $unreadNotificationsCount = $student ? $student->notifications()->unread()->count() : 0;

        // Get interview locations for the preference form
        $interviewLocations = \App\Models\InterviewLocation::active()
            ->select('id', 'name', 'address', 'city', 'atoll', 'contact_person', 'contact_phone', 'capacity', 'available_facilities')
            ->get()
            ->map(function ($location) {
                return [
                    'id' => $location->id,
                    'name' => $location->name,
                    'address' => $location->getFullAddress(),
                    'contact_info' => $location->getContactInfo(),
                    'capacity' => $location->capacity,
                    'facilities' => $location->getFacilitiesList()
                ];
            });

        return view('landing-page.student.dashboard', compact('student', 'profile', 'permanentAddress', 'presentAddress', 'parentDetail', 'documents', 'jobPortalApplication', 'interviewSchedule', 'eligibility', 'ongoingPrograms', 'notifications', 'unreadNotificationsCount', 'interviewLocations'));
    }

    /**
     * Mark notification as read
     */
    public function markNotificationAsRead($id)
    {
        try {
            $student = Auth::guard('student')->user();

            if (!$student) {
                \Log::error('Student not authenticated for notification mark as read');
                return response()->json(['success' => false, 'error' => 'Student not authenticated'], 401);
            }

            \Log::info('Student authenticated: ' . $student->id . ', marking notification: ' . $id);

            $notification = $student->notifications()->findOrFail($id);
            $notification->markAsRead();

            \Log::info('Notification marked as read successfully');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Error marking notification as read: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Failed to mark notification as read: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsAsRead()
    {
        try {
            $student = Auth::guard('student')->user();

            if (!$student) {
                \Log::error('Student not authenticated for mark all notifications as read');
                return response()->json(['success' => false, 'error' => 'Student not authenticated'], 401);
            }

            \Log::info('Student authenticated: ' . $student->id . ', marking all notifications as read');

            $updated = $student->notifications()->unread()->update([
                'is_read' => true,
                'read_at' => now()
            ]);

            \Log::info('Marked ' . $updated . ' notifications as read');
            return response()->json(['success' => true, 'updated_count' => $updated]);
        } catch (\Exception $e) {
            \Log::error('Error marking all notifications as read: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Failed to mark all notifications as read: ' . $e->getMessage()], 500);
        }
    }

    private function checkEligibility($student)
    {
        $age = null;
        $isEligible = false;
        $eligibilityMessage = '';
        $needsParentConsent = false;

        if ($student->profile && $student->profile->dob) {
            $age = \Carbon\Carbon::parse($student->profile->dob)->age;

            if ($age >= 16 && $age <= 28) {
                $isEligible = true;
                $eligibilityMessage = 'You are eligible to apply for National Service.';

                if ($age < 18) {
                    $needsParentConsent = true;
                    $eligibilityMessage .= ' Since you are under 18, you will need to upload a parent consent form.';
                }
            } elseif ($age < 16) {
                $eligibilityMessage = 'You are too young to apply for National Service. Minimum age is 16 years.';
            } else {
                $eligibilityMessage = 'You are too old to apply for National Service. Maximum age is 28 years.';
            }
        } else {
            $eligibilityMessage = 'Please complete your profile to check eligibility.';
        }

        return [
            'age' => $age,
            'is_eligible' => $isEligible,
            'message' => $eligibilityMessage,
            'needs_parent_consent' => $needsParentConsent
        ];
    }

    private function getOngoingPrograms()
    {
        // Check if there are any active National Service programs
        $activeBatches = \App\Models\TrainingBatch::where('status', 'active')
            ->where('start_date', '>', now())
            ->count();

        return [
            'has_ongoing_program' => $activeBatches > 0,
            'active_batches' => $activeBatches
        ];
    }

    private function generateUniqueApplicationNumber()
    {
        $year = date('Y');
        $prefix = 'NS-' . $year . '-';

        // Get the last application number for this year
        $lastApplication = \App\Models\JobPortalApplication::where('application_number', 'like', $prefix . '%')
            ->orderBy('application_number', 'desc')
            ->first();

        if ($lastApplication) {
            // Extract the number part and increment
            $lastNumber = (int) substr($lastApplication->application_number, strlen($prefix));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get available interview locations for student selection
     */
    public function getInterviewLocations()
    {
        try {
            $locations = \App\Models\InterviewLocation::active()
                ->select('id', 'name', 'address', 'city', 'atoll', 'contact_person', 'contact_phone', 'capacity', 'available_facilities')
                ->get()
                ->map(function ($location) {
                    return [
                        'id' => $location->id,
                        'name' => $location->name,
                        'address' => $location->getFullAddress(),
                        'contact_info' => $location->getContactInfo(),
                        'capacity' => $location->capacity,
                        'facilities' => $location->getFacilitiesList()
                    ];
                });

            return response()->json([
                'success' => true,
                'locations' => $locations
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching interview locations: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch interview locations'
            ], 500);
        }
    }

    /**
     * Submit student's interview location preference
     */
    public function submitLocationPreference(Request $request)
    {
        try {
            $student = Auth::guard('student')->user();

            if (!$student) {
                return response()->json(['success' => false, 'error' => 'Student not authenticated'], 401);
            }

            $validator = Validator::make($request->all(), [
                'location_id' => 'required|exists:interview_locations,id',
                'preference_reason' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get the student's job portal application
            $application = \App\Models\JobPortalApplication::where('student_id', $student->id)->first();

            if (!$application) {
                return response()->json([
                    'success' => false,
                    'error' => 'No application found for this student'
                ], 404);
            }

            // Update or create location preference
            $application->update([
                'preferred_interview_location_id' => $request->location_id,
                'location_preference_reason' => $request->preference_reason,
                'location_preference_submitted_at' => now()
            ]);

            // Get the selected location details
            $selectedLocation = \App\Models\InterviewLocation::find($request->location_id);

            // Create notification for admin
            \App\Models\StudentNotification::create([
                'student_id' => $student->id,
                'application_id' => $application->id,
                'type' => 'info',
                'title' => '📍 Interview Location Preference Submitted',
                'message' => 'Student has submitted their interview location preference.',
                'is_read' => false,
                'metadata' => [
                    'location_id' => $request->location_id,
                    'location_name' => $selectedLocation->name ?? 'Unknown',
                    'preference_reason' => $request->preference_reason,
                    'application_number' => $application->application_number
                ]
            ]);

            // Send email notification to admin
            try {
                $adminEmails = \App\Models\User::where('type', 'super admin')->pluck('email')->toArray();
                if (!empty($adminEmails)) {
                    \Illuminate\Support\Facades\Mail::to($adminEmails)->send(
                        new \App\Mail\LocationPreferenceNotificationMail($student, $application, $selectedLocation, $request->preference_reason)
                    );
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send location preference email notification: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Interview location preference submitted successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error submitting location preference: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to submit location preference'
            ], 500);
        }
    }
}
