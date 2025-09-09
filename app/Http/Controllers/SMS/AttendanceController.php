<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\SMS\Attendance;
use App\Models\SMS\Student;
use App\Models\SMS\TrainingBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show monthly attendance view
     */
    public function monthlyView(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $batchId = $request->get('batch_id');
        
        $query = Student::query();
        if ($batchId) {
            $query->where('batch_id', $batchId);
        }
        
        $students = $query->with('batch')->get();
        $batches = TrainingBatch::all();
        
        // Get attendance records for the month
        $startDate = \Carbon\Carbon::parse($month . '-01');
        $endDate = $startDate->copy()->endOfMonth();
        
        $attendances = Attendance::whereBetween('date', [$startDate, $endDate])
            ->when($batchId, function($q) use ($batchId) {
                $q->whereHas('student', function($studentQuery) use ($batchId) {
                    $studentQuery->where('batch_id', $batchId);
                });
            })
            ->get()
            ->groupBy(['student_id', 'date']);
        
        return view('sms.attendance.monthly', compact('students', 'batches', 'attendances', 'month', 'batchId'));
    }

    /**
     * Show mark attendance form
     */
    public function markAttendance(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        $batchId = $request->get('batch_id');
        
        $query = Student::where('is_active', true);
        if ($batchId) {
            $query->where('batch_id', $batchId);
        }
        
        $students = $query->with('batch')->get();
        $batches = TrainingBatch::all();
        
        // Get existing attendance for the date
        $existingAttendance = Attendance::where('date', $date)
            ->when($batchId, function($q) use ($batchId) {
                $q->whereHas('student', function($studentQuery) use ($batchId) {
                    $studentQuery->where('batch_id', $batchId);
                });
            })
            ->get()
            ->keyBy('student_id');
        
        return view('sms.attendance.mark', compact('students', 'batches', 'existingAttendance', 'date', 'batchId'));
    }

    /**
     * Store attendance records
     */
    public function storeAttendance(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:sms_students,id',
            'attendance.*.status' => 'required|in:present,absent,late,leave,medical_excuse,official_leave',
            'attendance.*.check_in_time' => 'nullable|date_format:H:i',
            'attendance.*.check_out_time' => 'nullable|date_format:H:i',
            'attendance.*.reasons' => 'nullable|string',
        ]);

        $date = $request->date;
        $markedBy = Auth::id();

        foreach ($request->attendance as $attendanceData) {
            $existingAttendance = Attendance::where('student_id', $attendanceData['student_id'])
                ->where('date', $date)
                ->first();

            $data = [
                'student_id' => $attendanceData['student_id'],
                'date' => $date,
                'status' => $attendanceData['status'],
                'check_in_time' => $attendanceData['check_in_time'] ? $date . ' ' . $attendanceData['check_in_time'] : null,
                'check_out_time' => $attendanceData['check_out_time'] ? $date . ' ' . $attendanceData['check_out_time'] : null,
                'reasons' => $attendanceData['reasons'] ?? null,
                'marked_by' => $markedBy,
            ];

            if ($existingAttendance) {
                $existingAttendance->update($data);
            } else {
                Attendance::create($data);
            }
        }

        return redirect()->route('sms.attendance.index')
            ->with('success', 'Attendance marked successfully for ' . \Carbon\Carbon::parse($date)->format('M d, Y'));
    }
}
