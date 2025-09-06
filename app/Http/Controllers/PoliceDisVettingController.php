<?php

namespace App\Http\Controllers;

use App\Models\PoliceDisVetting;
use App\Models\JobPortalApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PoliceDisVettingController extends Controller
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
        $query = PoliceDisVetting::with(['application.student.profile', 'processor']);

        if ($request->filled('vetting_type')) {
            $query->where('vetting_type', $request->vetting_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('submitted_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('submitted_date', '<=', $request->date_to);
        }

        $vettings = $query->latest()->paginate(20);
        $vettingTypes = ['police', 'dis'];
        $statuses = ['pending', 'in_progress', 'cleared', 'failed', 'rejected'];

        return view('job-portal.vetting.index', compact('vettings', 'vettingTypes', 'statuses'));
    }

    /**
     * Update vetting status
     */
    public function update(Request $request, $id)
    {
        $vetting = PoliceDisVetting::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,in_progress,cleared,failed,rejected',
            'reference_number' => 'nullable|string|max:255',
            'comments' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $vetting->update([
            'status' => $request->status,
            'reference_number' => $request->reference_number,
            'comments' => $request->comments,
            'processed_by' => Auth::id()
        ]);

        // Update completion date if status is completed
        if (in_array($request->status, ['cleared', 'failed', 'rejected'])) {
            $vetting->update(['completed_date' => now()->toDateString()]);
        }

        // Send notification to student if status changed
        if ($request->status === 'cleared') {
            $this->sendVettingClearedNotification($vetting);
        } elseif (in_array($request->status, ['failed', 'rejected'])) {
            $this->sendVettingFailedNotification($vetting);
        }

        return redirect()->back()->with('success', 'Vetting status updated successfully.');
    }

    /**
     * Create vetting record for an application
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'application_id' => 'required|exists:job_portal_applications,id',
            'vetting_type' => 'required|in:police,dis'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $application = JobPortalApplication::findOrFail($request->application_id);

        // Check if vetting already exists for this application and type
        $existingVetting = PoliceDisVetting::where('application_id', $request->application_id)
            ->where('vetting_type', $request->vetting_type)
            ->first();

        if ($existingVetting) {
            return redirect()->back()->with('error', 'Vetting record already exists for this application and type.');
        }

        PoliceDisVetting::create([
            'application_id' => $request->application_id,
            'vetting_type' => $request->vetting_type,
            'status' => 'pending',
            'submitted_date' => now()->toDateString()
        ]);

        return redirect()->back()->with('success', 'Vetting record created successfully.');
    }

    /**
     * Get vetting statistics
     */
    public function statistics()
    {
        $stats = [
            'total' => PoliceDisVetting::count(),
            'pending' => PoliceDisVetting::pending()->count(),
            'in_progress' => PoliceDisVetting::inProgress()->count(),
            'cleared' => PoliceDisVetting::cleared()->count(),
            'failed' => PoliceDisVetting::failed()->count(),
            'rejected' => PoliceDisVetting::rejected()->count(),
            'by_type' => PoliceDisVetting::selectRaw('vetting_type, count(*) as count')
                ->groupBy('vetting_type')
                ->pluck('count', 'vetting_type'),
            'by_status' => PoliceDisVetting::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
        ];

        return response()->json($stats);
    }

    /**
     * Send vetting cleared notification
     */
    private function sendVettingClearedNotification($vetting)
    {
        // Implementation for sending notification when vetting is cleared
        // This would integrate with the notification system
    }

    /**
     * Send vetting failed notification
     */
    private function sendVettingFailedNotification($vetting)
    {
        // Implementation for sending notification when vetting fails
        // This would integrate with the notification system
    }
}