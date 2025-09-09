<?php

namespace App\Http\Controllers;

use App\Models\Vetting;
use App\Models\JobPortalApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VettingController extends Controller
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
     * Display a listing of vetting records
     */
    public function index(Request $request)
    {
        $query = Vetting::with(['application.student', 'vettingOfficer']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $vettings = $query->latest()->paginate(20);
        $vettingTypes = ['police', 'dis', 'medical', 'background'];
        $statuses = ['pending', 'in_progress', 'completed', 'failed'];

        return view('job-portal.vetting.index', compact('vettings', 'vettingTypes', 'statuses'));
    }

    /**
     * Show the form for creating a new vetting record
     */
    public function create()
    {
        $applications = JobPortalApplication::with('student')
            ->where('status', 'approved')
            ->whereDoesntHave('vettings')
            ->get();

        return view('job-portal.vetting.create', compact('applications'));
    }

    /**
     * Store a newly created vetting record
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'application_id' => 'required|exists:job_portal_applications,id',
            'type' => 'required|in:police,dis,medical,background',
            'status' => 'required|in:pending,in_progress,completed,failed',
            'notes' => 'nullable|string|max:1000',
            'due_date' => 'nullable|date|after:today',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $vetting = Vetting::create([
            'application_id' => $request->application_id,
            'type' => $request->type,
            'status' => $request->status,
            'notes' => $request->notes,
            'due_date' => $request->due_date,
            'initiated_by' => Auth::id(),
        ]);

        return redirect()->route('job-portal.vetting.index')
            ->with('success', 'Vetting record created successfully.');
    }

    /**
     * Display the specified vetting record
     */
    public function show($id)
    {
        $vetting = Vetting::with(['application.student', 'vettingOfficer'])->findOrFail($id);
        return view('job-portal.vetting.show', compact('vetting'));
    }

    /**
     * Show the form for editing the specified vetting record
     */
    public function edit($id)
    {
        $vetting = Vetting::findOrFail($id);
        $applications = JobPortalApplication::with('student')->get();
        
        return view('job-portal.vetting.edit', compact('vetting', 'applications'));
    }

    /**
     * Update the specified vetting record
     */
    public function update(Request $request, $id)
    {
        $vetting = Vetting::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:police,dis,medical,background',
            'status' => 'required|in:pending,in_progress,completed,failed',
            'notes' => 'nullable|string|max:1000',
            'due_date' => 'nullable|date',
            'result' => 'nullable|string|max:1000',
            'completed_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $vetting->update([
            'type' => $request->type,
            'status' => $request->status,
            'notes' => $request->notes,
            'due_date' => $request->due_date,
            'result' => $request->result,
            'completed_at' => $request->completed_at,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('job-portal.vetting.index')
            ->with('success', 'Vetting record updated successfully.');
    }

    /**
     * Remove the specified vetting record
     */
    public function destroy($id)
    {
        $vetting = Vetting::findOrFail($id);
        $vetting->delete();

        return redirect()->route('job-portal.vetting.index')
            ->with('success', 'Vetting record deleted successfully.');
    }
}
