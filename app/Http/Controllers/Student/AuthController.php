<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Hash;
use Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:student')->except('logout');
    }


    public function getRegister(){
        if (Auth::guard('student')->check()) {
            return redirect()->route('student.profile.form');
        }
        return view('landing-page.student_register');
    }

    public function postRegister(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'password' => 'required|min:8|confirmed',
            'date_of_birth' => 'required|date|before:today',
        ]);

        // Calculate age
        $age = \Carbon\Carbon::parse($request->date_of_birth)->age;
        
        // Check age eligibility
        if ($age < 16 || $age > 28) {
            return back()->withErrors([
                'date_of_birth' => 'Age must be between 16 and 28 years to apply for National Service.',
            ]);
        }

        // Check if there's an ongoing National Service program
        $ongoingProgram = $this->checkOngoingProgram();
        if (!$ongoingProgram) {
            return back()->withErrors([
                'program' => 'There is currently no ongoing National Service program. Please check back later.',
            ]);
        }

        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'date_of_birth' => $request->date_of_birth,
            'is_under_age_18' => $age < 18,
            'application_date' => now(),
            'status' => 'pending',
            'application_stage' => 'registration_completed',
        ]);

        // Create job portal application immediately after registration
        $applicationNumber = $this->generateUniqueApplicationNumber();
        
        \App\Models\JobPortalApplication::create([
            'student_id' => $student->id,
            'application_number' => $applicationNumber,
            'status' => 'pending_review',
            'documents_verified' => false,
            'basic_criteria_met' => false
        ]);

        Auth::guard('student')->login($student);

        return redirect()->route('student.profile.form')->with('success', 'Registration successful! Your National Service application has been created. Please complete your profile to proceed.');
    }

    private function checkOngoingProgram()
    {
        // Check if there's an active recruitment period
        // This could be based on dates, settings, or other criteria
        return true; // For now, always return true
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

    public function getLogin(Request $request){
        if (Auth::guard('student')->check()) {
            return redirect()->route('student.profile.form');
        }
        return view('landing-page.student_login');
    }

    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('student')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {
            $request->session()->regenerate();
            return redirect()->route('student.dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials',
        ]);
    }

    public function logout()
    {
        Auth::guard('student')->logout();
        return redirect()->route('landing_page');
    }

    public function jobPostLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->type === 'super admin') {
                return redirect()->route('job-portal.dashboard');
            } else {
                return back()->withErrors([
                    'msg' => "You're not super admin.",
                ]);
            }
        }

        return back()->withErrors([
            'msg' => 'The provided credentials do not match our records.',
        ]);
    }

    public function jobLogout(Request $request)
    {
        Auth::guard('web')->logout();

        return redirect()->route('landing_page');
    }
}
