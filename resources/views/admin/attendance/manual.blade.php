@extends('layouts.dashboard')

@section('title', 'Manual Attendance Entry')
@section('page-title', 'Manual Attendance Entry')

@section('sidebar')
    @include('admin.partials.sidebar')
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Manual Attendance Entry</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Attendance
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="text-lg font-medium text-gray-900">Select Branch and Date</h3>
        </div>
        <div class="card-body">
            <form method="GET" class="flex flex-wrap gap-4">
                <div>
                    <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-2">Branch</label>
                    <select name="branch_id" id="branch_id" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $selectedBranch == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" name="date" id="date" value="{{ $selectedDate }}"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn btn-primary">Load Members</button>
                </div>
            </form>
        </div>
    </div>

    @if($customers->isNotEmpty() || $trainers->isNotEmpty())
        <form method="POST" action="{{ route('admin.attendance.store-manual') }}" id="attendanceForm">
            @csrf
            <input type="hidden" name="date" value="{{ $selectedDate }}">
            <input type="hidden" name="branch_id" value="{{ $selectedBranch }}">

            <!-- Members Section -->
            @if($customers->isNotEmpty())
                <div class="card mb-6">
                    <div class="card-header">
                        <h3 class="text-lg font-medium text-gray-900">Members Attendance</h3>
                        <p class="text-sm text-gray-600">Mark attendance for gym members</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="mobile-table">
                                <thead>
                                    <tr>
                                        <th>Member</th>
                                        <th>Branch</th>
                                        <th>Check In</th>
                                        <th>Check Out</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customers as $customer)
                                        <tr>
                                            <td>
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                            <span class="text-sm font-medium text-gray-700">
                                                                {{ substr($customer->first_name, 0, 2) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $customer->first_name }} {{ $customer->last_name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $customer->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $customer->branch->name ?? 'N/A' }}
                                            </td>
                                            <td>
                                                <input type="time" name="entries[{{ $loop->index }}][check_in_time]" 
                                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                                <input type="hidden" name="entries[{{ $loop->index }}][attendable_type]" value="customer">
                                                <input type="hidden" name="entries[{{ $loop->index }}][attendable_id]" value="{{ $customer->id }}">
                                            </td>
                                            <td>
                                                <input type="time" name="entries[{{ $loop->index }}][check_out_time]" 
                                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td>
                                                <input type="text" name="entries[{{ $loop->index }}][notes]" 
                                                       placeholder="Optional notes"
                                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Trainers Section -->
            @if($trainers->isNotEmpty())
                <div class="card mb-6">
                    <div class="card-header">
                        <h3 class="text-lg font-medium text-gray-900">Trainers Attendance</h3>
                        <p class="text-sm text-gray-600">Mark attendance for trainers</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="mobile-table">
                                <thead>
                                    <tr>
                                        <th>Trainer</th>
                                        <th>Branch</th>
                                        <th>Specialization</th>
                                        <th>Check In</th>
                                        <th>Check Out</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trainers as $trainer)
                                        <tr>
                                            <td>
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                            <span class="text-sm font-medium text-gray-700">
                                                                {{ substr($trainer->first_name, 0, 2) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $trainer->first_name }} {{ $trainer->last_name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $trainer->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $trainer->branch->name ?? 'N/A' }}
                                            </td>
                                            <td>
                                                {{ is_array($trainer->specializations) ? implode(', ', $trainer->specializations) : $trainer->specializations }}
                                            </td>
                                            <td>
                                                <input type="time" name="entries[{{ $loop->index + $customers->count() }}][check_in_time]" 
                                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                                <input type="hidden" name="entries[{{ $loop->index + $customers->count() }}][attendable_type]" value="trainer">
                                                <input type="hidden" name="entries[{{ $loop->index + $customers->count() }}][attendable_id]" value="{{ $trainer->id }}">
                                            </td>
                                            <td>
                                                <input type="time" name="entries[{{ $loop->index + $customers->count() }}][check_out_time]" 
                                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td>
                                                <input type="text" name="entries[{{ $loop->index + $customers->count() }}][notes]" 
                                                       placeholder="Optional notes"
                                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form Actions -->
            <div class="card">
                <div class="card-body">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            <span id="entryCount">{{ $customers->count() + $trainers->count() }}</span> entries ready to submit
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" onclick="fillCurrentTime()" class="btn btn-outline">Fill Current Time</button>
                            <button type="submit" class="btn btn-primary">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Submit Attendance
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @else
        <div class="card">
            <div class="card-body text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No members or trainers found</h3>
                <p class="mt-1 text-sm text-gray-500">Please select a branch and date to load members and trainers.</p>
            </div>
        </div>
    @endif
</div>

<script>
function fillCurrentTime() {
    const now = new Date();
    const timeString = now.toTimeString().slice(0, 5);
    
    const checkInInputs = document.querySelectorAll('input[name*="[check_in_time]"]');
    checkInInputs.forEach(input => {
        if (!input.value) {
            input.value = timeString;
        }
    });
}

// Auto-fill current time for check-in when clicking on check-in field
document.addEventListener('DOMContentLoaded', function() {
    const checkInInputs = document.querySelectorAll('input[name*="[check_in_time]"]');
    checkInInputs.forEach(input => {
        input.addEventListener('click', function() {
            if (!this.value) {
                const now = new Date();
                const timeString = now.toTimeString().slice(0, 5);
                this.value = timeString;
            }
        });
    });
});
</script>
@endsection
