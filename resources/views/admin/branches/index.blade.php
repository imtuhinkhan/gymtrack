@extends('layouts.dashboard')

@section('content')
<div class="min-h-screen overflow-x-hidden">
    <div class="space-y-6 max-w-full">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Branches Management</h1>
        <a href="{{ route('admin.branches.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Branch
        </a>
    </div>

    <!-- Branches Table -->
    <div class="card">
        <div class="card-body">
            @if($branches->count() == 0)
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No branches found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new branch.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.branches.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Branch
        </a>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="table-header">
                            <tr>
                                <th class="px-3 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Branch</th>
                                <th class="px-3 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Location</th>
                                <th class="px-3 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Manager</th>
                                <th class="px-3 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Hours</th>
                                <th class="px-3 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-3 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($branches as $branch)
                                <tr class="table-row">
                                    <td class="px-3 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                @if($branch->logo)
                                                    <img class="h-8 w-8 rounded-full" src="{{ Storage::url($branch->logo) }}" alt="{{ $branch->name }}">
                                                @else
                                                    <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                                        <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $branch->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $branch->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-900">
                                        {{ $branch->city }}, {{ $branch->state }}
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-900">
                                        {{ $branch->manager_name ?? 'Not Assigned' }}
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($branch->opening_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($branch->closing_time)->format('g:i A') }}
                                    </td>
                                    <td class="px-3 py-4">
                                        <span class="badge badge-{{ $branch->is_active ? 'success' : 'warning' }}">
                                            {{ $branch->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-4 text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('admin.branches.show', $branch->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                            <a href="{{ route('admin.branches.edit', $branch->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <form method="POST" action="{{ route('admin.branches.destroy', $branch->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this branch?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $branches->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
        </div>
    </div>
</div>
@endsection