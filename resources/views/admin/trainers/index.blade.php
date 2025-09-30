@extends('layouts.dashboard')

@section('content')
<div class="min-h-screen overflow-x-hidden">
    <div class="space-y-6 max-w-full">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Trainers Management</h1>
        @can('create_trainers')
        <a href="{{ route('admin.trainers.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Trainer
        </a>
        @endcan
    </div>

    <!-- Filters -->
    <div class="card mb-6">
        <div class="card-body">
            <form method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-0">
                    <input type="text" name="search" placeholder="Search trainers..." 
                           value="{{ request('search') }}" 
                           class="form-input w-full">
                </div>
                <div>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
                <div>
                    <select name="branch_id" class="form-select">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-secondary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.trainers.index') }}" class="btn btn-outline">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Trainers Table -->
    <div class="card">
        <div class="card-body">
            @if($trainers->count() == 0)
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No trainers found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new trainer.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.trainers.create') }}" class="btn btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add New Trainer
                        </a>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="table-header">
                            <tr>
                                <th class="px-3 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Trainer</th>
                                <th class="px-3 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Branch</th>
                                <th class="px-3 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Specializations</th>
                                <th class="px-3 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Hourly Rate</th>
                                <th class="px-3 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-3 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Hired</th>
                                <th class="px-3 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($trainers as $trainer)
                                <tr class="table-row">
                                    <td class="px-3 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                @if($trainer->profile_image)
                                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('storage/' . $trainer->profile_image) }}" alt="{{ $trainer->first_name }}">
                                                @else
                                                    <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                                        <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $trainer->first_name }} {{ $trainer->last_name }}</div>
                                                <div class="text-sm text-gray-500">{{ $trainer->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-900">
                                        {{ $trainer->branch->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-900">
                                        {{ is_array($trainer->specializations) ? implode(', ', $trainer->specializations) : $trainer->specializations }}
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-900">
                                        ${{ number_format($trainer->hourly_rate ?? 0, 2) }}/hr
                                    </td>
                                    <td class="px-3 py-4">
                                        <span class="badge badge-{{ $trainer->status === 'active' ? 'success' : ($trainer->status === 'inactive' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($trainer->status) }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-500">
                                        {{ $trainer->hire_date ? \Carbon\Carbon::parse($trainer->hire_date)->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td class="px-3 py-4 text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('admin.trainers.show', $trainer->id) }}" class="btn btn-sm btn-secondary">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            @can('edit_trainers')
                                            <a href="{{ route('admin.trainers.edit', $trainer->id) }}" class="btn btn-sm btn-primary">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            @endcan
                                            @can('edit_trainers')
                                            <form method="POST" action="{{ route('admin.trainers.toggle-status', $trainer->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-{{ $trainer->status === 'active' ? 'warning' : 'success' }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                            @endcan
                                            @can('delete_trainers')
                                            <form method="POST" action="{{ route('admin.trainers.destroy', $trainer->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this trainer?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination would go here if implemented -->
                <!-- Pagination -->
                <div class="mt-6">
                    {{ $trainers->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
        </div>
    </div>
</div>
@endsection
