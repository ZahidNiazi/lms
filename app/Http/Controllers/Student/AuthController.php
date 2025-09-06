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
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('student')->login($student);

        return redirect()->route('student.profile.form');
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

            $student = Auth::guard('student')->user();
            $profile = $student->profile;
            $permanentAddress = $student->addresses()->where('type', 'permanent')->first();
            $presentAddress = $student->addresses()->where('type', 'present')->first();
            $parentDetail = $student->parentDetail;
            $documents = $student->documents()->get()->keyBy('type');

            return view('landing-page.student.profile-form', compact('profile', 'permanentAddress', 'presentAddress', 'parentDetail', 'documents'));
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
