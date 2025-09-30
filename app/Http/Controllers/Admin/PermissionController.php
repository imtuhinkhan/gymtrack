<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::all()->groupBy('group');
        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups = [
            'branches' => 'Branch Management',
            'members' => 'Member Management',
            'trainers' => 'Trainer Management',
            'packages' => 'Package Management',
            'subscriptions' => 'Subscription Management',
            'payments' => 'Payment Management',
            'attendance' => 'Attendance Management',
            'workout_routines' => 'Workout Routine Management',
            'notices' => 'Notice Management',
            'gallery' => 'Gallery Management',
            'inquiries' => 'Inquiry Management',
            'settings' => 'Settings Management',
            'roles' => 'Role Management',
            'permissions' => 'Permission Management',
            'users' => 'User Management',
            'reports' => 'Reports Management'
        ];
        
        return view('admin.permissions.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'group' => 'required|string|max:255',
            'description' => 'nullable|string|max:500'
        ]);

        Permission::create([
            'name' => $request->name,
            'group' => $request->group,
            'description' => $request->description
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        $roles = $permission->roles;
        return view('admin.permissions.show', compact('permission', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        $groups = [
            'branches' => 'Branch Management',
            'members' => 'Member Management',
            'trainers' => 'Trainer Management',
            'packages' => 'Package Management',
            'subscriptions' => 'Subscription Management',
            'payments' => 'Payment Management',
            'attendance' => 'Attendance Management',
            'workout_routines' => 'Workout Routine Management',
            'notices' => 'Notice Management',
            'gallery' => 'Gallery Management',
            'inquiries' => 'Inquiry Management',
            'settings' => 'Settings Management',
            'roles' => 'Role Management',
            'permissions' => 'Permission Management',
            'users' => 'User Management',
            'reports' => 'Reports Management'
        ];
        
        return view('admin.permissions.edit', compact('permission', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'group' => 'required|string|max:255',
            'description' => 'nullable|string|max:500'
        ]);

        $permission->update([
            'name' => $request->name,
            'group' => $request->group,
            'description' => $request->description
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        if ($permission->roles()->count() > 0) {
            return redirect()->route('admin.permissions.index')
                ->with('error', 'Cannot delete permission. It is assigned to roles.');
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }
}
