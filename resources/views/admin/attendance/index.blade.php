@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Attendance Management</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.attendance.manual') }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Manual Entry
            </a>
            <a href="{{ route('admin.attendance.statistics') }}" class="btn btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                View Statistics
            </a>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="card mb-6">
        <div class="card-body">
            <form method="GET" class="flex flex-wrap gap-4">
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700">Select Date</label>
                    <input type="date" id="date" name="date" value="{{ request('date', now()->toDateString()) }}"
                           class="form-input mt-1">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn btn-secondary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline ml-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="card">
        <div class="card-body">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Attendance for {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</h3>
            
            @if($customers->isEmpty() && $trainers->isEmpty())
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No attendance records found</h3>
                    <p class="mt-1 text-sm text-gray-500">No members or trainers found for this date.</p>
                </div>
            @else
                <!-- Members Attendance -->
                @if($customers->isNotEmpty())
                    <div class="mb-8">
                        <h4 class="text-md font-semibold text-gray-900 mb-4">Members Attendance</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($customers as $customer)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $customer->profile_image_url }}" alt="{{ $customer->first_name }}">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $customer->first_name }} {{ $customer->last_name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $customer->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $customer->branch->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $attendance = $customerAttendance->get($customer->id);
                                                @endphp
                                                @if($attendance)
                                                    <span class="badge badge-{{ $attendance->status === 'present' ? 'success' : ($attendance->status === 'absent' ? 'danger' : 'warning') }}">
                                                        {{ ucfirst($attendance->status) }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">Not Marked</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <form method="POST" action="{{ route('admin.attendance.store') }}" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="attendable_id" value="{{ $customer->id }}">
                                                        <input type="hidden" name="attendable_type" value="App\Models\Customer">
                                                        <input type="hidden" name="date" value="{{ $date }}">
                                                        <button type="submit" name="status" value="present" class="text-green-600 hover:text-green-900">Present</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.attendance.store') }}" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="attendable_id" value="{{ $customer->id }}">
                                                        <input type="hidden" name="attendable_type" value="App\Models\Customer">
                                                        <input type="hidden" name="date" value="{{ $date }}">
                                                        <button type="submit" name="status" value="absent" class="text-red-600 hover:text-red-900">Absent</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.attendance.store') }}" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="attendable_id" value="{{ $customer->id }}">
                                                        <input type="hidden" name="attendable_type" value="App\Models\Customer">
                                                        <input type="hidden" name="date" value="{{ $date }}">
                                                        <button type="submit" name="status" value="leave" class="text-yellow-600 hover:text-yellow-900">Leave</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Trainers Attendance -->
                @if($trainers->isNotEmpty())
                    <div>
                        <h4 class="text-md font-semibold text-gray-900 mb-4">Trainers Attendance</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trainer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Specialization</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($trainers as $trainer)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $trainer->profile_image_url }}" alt="{{ $trainer->first_name }}">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $trainer->first_name }} {{ $trainer->last_name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $trainer->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $trainer->branch->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $trainer->specialization }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $attendance = $trainerAttendance->get($trainer->id);
                                                @endphp
                                                @if($attendance)
                                                    <span class="badge badge-{{ $attendance->status === 'present' ? 'success' : ($attendance->status === 'absent' ? 'danger' : 'warning') }}">
                                                        {{ ucfirst($attendance->status) }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">Not Marked</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <form method="POST" action="{{ route('admin.attendance.store') }}" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="attendable_id" value="{{ $trainer->id }}">
                                                        <input type="hidden" name="attendable_type" value="App\Models\Trainer">
                                                        <input type="hidden" name="date" value="{{ $date }}">
                                                        <button type="submit" name="status" value="present" class="text-green-600 hover:text-green-900">Present</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.attendance.store') }}" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="attendable_id" value="{{ $trainer->id }}">
                                                        <input type="hidden" name="attendable_type" value="App\Models\Trainer">
                                                        <input type="hidden" name="date" value="{{ $date }}">
                                                        <button type="submit" name="status" value="absent" class="text-red-600 hover:text-red-900">Absent</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.attendance.store') }}" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="attendable_id" value="{{ $trainer->id }}">
                                                        <input type="hidden" name="attendable_type" value="App\Models\Trainer">
                                                        <input type="hidden" name="date" value="{{ $date }}">
                                                        <button type="submit" name="status" value="leave" class="text-yellow-600 hover:text-yellow-900">Leave</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection