<?php

namespace App\Http\Controllers;

use App\Models\InterviewLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InterviewLocationController extends Controller
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
     * Display a listing of interview locations
     */
    public function index(Request $request)
    {
        $query = InterviewLocation::query();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('city')) {
            $query->byCity($request->city);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } else {
                $query->inactive();
            }
        }

        $locations = $query->latest()->paginate(20);
        $cities = InterviewLocation::distinct()->pluck('city')->filter();

        return view('job-portal.interview-locations.index', compact('locations', 'cities'));
    }

    /**
     * Show the form for creating a new location
     */
    public function create()
    {
        return view('job-portal.interview-locations.create');
    }

    /**
     * Store a newly created location
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'atoll' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'capacity' => 'required|integer|min:1',
            'available_facilities' => 'nullable|array',
            'available_facilities.*' => 'string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        InterviewLocation::create([
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'atoll' => $request->atoll,
            'contact_person' => $request->contact_person,
            'contact_phone' => $request->contact_phone,
            'contact_email' => $request->contact_email,
            'capacity' => $request->capacity,
            'available_facilities' => $request->available_facilities,
            'created_by' => Auth::id()
        ]);

        return redirect()->route('job-portal.interview-locations.index')
            ->with('success', 'Interview location created successfully.');
    }

    /**
     * Show the form for editing the location
     */
    public function edit($id)
    {
        $location = InterviewLocation::findOrFail($id);
        return view('job-portal.interview-locations.edit', compact('location'));
    }

    /**
     * Update the specified location
     */
    public function update(Request $request, $id)
    {
        $location = InterviewLocation::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'atoll' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'capacity' => 'required|integer|min:1',
            'available_facilities' => 'nullable|array',
            'available_facilities.*' => 'string|max:255',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $location->update([
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'atoll' => $request->atoll,
            'contact_person' => $request->contact_person,
            'contact_phone' => $request->contact_phone,
            'contact_email' => $request->contact_email,
            'capacity' => $request->capacity,
            'available_facilities' => $request->available_facilities,
            'is_active' => $request->boolean('is_active', true)
        ]);

        return redirect()->route('job-portal.interview-locations.index')
            ->with('success', 'Interview location updated successfully.');
    }
}