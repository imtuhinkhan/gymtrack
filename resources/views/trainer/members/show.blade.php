@extends('layouts.dashboard')

@section('title', 'Member Details')
@section('page-title', 'Member Details')

@section('sidebar')
    @include('trainer.partials.sidebar')
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">{{ $member->first_name }} {{ $member->last_name }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('trainer.members.index') }}" class="btn btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Members
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Member Information -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">Member Information</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                            <p class="text-sm text-gray-900">{{ $member->first_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                            <p class="text-sm text-gray-900">{{ $member->last_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <p class="text-sm text-gray-900">{{ $member->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <p class="text-sm text-gray-900">{{ $member->phone ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                            <p class="text-sm text-gray-900">{{ $member->date_of_birth ? $member->date_of_birth->format('M d, Y') : 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                            <p class="text-sm text-gray-900">{{ ucfirst($member->gender ?? 'Not specified') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <p class="text-sm text-gray-900">{{ $member->address ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                            <p class="text-sm text-gray-900">{{ $member->city ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
                            <p class="text-sm text-gray-900">{{ $member->state ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Zip Code</label>
                            <p class="text-sm text-gray-900">{{ $member->zip_code ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Emergency Contact</label>
                            <p class="text-sm text-gray-900">{{ $member->emergency_contact ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Emergency Phone</label>
                            <p class="text-sm text-gray-900">{{ $member->emergency_phone ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Membership Status</label>
                            <span class="badge badge-{{ $member->is_active ? 'success' : 'secondary' }}">
                                {{ $member->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Joined Date</label>
                            <p class="text-sm text-gray-900">{{ $member->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Workout Routines -->
            <div class="card mt-6">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">Workout Routines</h3>
                </div>
                <div class="card-body">
                    @if($member->workoutRoutines && $member->workoutRoutines->count() > 0)
                        <div class="space-y-4">
                            @foreach($member->workoutRoutines as $routine)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $routine->name }}</h4>
                                            <p class="text-sm text-gray-500 mt-1">{{ $routine->description ?? 'No description' }}</p>
                                            <div class="mt-2 flex space-x-4 text-xs text-gray-600">
                                                <span><strong>Duration:</strong> {{ $routine->estimated_duration_minutes }} min</span>
                                                <span><strong>Difficulty:</strong> {{ ucfirst($routine->difficulty_level) }}</span>
                                                <span><strong>Target:</strong> {{ ucfirst(str_replace('_', ' ', $routine->target_muscle_group)) }}</span>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('trainer.workout-routines.show', $routine->id) }}" class="btn btn-sm btn-secondary">
                                                View
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-4">No workout routines assigned to this member yet.</p>
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
                        <a href="{{ route('trainer.workout-routines.create') }}?member_id={{ $member->id }}" class="btn btn-primary w-full">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create Workout Routine
                        </a>
                        
                        <a href="{{ route('trainer.attendance.index') }}?member_id={{ $member->id }}" class="btn btn-secondary w-full">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            View Attendance
                        </a>
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
                            <span class="text-sm font-medium text-gray-700">Workout Routines</span>
                            <span class="text-sm font-bold text-gray-900">{{ $member->workoutRoutines ? $member->workoutRoutines->count() : 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Membership Duration</span>
                            <span class="text-sm font-bold text-gray-900">{{ $member->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Status</span>
                            <span class="text-sm font-bold text-gray-900">{{ $member->is_active ? 'Active' : 'Inactive' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
