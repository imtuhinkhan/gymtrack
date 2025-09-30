@extends('layouts.dashboard')

@section('title', 'Trainer Details')
@section('page-title', 'Trainer Details')

@section('sidebar')
    @include('admin.partials.sidebar')
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">{{ $trainer->first_name }} {{ $trainer->last_name }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.trainers.edit', $trainer->id) }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Trainer
            </a>
            <a href="{{ route('admin.trainers.index') }}" class="btn btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Trainers
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Trainer Information -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">Trainer Information</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                            <p class="text-sm text-gray-900">{{ $trainer->first_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                            <p class="text-sm text-gray-900">{{ $trainer->last_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <p class="text-sm text-gray-900">{{ $trainer->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <p class="text-sm text-gray-900">{{ $trainer->phone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                            <p class="text-sm text-gray-900">{{ $trainer->date_of_birth ? \Carbon\Carbon::parse($trainer->date_of_birth)->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                            <p class="text-sm text-gray-900">{{ ucfirst($trainer->gender ?? 'N/A') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Branch</label>
                            <p class="text-sm text-gray-900">{{ $trainer->branch->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Experience</label>
                            <p class="text-sm text-gray-900">{{ $trainer->experience_years ?? 0 }} years</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Hourly Rate</label>
                            <p class="text-sm text-gray-900">${{ number_format($trainer->hourly_rate ?? 0, 2) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <span class="badge badge-{{ $trainer->status == 'active' ? 'success' : ($trainer->status == 'inactive' ? 'secondary' : 'warning') }}">
                                {{ ucfirst(str_replace('_', ' ', $trainer->status)) }}
                            </span>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Specializations</label>
                            <p class="text-sm text-gray-900">{{ is_array($trainer->specializations) ? implode(', ', $trainer->specializations) : ($trainer->specializations ?? 'N/A') }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <p class="text-sm text-gray-900">{{ $trainer->address ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Joined Date</label>
                            <p class="text-sm text-gray-900">{{ $trainer->created_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                            <p class="text-sm text-gray-900">{{ $trainer->updated_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assigned Members -->
            <div class="card mt-6">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">Assigned Members</h3>
                </div>
                <div class="card-body">
                    @if($trainer->customers && $trainer->customers->count() > 0)
                        <div class="space-y-3">
                            @foreach($trainer->customers->take(10) as $customer)
                                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-700">
                                            {{ substr($customer->first_name ?? 'N/A', 0, 2) }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $customer->first_name }} {{ $customer->last_name }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ $customer->email }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span class="badge badge-{{ $customer->status == 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($customer->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($trainer->customers->count() > 10)
                            <p class="text-sm text-gray-500 text-center mt-3">
                                And {{ $trainer->customers->count() - 10 }} more members...
                            </p>
                        @endif
                    @else
                        <p class="text-sm text-gray-500 text-center py-4">No members assigned to this trainer.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <a href="{{ route('admin.trainers.edit', $trainer->id) }}" class="btn btn-primary w-full">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Trainer
                        </a>
                        
                        <form method="POST" action="{{ route('admin.trainers.toggle-status', $trainer->id) }}" class="inline-block w-full">
                            @csrf
                            <button type="submit" class="btn btn-{{ $trainer->status == 'active' ? 'warning' : 'success' }} w-full">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                </svg>
                                {{ $trainer->status == 'active' ? 'Deactivate' : 'Activate' }} Trainer
                            </button>
                        </form>
                        
                        <form method="POST" action="{{ route('admin.trainers.destroy', $trainer->id) }}" 
                              onsubmit="return confirm('Are you sure you want to delete this trainer? This action cannot be undone.')" 
                              class="inline-block w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-full">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Trainer
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">Statistics</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Total Members</span>
                            <span class="text-sm font-bold text-gray-900">{{ $trainer->customers ? $trainer->customers->count() : 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Active Members</span>
                            <span class="text-sm font-bold text-green-600">{{ $trainer->customers ? $trainer->customers->where('status', 'active')->count() : 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Experience</span>
                            <span class="text-sm font-bold text-gray-900">{{ $trainer->experience_years ?? 0 }} years</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">Trainer Created</p>
                                <p class="text-xs text-gray-500">{{ $trainer->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        
                        @if($trainer->updated_at != $trainer->created_at)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">Trainer Updated</p>
                                <p class="text-xs text-gray-500">{{ $trainer->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
