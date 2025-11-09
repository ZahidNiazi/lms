<?php

namespace App\Http\Controllers;

use App\Models\Platoon;
use Illuminate\Http\Request;

class PlatoonController extends Controller
{
    public function index()
    {
        $platoons = Platoon::orderBy('id', 'desc')->get();
        return response()->json($platoons);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:platoons,name']);
        $platoon = Platoon::create(['name' => $request->name]);
        return response()->json(['success' => true, 'platoon' => $platoon]);
    }
    public function update(Request $request, Platoon $platoon)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $platoon->update(['name' => $request->name]);
        return response()->json(['success' => true, 'platoon' => $platoon]);
    }

    public function destroy($id)
    {
        $platoon = Platoon::findOrFail($id);
        $platoon->delete();
        return response()->json(['success' => true]);
    }
}