@extends('layouts.dashboard')

@section('content')
<div class="min-h-screen overflow-x-hidden">
    <div class="space-y-6 max-w-full">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Role Management</h1>
            <p class="text-gray-600">Manage user roles and their permissions</p>
        </div>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Create Role
        </a>
    </div>

        <!-- Roles Table -->
        <div class="card">
            <div class="card-body">
                @if($roles->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="table-header">
                                <tr>
                                    <th class="table-cell">Role Name</th>
                                    <th class="table-cell">Permissions</th>
                                    <th class="table-cell">Users</th>
                                    <th class="table-cell">Created</th>
                                    <th class="table-cell">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                    <tr class="table-row">
                                        <td class="table-cell">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</div>
                                                    <div class="text-sm text-gray-500">{{ $role->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="table-cell">
                                            <div class="flex flex-wrap gap-1">
                                                @if($role->permissions->count() > 0)
                                                    @foreach($role->permissions->take(3) as $permission)
                                                        <span class="badge badge-secondary text-xs">{{ ucfirst(str_replace('_', ' ', $permission->name)) }}</span>
                                                    @endforeach
                                                    @if($role->permissions->count() > 3)
                                                        <span class="badge badge-secondary text-xs">+{{ $role->permissions->count() - 3 }} more</span>
                                                    @endif
                                                @else
                                                    <span class="text-sm text-gray-500">No permissions</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="table-cell">
                                            <span class="text-sm font-medium text-gray-900">{{ $role->users->count() }}</span>
                                        </td>
                                        <td class="table-cell">
                                            <span class="text-sm text-gray-500">{{ $role->created_at->format('M d, Y') }}</span>
                                        </td>
                                        <td class="table-cell">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-sm btn-secondary">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-primary">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                                @can('delete_roles')
                                                    @if($role->users->count() > 0)
                                                        <button type="button" class="btn btn-sm btn-danger opacity-50 cursor-not-allowed" 
                                                                title="Cannot delete role with assigned users ({{ $role->users->count() }} users)">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    @else
                                                        <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="inline-block" 
                                                              onsubmit="return confirm('Are you sure you want to delete this role? This action cannot be undone.')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No roles</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new role.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create Role
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        </div>
    </div>
</div>
@endsection
