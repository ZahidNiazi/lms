<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\SMS\Performance;
use App\Models\SMS\PerformanceField;
use App\Models\SMS\PerformanceDocument;
use App\Models\SMS\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PerformanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display performance management dashboard
     */
    public function index(Request $request)
    {
        $query = Performance::with(['student', 'performanceField', 'evaluator']);

        // Apply filters
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('performance_field_id')) {
            $query->where('performance_field_id', $request->performance_field_id);
        }

        if ($request->filled('date_from')) {
            $query->where('evaluation_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('evaluation_date', '<=', $request->date_to);
        }

        if ($request->filled('grade')) {
            $query->whereRaw('(score / max_score * 100) >= ?', [$this->getGradeMinPercentage($request->grade)]);
        }

        $performances = $query->latest('evaluation_date')->paginate(20);
        $students = Student::active()->with('batch')->get();
        $performanceFields = PerformanceField::active()->get();

        // Performance statistics
        $stats = [
            'total_evaluations' => Performance::count(),
            'average_score' => Performance::avg(DB::raw('score / max_score * 100')),
            'excellent_performers' => Performance::whereRaw('(score / max_score * 100) >= 90')->count(),
            'needs_improvement' => Performance::whereRaw('(score / max_score * 100) < 60')->count(),
        ];

        return view('sms.performance.index', compact('performances', 'students', 'performanceFields', 'stats'));
    }

    /**
     * Show create performance form
     */
    public function create(Request $request)
    {
        $students = Student::active()->with('batch')->get();
        $performanceFields = PerformanceField::active()->get();
        $selectedStudent = $request->get('student_id') ? Student::with('batch')->find($request->get('student_id')) : null;

        return view('sms.performance.create', compact('students', 'performanceFields', 'selectedStudent'));
    }
    

    /**
     * Store new performance record
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:sms_students,id',
            'performance_field_id' => 'required|exists:sms_performance_fields,id',
            'score' => 'required|numeric|min:0',
            'max_score' => 'required|numeric|min:0|gte:score',
            'comments' => 'nullable|string|max:1000',
            'counselling_notes' => 'nullable|string|max:1000',
            'pay_step' => 'nullable|string|max:255',
            'performance_indicator' => 'nullable|string|max:255',
            'observation_notes' => 'nullable|string|max:1000',
            'evaluation_date' => 'required|date',
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240'
        ]);

        DB::beginTransaction();
        try {
            $performance = Performance::create([
                'student_id' => $request->student_id,
                'performance_field_id' => $request->performance_field_id,
                'score' => $request->score,
                'max_score' => $request->max_score,
                'comments' => $request->comments,
                'counselling_notes' => $request->counselling_notes,
                'pay_step' => $request->pay_step,
                'performance_indicator' => $request->performance_indicator,
                'observation_notes' => $request->observation_notes,
                'evaluation_date' => $request->evaluation_date,
                'evaluated_by' => Auth::id(),
            ]);

            // Handle document uploads
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('public/performance_documents', $fileName);

                    PerformanceDocument::create([
                        'performance_id' => $performance->id,
                        'document_name' => $file->getClientOriginalName(),
                        'file_path' => 'performance_documents/' . $fileName,
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'uploaded_by' => Auth::id(),
                        'upload_date' => now(),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('sms.performance.index')
                ->with('success', 'Performance record created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Failed to create performance record.');
        }
    }

    /**
     * Show performance details
     */
    public function show($id)
    {
        $performance = Performance::with([
            'student',
            'performanceField',
            'evaluator',
            'documents.uploader'
        ])->findOrFail($id);

        return view('sms.performance.show', compact('performance'));
    }

    /**
     * Show edit performance form
     */
    public function edit($id)
    {
        $performance = Performance::with(['documents'])->findOrFail($id);
        $students = Student::active()->with('batch')->get();
        $performanceFields = PerformanceField::active()->get();

        return view('sms.performance.edit', compact('performance', 'students', 'performanceFields'));
    }

    /**
     * Update performance record
     */
    public function update(Request $request, $id)
    {
        $performance = Performance::findOrFail($id);

        $request->validate([
            'student_id' => 'required|exists:sms_students,id',
            'performance_field_id' => 'required|exists:sms_performance_fields,id',
            'score' => 'required|numeric|min:0',
            'max_score' => 'required|numeric|min:0|gte:score',
            'comments' => 'nullable|string|max:1000',
            'counselling_notes' => 'nullable|string|max:1000',
            'pay_step' => 'nullable|string|max:255',
            'performance_indicator' => 'nullable|string|max:255',
            'observation_notes' => 'nullable|string|max:1000',
            'evaluation_date' => 'required|date',
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240'
        ]);

        DB::beginTransaction();
        try {
            $performance->update([
                'student_id' => $request->student_id,
                'performance_field_id' => $request->performance_field_id,
                'score' => $request->score,
                'max_score' => $request->max_score,
                'comments' => $request->comments,
                'counselling_notes' => $request->counselling_notes,
                'pay_step' => $request->pay_step,
                'performance_indicator' => $request->performance_indicator,
                'observation_notes' => $request->observation_notes,
                'evaluation_date' => $request->evaluation_date,
            ]);

            // Handle new document uploads
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('public/performance_documents', $fileName);

                    PerformanceDocument::create([
                        'performance_id' => $performance->id,
                        'document_name' => $file->getClientOriginalName(),
                        'file_path' => 'performance_documents/' . $fileName,
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'uploaded_by' => Auth::id(),
                        'upload_date' => now(),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('sms.performance.index')
                ->with('success', 'Performance record updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Failed to update performance record.');
        }
    }

    /**
     * Delete performance record
     */
    public function destroy($id)
    {
        $performance = Performance::with('documents')->findOrFail($id);

        DB::beginTransaction();
        try {
            // Delete associated documents
            foreach ($performance->documents as $document) {
                if (Storage::exists('public/' . $document->file_path)) {
                    Storage::delete('public/' . $document->file_path);
                }
                $document->delete();
            }

            $performance->delete();
            DB::commit();

            return redirect()->route('sms.performance.index')
                ->with('success', 'Performance record deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to delete performance record.');
        }
    }

    /**
     * Delete performance document
     */
    public function deleteDocument($id)
    {
        $document = PerformanceDocument::findOrFail($id);

        if (Storage::exists('public/' . $document->file_path)) {
            Storage::delete('public/' . $document->file_path);
        }

        $document->delete();

        return back()->with('success', 'Document deleted successfully.');
    }

    /**
     * Download performance document
     */
    public function downloadDocument($id)
    {
        $document = PerformanceDocument::findOrFail($id);

        if (!Storage::exists('public/' . $document->file_path)) {
            return back()->with('error', 'File not found.');
        }

        return Storage::download('public/' . $document->file_path, $document->document_name);
    }

    /**
     * Get student performance summary
     */
    public function studentPerformance($studentId)
    {
        $student = Student::with('batch')->findOrFail($studentId);
        $performances = Performance::with(['performanceField', 'evaluator'])
            ->where('student_id', $studentId)
            ->latest('evaluation_date')
            ->get();

        $performanceFields = PerformanceField::active()->get();
        $fieldStats = [];

        foreach ($performanceFields as $field) {
            $fieldPerformances = $performances->where('performance_field_id', $field->id);
            if ($fieldPerformances->count() > 0) {
                $fieldStats[] = [
                    'field' => $field,
                    'latest_score' => $fieldPerformances->first()->percentage,
                    'average_score' => $fieldPerformances->avg('percentage'),
                    'total_evaluations' => $fieldPerformances->count(),
                    'latest_date' => $fieldPerformances->first()->evaluation_date,
                ];
            }
        }

        return view('sms.performance.student-summary', compact('student', 'performances', 'fieldStats'));
    }

    /**
     * Helper method to get minimum percentage for grade
     */
    private function getGradeMinPercentage($grade)
    {
        return match($grade) {
            'A+' => 90,
            'A' => 80,
            'B+' => 70,
            'B' => 60,
            'C+' => 50,
            'C' => 40,
            'D' => 30,
            'F' => 0,
            default => 0
        };
    }
}
