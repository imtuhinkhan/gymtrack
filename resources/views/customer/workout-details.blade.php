@extends('layouts.dashboard')

@section('title', 'Workout Details')
@section('page-title', 'Workout Details')

@section('sidebar')
    @include('customer.partials.sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $workoutRoutine->name }}</h1>
            <p class="text-gray-600">Workout routine details and instructions</p>
        </div>
        <a href="{{ route('customer.workouts') }}" class="btn btn-secondary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Workouts
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description -->
            @if($workoutRoutine->description)
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Description</h3>
                </div>
                <div class="card-body">
                    <p class="text-gray-700">{{ $workoutRoutine->description }}</p>
                </div>
            </div>
            @endif

            <!-- Workout Instructions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Workout Instructions</h3>
                </div>
                <div class="card-body">
                    <div class="prose max-w-none">
                        <p class="text-gray-700 mb-4">
                            This workout routine is designed to target your <strong>{{ ucfirst(str_replace('_', ' ', $workoutRoutine->target_muscle_group)) }}</strong> 
                            with a <strong>{{ ucfirst($workoutRoutine->difficulty_level) }}</strong> difficulty level.
                        </p>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <h4 class="text-sm font-medium text-blue-800 mb-2">Important Notes:</h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• Always warm up before starting your workout</li>
                                <li>• Listen to your body and rest when needed</li>
                                <li>• Maintain proper form throughout each exercise</li>
                                <li>• Stay hydrated during your workout</li>
                                <li>• Cool down and stretch after completing the routine</li>
                            </ul>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-yellow-800 mb-2">Safety Reminder:</h4>
                            <p class="text-sm text-yellow-700">
                                If you experience any pain or discomfort during the workout, stop immediately and consult with your trainer.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Workout Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Workout Information</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Duration</span>
                            <span class="text-sm font-bold text-gray-900">{{ $workoutRoutine->estimated_duration_minutes }} minutes</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Difficulty</span>
                            <span class="badge badge-{{ $workoutRoutine->difficulty_level === 'beginner' ? 'success' : ($workoutRoutine->difficulty_level === 'intermediate' ? 'warning' : 'danger') }}">
                                {{ ucfirst($workoutRoutine->difficulty_level) }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Target Area</span>
                            <span class="text-sm font-bold text-gray-900">{{ ucfirst(str_replace('_', ' ', $workoutRoutine->target_muscle_group)) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Status</span>
                            <span class="badge badge-{{ $workoutRoutine->is_active ? 'success' : 'secondary' }}">
                                {{ $workoutRoutine->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trainer Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Assigned Trainer</h3>
                </div>
                <div class="card-body">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-bold">
                                {{ substr($workoutRoutine->trainer->first_name ?? 'T', 0, 1) }}{{ substr($workoutRoutine->trainer->last_name ?? 'R', 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">
                                {{ $workoutRoutine->trainer->first_name ?? 'N/A' }} {{ $workoutRoutine->trainer->last_name ?? 'Trainer' }}
                            </h4>
                            <p class="text-xs text-gray-500">{{ $workoutRoutine->trainer->email ?? 'No email' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <a href="{{ route('customer.workouts') }}" class="btn btn-secondary w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            All Workouts
                        </a>
                        
                        <a href="{{ route('customer.attendance') }}" class="btn btn-secondary w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            View Attendance
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

