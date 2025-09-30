@extends('layouts.dashboard')

@section('title', 'My Dashboard')
@section('page-title', 'My Dashboard')

@section('sidebar')
    @include('customer.partials.sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="card mb-6">
        <div class="card-body">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    @if($customer->profile_image)
                        <img src="{{ Storage::url($customer->profile_image) }}" 
                             alt="{{ $customer->first_name }}" 
                             class="w-16 h-16 rounded-full object-cover border-4 border-white shadow-lg">
                    @else
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center border-4 border-white shadow-lg">
                            <span class="text-white text-xl font-bold">
                                {{ substr($customer->first_name, 0, 1) }}{{ substr($customer->last_name, 0, 1) }}
                            </span>
                        </div>
                    @endif
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ $customer->first_name }}!</h1>
                    <p class="text-gray-600">Track your fitness journey and stay motivated</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Clock In/Out Section -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="text-lg font-medium text-gray-900">Today's Attendance</h3>
        </div>
        <div class="card-body">
            @php
                $todayAttendance = $customer->attendance()->where('date', now()->toDateString())->first();
            @endphp
            
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">{{ now()->format('M d, Y') }}</div>
                        <div class="text-sm text-gray-500">{{ now()->format('l') }}</div>
                    </div>
                    
                    @if($todayAttendance)
                        <div class="flex items-center space-x-6">
                            <div class="text-center">
                                <div class="text-sm text-gray-500">Check In</div>
                                <div class="text-lg font-semibold text-green-600">
                                    {{ $todayAttendance->check_in_time ? \Carbon\Carbon::parse($todayAttendance->check_in_time)->format('H:i') : '-' }}
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="text-sm text-gray-500">Check Out</div>
                                <div class="text-lg font-semibold text-blue-600">
                                    {{ $todayAttendance->check_out_time ? \Carbon\Carbon::parse($todayAttendance->check_out_time)->format('H:i') : '-' }}
                                </div>
                            </div>
                            @if($todayAttendance->check_in_time && $todayAttendance->check_out_time)
                                <div class="text-center">
                                    <div class="text-sm text-gray-500">Duration</div>
                                    <div class="text-lg font-semibold text-purple-600">
                                        {{ \Carbon\Carbon::parse($todayAttendance->check_in_time)->diffInMinutes(\Carbon\Carbon::parse($todayAttendance->check_out_time)) }} min
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-gray-500">No attendance recorded for today</div>
                    @endif
                </div>
                
                <div class="flex space-x-3">
                    @if(!$todayAttendance || !$todayAttendance->check_in_time)
                        <form method="POST" action="{{ route('customer.clock-in') }}" class="inline">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Clock In
                            </button>
                        </form>
                    @endif
                    
                    @if($todayAttendance && $todayAttendance->check_in_time && !$todayAttendance->check_out_time)
                        <form method="POST" action="{{ route('customer.clock-out') }}" class="inline">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Clock Out
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="stats-card">
            <div class="stats-card-header">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="stats-card-title">Total Workouts</dt>
                            <dd class="stats-card-value">{{ $stats['total_workouts'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-header">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="stats-card-title">Active Workouts</dt>
                            <dd class="stats-card-value">{{ $stats['active_workouts'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-header">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="stats-card-title">Total Attendance</dt>
                            <dd class="stats-card-value">{{ $stats['total_attendance'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-header">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="stats-card-title">Membership Status</dt>
                            <dd class="stats-card-value">{{ ucfirst($customer->status) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- My Workout Routines -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-medium text-gray-900">My Workout Routines</h3>
        </div>
        <div class="card-body">
            @if($workoutRoutines->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trainer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target Muscle</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Difficulty</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($workoutRoutines as $routine)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $routine->name }}</div>
                                        @if($routine->description)
                                            <div class="text-sm text-gray-500">{{ Str::limit($routine->description, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $routine->trainer->first_name ?? 'N/A' }} {{ $routine->trainer->last_name ?? '' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ ucfirst(str_replace('_', ' ', $routine->target_muscle_group)) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="badge badge-{{ $routine->difficulty_level === 'beginner' ? 'success' : ($routine->difficulty_level === 'intermediate' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($routine->difficulty_level) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $routine->estimated_duration_minutes }} min
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="badge badge-{{ $routine->is_active ? 'success' : 'secondary' }}">
                                            {{ $routine->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No workout routines</h3>
                    <p class="mt-1 text-sm text-gray-500">Your trainer hasn't assigned any workout routines yet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Attendance -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-medium text-gray-900">Recent Attendance</h3>
        </div>
        <div class="card-body">
            @if($recentAttendance->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check In</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check Out</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentAttendance as $record)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($record->check_in_time)->format('H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $record->check_out_time ? \Carbon\Carbon::parse($record->check_out_time)->format('H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($record->check_out_time)
                                            {{ \Carbon\Carbon::parse($record->check_in_time)->diffInMinutes(\Carbon\Carbon::parse($record->check_out_time)) }} min
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="badge badge-success">Present</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No attendance records</h3>
                    <p class="mt-1 text-sm text-gray-500">No attendance records found for the last 30 days.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection