<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        return view('users-management.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users-management.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required'
        ]);

        switch ($request->role) {
            
            case 'super admin':
                $type = 'super admin';
                break;
            case 'Company':
                $type = 'company';
                break;
            case 'Accountant':
                $type = 'company';
                break;
            case 'Employee':
                $type = 'accountant';
                break;
            case 'Client':
                $type = 'client';
                break;
            case 'HR':
                $type = 'hr-author';
                break;
            default:
                $type = null;
                break;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $type,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users-management.index')->with('success', 'User created successfully with assigned role.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $permissions = Permission::all();
        return view('users-management.edit', compact('user', 'roles', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update role
        $user->syncRoles($request->role);

        // Update direct permissions
        $user->syncPermissions($request->permissions ?? []);

        return redirect()->route('users-management.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->syncRoles([]);
        $user->syncPermissions([]);
        return redirect()->route('users-management.index')->with('success', 'All roles and permissions cleared.');
    }
}
