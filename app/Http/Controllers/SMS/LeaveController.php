<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\SMS\Leave;
use App\Models\SMS\LeaveType;
use App\Models\SMS\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display leave types management
     */
    public function leaveTypes()
    {
        $leaveTypes = LeaveType::paginate(20);
        return view('sms.leaves.leave-types', compact('leaveTypes'));
    }

    /**
     * Store new leave type
     */
    public function storeLeaveType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sms_leave_types,name',
            'description' => 'nullable|string',
            'max_days_per_year' => 'required|integer|min:1',
            'requires_approval' => 'boolean',
        ]);

        LeaveType::create([
            'name' => $request->name,
            'description' => $request->description,
            'max_days_per_year' => $request->max_days_per_year,
            'requires_approval' => $request->has('requires_approval'),
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Leave type created successfully.');
    }

    /**
     * Show create leave application form
     */
    public function createLeaveApplication()
    {
        $students = Student::active()->get();
        $leaveTypes = LeaveType::active()->get();
        return view('sms.leaves.create-application', compact('students', 'leaveTypes'));
    }

    /**
     * Store new leave application
     */
    public function storeLeaveApplication(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:sms_students,id',
            'leave_type_id' => 'required|exists:sms_leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'leave_reasons' => 'required|string',
        ]);

        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        Leave::create([
            'student_id' => $request->student_id,
            'leave_type_id' => $request->leave_type_id,
            'applied_on' => now(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_days' => $totalDays,
            'leave_reasons' => $request->leave_reasons,
            'status' => 'pending',
        ]);

        return redirect()->route('sms.leaves.index')->with('success', 'Leave application submitted successfully.');
    }

    /**
     * Approve leave application
     */
    public function approveLeave(Request $request, $id)
    {
        $leave = Leave::findOrFail($id);
        
        $leave->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Leave application approved successfully.');
    }

    /**
     * Reject leave application
     */
    public function rejectLeave(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $leave = Leave::findOrFail($id);
        
        $leave->update([
            'status' => 'rejected',
            'rejected_by' => Auth::id(),
            'rejected_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->back()->with('success', 'Leave application rejected.');
    }

    /**
     * Show edit leave type form
     */
    public function editLeaveType($id)
    {
        $leaveType = LeaveType::findOrFail($id);
        return view('sms.leaves.edit-leave-type', compact('leaveType'));
    }

    /**
     * Update leave type
     */
    public function updateLeaveType(Request $request, $id)
    {
        $leaveType = LeaveType::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:sms_leave_types,name,' . $id,
            'description' => 'nullable|string',
            'max_days_per_year' => 'required|integer|min:1',
            'requires_approval' => 'boolean',
        ]);

        $leaveType->update([
            'name' => $request->name,
            'description' => $request->description,
            'max_days_per_year' => $request->max_days_per_year,
            'requires_approval' => $request->has('requires_approval'),
        ]);

        return redirect()->route('sms.leave-types.index')->with('success', 'Leave type updated successfully.');
    }

    /**
     * Delete leave type
     */
    public function deleteLeaveType($id)
    {
        $leaveType = LeaveType::findOrFail($id);
        
        // Check if any leaves are using this type
        $leaveCount = Leave::where('leave_type_id', $id)->count();
        if ($leaveCount > 0) {
            return redirect()->back()->with('error', 'Cannot delete leave type. It is being used by ' . $leaveCount . ' leave application(s).');
        }

        $leaveType->delete();

        return redirect()->route('sms.leave-types.index')->with('success', 'Leave type deleted successfully.');
    }

    /**
     * Show leave application details
     */
    public function showLeaveApplication($id)
    {
        $leave = Leave::with(['student', 'leaveType', 'approvedBy', 'rejectedBy'])->findOrFail($id);
        return view('sms.leaves.show-application', compact('leave'));
    }
}