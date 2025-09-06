<?php

namespace App\Http\Controllers;

use App\Models\TrainingBatch;
use App\Models\JobPortalApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TrainingBatchController extends Controller
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
     * Display a listing of training batches
     */
    public function index()
    {
        $batches = TrainingBatch::withCount(['applications' => function($query) {
            $query->where('status', 'batch_assigned');
        }])->latest()->paginate(20);

        return view('job-portal.batches.index', compact('batches'));
    }

    /**
     * Show the form for creating a new batch
     */
    public function create()
    {
        return view('job-portal.batches.create');
    }

    /**
     * Store a newly created batch
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'batch_name' => 'required|string|max:255',
            'batch_code' => 'required|string|max:255|unique:training_batches',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'capacity' => 'required|integer|min:1|max:500',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $batch = TrainingBatch::create([
            'batch_name' => $request->batch_name,
            'batch_code' => $request->batch_code,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'capacity' => $request->capacity,
            'reserve_capacity' => ceil($request->capacity * 0.15), // 15% reserve
            'description' => $request->description,
            'created_by' => Auth::id(),
            'status' => 'planning'
        ]);

        return redirect()->route('job-portal.batches.index')
            ->with('success', 'Training batch created successfully.');
    }

    /**
     * Display the specified batch
     */
    public function show($id)
    {
        $batch = TrainingBatch::with(['applications.student.profile'])->findOrFail($id);
        $applications = $batch->applications()->with(['student.profile', 'student.addresses'])->paginate(20);
        
        return view('job-portal.batches.show', compact('batch', 'applications'));
    }

    /**
     * Show the form for editing the batch
     */
    public function edit($id)
    {
        $batch = TrainingBatch::findOrFail($id);
        return view('job-portal.batches.edit', compact('batch'));
    }

    /**
     * Update the specified batch
     */
    public function update(Request $request, $id)
    {
        $batch = TrainingBatch::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'batch_name' => 'required|string|max:255',
            'batch_code' => 'required|string|max:255|unique:training_batches,batch_code,' . $id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'capacity' => 'required|integer|min:1|max:500',
            'status' => 'required|in:planning,open,full,in_progress,completed,cancelled',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $batch->update([
            'batch_name' => $request->batch_name,
            'batch_code' => $request->batch_code,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'capacity' => $request->capacity,
            'reserve_capacity' => ceil($request->capacity * 0.15),
            'status' => $request->status,
            'description' => $request->description
        ]);

        return redirect()->route('job-portal.batches.index')
            ->with('success', 'Training batch updated successfully.');
    }
}