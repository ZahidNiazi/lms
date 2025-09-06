<?php

namespace App\Http\Controllers;

use App\Models\NotificationTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NotificationTemplateController extends Controller
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
     * Display a listing of notification templates
     */
    public function index(Request $request)
    {
        $query = NotificationTemplate::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('trigger_event')) {
            $query->where('trigger_event', $request->trigger_event);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } else {
                $query->where('is_active', false);
            }
        }

        $templates = $query->latest()->paginate(20);
        $types = ['email', 'sms', 'whatsapp'];
        $triggerEvents = [
            'application_status_update',
            'interview_scheduled',
            'application_selected',
            'application_rejected',
            'batch_assigned',
            'training_started',
            'training_completed',
            'deployed'
        ];

        return view('job-portal.notification-templates.index', compact('templates', 'types', 'triggerEvents'));
    }

    /**
     * Show the form for creating a new template
     */
    public function create()
    {
        $types = ['email', 'sms', 'whatsapp'];
        $triggerEvents = [
            'application_status_update' => 'Application Status Update',
            'interview_scheduled' => 'Interview Scheduled',
            'application_selected' => 'Application Selected',
            'application_rejected' => 'Application Rejected',
            'batch_assigned' => 'Batch Assigned',
            'training_started' => 'Training Started',
            'training_completed' => 'Training Completed',
            'deployed' => 'Deployed'
        ];

        $variables = [
            'student_name' => 'Student Name',
            'application_number' => 'Application Number',
            'status' => 'Application Status',
            'message' => 'Custom Message',
            'date' => 'Current Date',
            'interview_date' => 'Interview Date',
            'venue' => 'Interview Venue',
            'batch_name' => 'Batch Name',
            'batch_code' => 'Batch Code',
            'start_date' => 'Training Start Date'
        ];

        return view('job-portal.notification-templates.create', compact('types', 'triggerEvents', 'variables'));
    }

    /**
     * Store a newly created template
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|in:email,sms,whatsapp',
            'trigger_event' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
            'variables' => 'nullable|array',
            'variables.*' => 'string|max:255',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        NotificationTemplate::create([
            'name' => $request->name,
            'type' => $request->type,
            'trigger_event' => $request->trigger_event,
            'subject' => $request->subject,
            'body' => $request->body,
            'variables' => $request->variables,
            'is_active' => $request->boolean('is_active', true),
            'created_by' => Auth::id()
        ]);

        return redirect()->route('job-portal.notification-templates.index')
            ->with('success', 'Notification template created successfully.');
    }

    /**
     * Show the form for editing the template
     */
    public function edit($id)
    {
        $template = NotificationTemplate::findOrFail($id);
        
        $types = ['email', 'sms', 'whatsapp'];
        $triggerEvents = [
            'application_status_update' => 'Application Status Update',
            'interview_scheduled' => 'Interview Scheduled',
            'application_selected' => 'Application Selected',
            'application_rejected' => 'Application Rejected',
            'batch_assigned' => 'Batch Assigned',
            'training_started' => 'Training Started',
            'training_completed' => 'Training Completed',
            'deployed' => 'Deployed'
        ];

        $variables = [
            'student_name' => 'Student Name',
            'application_number' => 'Application Number',
            'status' => 'Application Status',
            'message' => 'Custom Message',
            'date' => 'Current Date',
            'interview_date' => 'Interview Date',
            'venue' => 'Interview Venue',
            'batch_name' => 'Batch Name',
            'batch_code' => 'Batch Code',
            'start_date' => 'Training Start Date'
        ];

        return view('job-portal.notification-templates.edit', compact('template', 'types', 'triggerEvents', 'variables'));
    }

    /**
     * Update the specified template
     */
    public function update(Request $request, $id)
    {
        $template = NotificationTemplate::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|in:email,sms,whatsapp',
            'trigger_event' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
            'variables' => 'nullable|array',
            'variables.*' => 'string|max:255',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $template->update([
            'name' => $request->name,
            'type' => $request->type,
            'trigger_event' => $request->trigger_event,
            'subject' => $request->subject,
            'body' => $request->body,
            'variables' => $request->variables,
            'is_active' => $request->boolean('is_active', true)
        ]);

        return redirect()->route('job-portal.notification-templates.index')
            ->with('success', 'Notification template updated successfully.');
    }
}