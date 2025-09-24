<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\SMS\PerformanceField;
use App\Models\SMS\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerformanceFieldController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display performance fields management
     */
    public function index(Request $request)
    {
        $query = PerformanceField::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $performanceFields = $query->latest()->paginate(20);
        $categories = PerformanceField::distinct()->pluck('category')->filter();
        $students = Student::active()->with('batch')->get();

        return view('sms.performance.fields.index', compact('performanceFields', 'categories', 'students'));
    }

    /**
     * Show create performance field form
     */
    public function create()
    {
        $categories = [
            'Technical Skills',
            'Soft Skills',
            'Physical Fitness',
            'Leadership',
            'Communication',
            'Problem Solving',
            'Teamwork',
            'Discipline',
            'Attendance',
            'Other'
        ];
        $students = Student::active()->with('batch')->get();

        return view('sms.performance.fields.create', compact('categories', 'students'));
    }

    /**
     * Store new performance field
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sms_performance_fields,name',
            'description' => 'nullable|string|max:1000',
            'max_score' => 'required|numeric|min:1|max:1000',
            'category' => 'required|string|max:255',
            'is_active' => 'boolean'
        ]);

        PerformanceField::create([
            'name' => $request->name,
            'description' => $request->description,
            'max_score' => $request->max_score,
            'category' => $request->category,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('sms.performance.fields.index')
            ->with('success', 'Performance field created successfully.');
    }

    /**
     * Show edit performance field form
     */
    public function edit($id)
    {
        $performanceField = PerformanceField::findOrFail($id);
        $categories = [
            'Technical Skills',
            'Soft Skills',
            'Physical Fitness',
            'Leadership',
            'Communication',
            'Problem Solving',
            'Teamwork',
            'Discipline',
            'Attendance',
            'Other'
        ];
        $students = Student::active()->with('batch')->get();

        return view('sms.performance.fields.edit', compact('performanceField', 'categories', 'students'));
    }

    /**
     * Update performance field
     */
    public function update(Request $request, $id)
    {
        $performanceField = PerformanceField::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:sms_performance_fields,name,' . $id,
            'description' => 'nullable|string|max:1000',
            'max_score' => 'required|numeric|min:1|max:1000',
            'category' => 'required|string|max:255',
            'is_active' => 'boolean'
        ]);

        $performanceField->update([
            'name' => $request->name,
            'description' => $request->description,
            'max_score' => $request->max_score,
            'category' => $request->category,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('sms.performance.fields.index')
            ->with('success', 'Performance field updated successfully.');
    }

    /**
     * Delete performance field
     */
    public function destroy($id)
    {
        $performanceField = PerformanceField::findOrFail($id);

        // Check if field is being used in any performance records
        if ($performanceField->performances()->count() > 0) {
            return back()->with('error', 'Cannot delete performance field that has associated performance records.');
        }

        $performanceField->delete();

        return redirect()->route('sms.performance.fields.index')
            ->with('success', 'Performance field deleted successfully.');
    }

    /**
     * Toggle performance field status
     */
    public function toggleStatus($id)
    {
        $performanceField = PerformanceField::findOrFail($id);
        $performanceField->update(['is_active' => !$performanceField->is_active]);

        $status = $performanceField->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Performance field {$status} successfully.");
    }
}
