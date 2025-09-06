<?php

namespace App\Http\Controllers;

use App\Models\ApplicationCommunication;
use App\Models\JobPortalApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApplicationCommunicationController extends Controller
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
     * Display a listing of communications
     */
    public function index(Request $request)
    {
        $query = ApplicationCommunication::with(['application.student', 'sender']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('acknowledged')) {
            if ($request->acknowledged === 'yes') {
                $query->acknowledged();
            } else {
                $query->notAcknowledged();
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $communications = $query->latest()->paginate(20);
        $types = ['email', 'sms', 'whatsapp', 'system'];
        $statuses = ['pending', 'sent', 'delivered', 'failed'];

        return view('job-portal.communications.index', compact('communications', 'types', 'statuses'));
    }

    /**
     * Display the specified communication
     */
    public function show($id)
    {
        $communication = ApplicationCommunication::with([
            'application.student.profile',
            'application.student.addresses',
            'sender'
        ])->findOrFail($id);

        return view('job-portal.communications.show', compact('communication'));
    }

    /**
     * Send a new communication
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'application_id' => 'required|exists:job_portal_applications,id',
            'type' => 'required|in:email,sms,whatsapp',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $application = JobPortalApplication::with('student')->findOrFail($request->application_id);

        $communication = ApplicationCommunication::create([
            'application_id' => $request->application_id,
            'type' => $request->type,
            'subject' => $request->subject,
            'message' => $request->message,
            'recipient_info' => [
                'name' => $application->student->name,
                'email' => $application->student->email,
                'phone' => $application->student->profile->mobile_no ?? null
            ],
            'sent_by' => Auth::id()
        ]);

        // Here you would integrate with actual email/SMS/WhatsApp services
        // For now, we'll just mark it as sent
        $communication->markAsSent();

        return redirect()->back()->with('success', 'Communication sent successfully.');
    }

    /**
     * Resend a failed communication
     */
    public function resend($id)
    {
        $communication = ApplicationCommunication::findOrFail($id);

        if ($communication->status !== 'failed') {
            return redirect()->back()->with('error', 'Only failed communications can be resent.');
        }

        // Reset status and attempt to resend
        $communication->update(['status' => 'pending']);
        
        // Here you would integrate with actual email/SMS/WhatsApp services
        $communication->markAsSent();

        return redirect()->back()->with('success', 'Communication resent successfully.');
    }

    /**
     * Get communication statistics
     */
    public function statistics()
    {
        $stats = [
            'total' => ApplicationCommunication::count(),
            'sent' => ApplicationCommunication::sent()->count(),
            'delivered' => ApplicationCommunication::delivered()->count(),
            'failed' => ApplicationCommunication::failed()->count(),
            'acknowledged' => ApplicationCommunication::acknowledged()->count(),
            'by_type' => ApplicationCommunication::selectRaw('type, count(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type'),
            'by_status' => ApplicationCommunication::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
        ];

        return response()->json($stats);
    }
}