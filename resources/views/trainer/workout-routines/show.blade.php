@extends('layouts.dashboard')

@section('title', 'Workout Routine Details')
@section('page-title', 'Workout Routine Details')

@section('sidebar')
    @include('trainer.partials.sidebar')
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">{{ $workoutRoutine->name }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('trainer.workout-routines.edit', $workoutRoutine->id) }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Routine
            </a>
            <a href="{{ route('trainer.workout-routines.index') }}" class="btn btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Routines
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Workout Routine Information -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">Workout Routine Information</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Routine Name</label>
                            <p class="text-sm text-gray-900">{{ $workoutRoutine->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Assigned Member</label>
                            <p class="text-sm text-gray-900">{{ $workoutRoutine->member->first_name ?? 'N/A' }} {{ $workoutRoutine->member->last_name ?? '' }}</p>
                            <p class="text-xs text-gray-500">{{ $workoutRoutine->member->email ?? 'No email' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                            <p class="text-sm text-gray-900">{{ $workoutRoutine->estimated_duration_minutes }} minutes</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Target Muscle Group</label>
                            <span class="badge badge-secondary">{{ ucfirst(str_replace('_', ' ', $workoutRoutine->target_muscle_group ?? 'N/A')) }}</span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <span class="badge badge-{{ $workoutRoutine->is_active ? 'success' : 'secondary' }}">
                                {{ $workoutRoutine->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <p class="text-sm text-gray-900">{{ $workoutRoutine->description ?? 'No description provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Created Date</label>
                            <p class="text-sm text-gray-900">{{ $workoutRoutine->created_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                            <p class="text-sm text-gray-900">{{ $workoutRoutine->updated_at->format('M d, Y') }}</p>
                        </div>
                    </div>
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
                        <a href="{{ route('trainer.workout-routines.edit', $workoutRoutine->id) }}" class="btn btn-primary w-full">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Routine
                        </a>
                        
                        <form method="POST" action="{{ route('trainer.workout-routines.destroy', $workoutRoutine->id) }}" 
                              onsubmit="return confirm('Are you sure you want to delete this workout routine? This action cannot be undone.')" 
                              class="inline-block w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-full">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Routine
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
                            <span class="text-sm font-medium text-gray-700">Duration</span>
                            <span class="text-sm font-bold text-gray-900">{{ $workoutRoutine->estimated_duration_minutes }} minutes</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Difficulty</span>
                            <span class="text-sm font-bold text-gray-900">{{ ucfirst($workoutRoutine->difficulty_level) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Target Muscle Group</span>
                            <span class="text-sm font-bold text-gray-900">{{ ucfirst(str_replace('_', ' ', $workoutRoutine->target_muscle_group ?? 'N/A')) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
