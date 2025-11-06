<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::orderBy('id', 'desc')->get();
        return response()->json($companies);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:companies,name']);
        $company = Company::create(['name' => $request->name]);
        return response()->json(['success' => true, 'company' => $company]);
    }
    public function update(Request $request, Company $company)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $company->update(['name' => $request->name]);
        return response()->json(['success' => true, 'company' => $company]);
    }

    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();
        return response()->json(['success' => true]);
    }
}
