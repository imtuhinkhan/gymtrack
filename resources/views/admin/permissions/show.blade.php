@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    
    <div class="main-content">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ ucfirst(str_replace('_', ' ', $permission->name)) }}</h1>
                <p class="text-gray-600">Permission details and role assignments</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Permission
                </a>
                <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Permissions
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Permission Information -->
            <div class="lg:col-span-1">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Permission Information</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Permission Name</label>
                                <p class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $permission->name)) }}</p>
                                <p class="text-xs text-gray-500">{{ $permission->name }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Group</label>
                                <span class="badge badge-secondary">{{ ucfirst(str_replace('_', ' ', $permission->group)) }}</span>
                            </div>
                            
                            @if($permission->description)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                    <p class="text-sm text-gray-900">{{ $permission->description }}</p>
                                </div>
                            @endif
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                                <p class="text-sm text-gray-900">{{ $permission->created_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                                <p class="text-sm text-gray-900">{{ $permission->updated_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Roles with this Permission</label>
                                <p class="text-sm font-bold text-blue-600">{{ $permission->roles->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Roles with this Permission -->
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Roles with this Permission ({{ $permission->roles->count() }})</h3>
                        
                        @if($permission->roles->count() > 0)
                            <div class="table-container">
                                <table class="table">
                                    <thead class="table-header">
                                        <tr>
                                            <th class="table-cell">Role Name</th>
                                            <th class="table-cell">Permissions</th>
                                            <th class="table-cell">Users</th>
                                            <th class="table-cell">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($permission->roles as $role)
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
                                                    <span class="text-sm font-medium text-gray-900">{{ $role->permissions->count() }}</span>
                                                </td>
                                                <td class="table-cell">
                                                    <span class="text-sm font-medium text-gray-900">{{ $role->users->count() }}</span>
                                                </td>
                                                <td class="table-cell">
                                                    <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-sm btn-secondary">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No roles assigned</h3>
                                <p class="mt-1 text-sm text-gray-500">This permission is not assigned to any roles yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
