<?php

namespace App\Http\Controllers;

use App\Models\Island;
use App\Models\Atoll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IslandController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('manage system settings')) {
            return redirect()->back()->with('error', 'Permission denied.');
        }
        $islands = Island::with('atoll')
            ->where('created_by', Auth::user()->creatorId())
            ->orderBy('name')
            ->get();
        return response()->json($islands);
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('manage system settings')) {
            return redirect()->back()->with('error', 'Permission denied.');
        }
        $data = $request->validate([
            'atoll_id' => 'required|exists:atolls,id',
            'name' => 'required|string|max:255',
        ]);

        // Ensure atoll belongs to same creator
        $atoll = Atoll::where('id', $data['atoll_id'])
            ->where('created_by', Auth::user()->creatorId())
            ->firstOrFail();

        Island::create([
            'atoll_id' => $atoll->id,
            'name' => $data['name'],
            'created_by' => Auth::user()->creatorId(),
        ]);

        return redirect()->back()->with('success', __('Island created successfully.'));
    }

    public function update(Request $request, Island $island)
    {
        if (!Auth::user()->can('manage system settings')) {
            return redirect()->back()->with('error', 'Permission denied.');
        }
        abort_unless($island->created_by == Auth::user()->creatorId(), 403);

        $data = $request->validate([
            'atoll_id' => 'required|exists:atolls,id',
            'name' => 'required|string|max:255',
        ]);

        // Ensure atoll belongs to same creator
        $atoll = Atoll::where('id', $data['atoll_id'])
            ->where('created_by', Auth::user()->creatorId())
            ->firstOrFail();

        $island->update([
            'name' => $data['name'],
            'atoll_id' => $atoll->id,
        ]);

        return redirect()->back()->with('success', __('Island updated successfully.'));
    }

    public function destroy(Island $island)
    {
        if (!Auth::user()->can('manage system settings')) {
            return redirect()->back()->with('error', 'Permission denied.');
        }
        abort_unless($island->created_by == Auth::user()->creatorId(), 403);
        $island->delete();
        return redirect()->back()->with('success', __('Island deleted successfully.'));
    }
}
