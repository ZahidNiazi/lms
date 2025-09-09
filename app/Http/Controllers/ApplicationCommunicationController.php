<?php

namespace App\Http\Controllers;

use App\Models\ApplicationCommunication;
use App\Models\JobPortalApplication;
use App\Models\StudentNotification;
use App\Mail\CommunicationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ApplicationCommunicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->type !== 'super admin') {
                return redirect()->route('job-portal.dashboard')->with('error', __('Permission Denied. Only super admin can access this area.'));
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
     * Show the form for creating a new communication
     */
    public function create(Request $request)
    {
        $applications = JobPortalApplication::with(['student.profile'])
            ->whereIn('status', ['approved', 'selected', 'interview_scheduled', 'batch_assigned'])
            ->latest()
            ->get();
        
        $selectedApplicationId = $request->get('application_id');
        
        return view('job-portal.communications.create', compact('applications', 'selectedApplicationId'));
    }

    /**
     * Store a newly created communication
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'application_id' => 'required|exists:job_portal_applications,id',
            'type' => 'required|in:email,notification',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $application = JobPortalApplication::with('student.profile')->findOrFail($request->application_id);

        $studentName = $application->student->profile 
            ? $application->student->profile->first_name . ' ' . $application->student->profile->last_name
            : $application->student->name;

        $communication = ApplicationCommunication::create([
            'application_id' => $request->application_id,
            'type' => $request->type,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
            'recipient_info' => [
                'name' => $studentName,
                'email' => $application->student->email,
                'phone' => $application->student->profile->mobile_no ?? null
            ],
            'sent_by' => Auth::id()
        ]);

        // Send the communication based on type
        try {
            if ($request->type === 'email') {
                $this->sendEmail($communication, $application, $studentName);
            } elseif ($request->type === 'sms') {
                $this->sendSMS($communication, $application, $studentName);
            } elseif ($request->type === 'whatsapp') {
                $this->sendWhatsApp($communication, $application, $studentName);
            } elseif ($request->type === 'notification') {
                $this->sendNotification($communication, $application, $studentName);
            }

            $communication->update([
                'status' => 'sent',
                'sent_at' => now()
            ]);

            return redirect()->route('job-portal.communications.index')
                ->with('success', 'Communication sent successfully.');

        } catch (\Exception $e) {
            Log::error('Failed to send communication: ' . $e->getMessage());
            
            $communication->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to send communication: ' . $e->getMessage())
                ->withInput();
        }
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
     * Show the form for editing the specified communication
     */
    public function edit($id)
    {
        $communication = ApplicationCommunication::with(['application.student', 'sender'])->findOrFail($id);
        $applications = JobPortalApplication::with('student')->where('status', 'approved')->get();
        
        return view('job-portal.communications.edit', compact('communication', 'applications'));
    }

    /**
     * Update the specified communication
     */
    public function update(Request $request, $id)
    {
        $communication = ApplicationCommunication::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'application_id' => 'required|exists:job_portal_applications,id',
            'type' => 'required|in:email,sms,whatsapp,notification',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'status' => 'required|in:draft,sent,delivered,acknowledged,failed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $communication->update([
            'application_id' => $request->application_id,
            'type' => $request->type,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => $request->status,
        ]);

        return redirect()->route('job-portal.communications.show', $id)
            ->with('success', 'Communication updated successfully.');
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

    /**
     * Send email communication
     */
    private function sendEmail($communication, $application, $studentName)
    {
        try {
            Mail::to($application->student->email)->send(
                new CommunicationMail(
                    $communication->subject,
                    $communication->message,
                    $studentName,
                    $application->application_number
                )
            );
            
            Log::info('Email sent successfully', [
                'to' => $application->student->email,
                'subject' => $communication->subject,
                'communication_id' => $communication->id
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send email: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send SMS communication
     */
    private function sendSMS($communication, $application, $studentName)
    {
        try {
            // For now, we'll just log the SMS
            // In production, you would integrate with SMS service like Twilio
            Log::info('SMS sent successfully', [
                'to' => $application->student->profile->mobile_no ?? 'N/A',
                'message' => $communication->message,
                'communication_id' => $communication->id
            ]);
            
            // TODO: Implement actual SMS sending
            // Example: Twilio::message($phone, $message);
            
        } catch (\Exception $e) {
            Log::error('Failed to send SMS: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send WhatsApp communication
     */
    private function sendWhatsApp($communication, $application, $studentName)
    {
        try {
            // For now, we'll just log the WhatsApp message
            // In production, you would integrate with WhatsApp Business API
            Log::info('WhatsApp message sent successfully', [
                'to' => $application->student->profile->mobile_no ?? 'N/A',
                'message' => $communication->message,
                'communication_id' => $communication->id
            ]);
            
            // TODO: Implement actual WhatsApp sending
            // Example: WhatsApp API integration
            
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp message: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send notification communication
     */
    private function sendNotification($communication, $application, $studentName)
    {
        try {
            // Create notification for the student
            StudentNotification::create([
                'student_id' => $application->student_id,
                'application_id' => $application->id,
                'type' => 'info',
                'title' => $communication->subject ?: 'New Message from National Service LMS',
                'message' => $communication->message,
                'metadata' => [
                    'application_number' => $application->application_number,
                    'communication_id' => $communication->id,
                    'sent_by' => Auth::user()->name
                ]
            ]);
            
            Log::info('Notification created successfully', [
                'student_id' => $application->student_id,
                'student_name' => $studentName,
                'message' => $communication->message,
                'communication_id' => $communication->id
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to create notification: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Remove the specified communication from storage
     */
    public function destroy($id)
    {
        try {
            $communication = ApplicationCommunication::findOrFail($id);
            $communication->delete();

            return redirect()->route('job-portal.communications.index')
                ->with('success', 'Communication deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete communication: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to delete communication: ' . $e->getMessage());
        }
    }

    /**
     * Resend a failed communication
     */
    public function resend($id)
    {
        try {
            $communication = ApplicationCommunication::with(['application.student.profile'])->findOrFail($id);
            $application = $communication->application;
            
            $studentName = $application->student->profile 
                ? $application->student->profile->first_name . ' ' . $application->student->profile->last_name
                : $application->student->name;

            // Reset the communication status
            $communication->update([
                'status' => 'pending',
                'error_message' => null
            ]);

            // Send the communication based on type
            if ($communication->type === 'email') {
                $this->sendEmail($communication, $application, $studentName);
            } elseif ($communication->type === 'notification') {
                $this->sendNotification($communication, $application, $studentName);
            }

            $communication->update([
                'status' => 'sent',
                'sent_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Communication resent successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to resend communication: ' . $e->getMessage());
            
            $communication->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to resend communication: ' . $e->getMessage()
            ], 500);
        }
    }

}