@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    
    <div class="main-content">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</h1>
                <p class="text-gray-600">Role details and permissions</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Role
                </a>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Roles
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Role Information -->
            <div class="lg:col-span-1">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Role Information</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Role Name</label>
                                <p class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</p>
                                <p class="text-xs text-gray-500">{{ $role->name }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                                <p class="text-sm text-gray-900">{{ $role->created_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                                <p class="text-sm text-gray-900">{{ $role->updated_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Users with this Role</label>
                                <p class="text-sm font-bold text-blue-600">{{ $role->users->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permissions -->
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Permissions ({{ $role->permissions->count() }})</h3>
                        
                        @if($role->permissions->count() > 0)
                            <div class="space-y-4">
                                @foreach($role->permissions->groupBy('group') as $group => $permissions)
                                    <div>
                                        <h4 class="text-md font-medium text-gray-800 mb-2">{{ ucfirst(str_replace('_', ' ', $group)) }}</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                            @foreach($permissions as $permission)
                                                <div class="flex items-center p-2 bg-gray-50 rounded-lg">
                                                    <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    <span class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $permission->name)) }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No permissions</h3>
                                <p class="mt-1 text-sm text-gray-500">This role doesn't have any permissions assigned.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Users with this Role -->
        @if($role->users->count() > 0)
            <div class="mt-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Users with this Role ({{ $role->users->count() }})</h3>
                        
                        <div class="table-container">
                            <table class="table">
                                <thead class="table-header">
                                    <tr>
                                        <th class="table-cell">User</th>
                                        <th class="table-cell">Email</th>
                                        <th class="table-cell">Branch</th>
                                        <th class="table-cell">Status</th>
                                        <th class="table-cell">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($role->users as $user)
                                        <tr class="table-row">
                                            <td class="table-cell">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8">
                                                        @if($user->profile_image)
                                                            <img class="h-8 w-8 rounded-full" src="{{ $user->profile_image_url }}" alt="{{ $user->full_name }}">
                                                        @else
                                                            <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                                                <span class="text-xs font-medium text-gray-700">
                                                                    {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-gray-900">{{ $user->full_name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="table-cell">
                                                <span class="text-sm text-gray-900">{{ $user->email }}</span>
                                            </td>
                                            <td class="table-cell">
                                                <span class="text-sm text-gray-900">{{ $user->branch->name ?? 'N/A' }}</span>
                                            </td>
                                            <td class="table-cell">
                                                <span class="badge badge-{{ $user->status === 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($user->status) }}
                                                </span>
                                            </td>
                                            <td class="table-cell">
                                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-secondary">
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
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
