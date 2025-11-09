<?php

namespace App\Http\Controllers;

use App\Models\Atoll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AtollController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('manage system settings')) {
            return redirect()->back()->with('error', 'Permission denied.');
        }
        $atolls = Atoll::where('created_by', Auth::user()->creatorId())
            ->orderBy('name')
            ->get();
        return response()->json($atolls);
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('manage system settings')) {
            return redirect()->back()->with('error', 'Permission denied.');
        }
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Atoll::create([
            'name' => $data['name'],
            'created_by' => Auth::user()->creatorId(),
        ]);

        return redirect()->back()->with('success', __('Atoll created successfully.'));
    }

    public function update(Request $request, Atoll $atoll)
    {
        if (!Auth::user()->can('manage system settings')) {
            return redirect()->back()->with('error', 'Permission denied.');
        }
        abort_unless($atoll->created_by == Auth::user()->creatorId(), 403);

        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $atoll->update(['name' => $data['name']]);

        return redirect()->back()->with('success', __('Atoll updated successfully.'));
    }

    public function destroy(Atoll $atoll)
    {
        if (!Auth::user()->can('manage system settings')) {
            return redirect()->back()->with('error', 'Permission denied.');
        }
        abort_unless($atoll->created_by == Auth::user()->creatorId(), 403);
        $atoll->delete();
        return redirect()->back()->with('success', __('Atoll deleted successfully.'));
    }
}
