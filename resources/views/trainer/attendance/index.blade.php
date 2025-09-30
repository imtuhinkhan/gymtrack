@extends('layouts.dashboard')

@section('title', 'Attendance')
@section('page-title', 'Attendance')

@section('sidebar')
    @include('trainer.partials.sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Attendance</h1>
        <button onclick="openManualEntryModal()" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Manual Entry
        </button>
    </div>

    <!-- Date Filter -->
    <div class="card mb-6">
        <div class="card-body">
            <form method="GET" action="{{ route('trainer.attendance.index') }}" class="flex items-end space-x-4">
                <div class="flex-1">
                    <label for="date" class="block text-sm font-medium text-gray-700">Select Date</label>
                    <input type="date" id="date" name="date" value="{{ $date }}"
                           class="form-input mt-1">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn btn-secondary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('trainer.attendance.index') }}" class="btn btn-outline ml-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Attendance List -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-medium text-gray-900">Attendance for {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</h3>
        </div>
        <div class="card-body">
            @if($attendance->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check In</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check Out</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($attendance as $record)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-gray-700">
                                                        {{ substr($record->attendable->first_name ?? 'N/A', 0, 1) }}{{ substr($record->attendable->last_name ?? 'N/A', 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $record->attendable->first_name ?? 'N/A' }} {{ $record->attendable->last_name ?? '' }}
                                                </div>
                                            </div>
                                        </div>
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $record->notes ?? '-' }}
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
                    <p class="mt-1 text-sm text-gray-500">No attendance records found for this date.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Manual Entry Modal -->
<div id="manualEntryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Manual Attendance Entry</h3>
            <form method="POST" action="{{ route('trainer.attendance.store-manual') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-700">Member</label>
                        <select name="customer_id" id="customer_id" class="form-select mt-1" required>
                            <option value="">Select Member</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}">{{ $member->first_name ?? 'N/A' }} {{ $member->last_name ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" name="date" id="date" value="{{ $date }}" class="form-input mt-1" required>
                    </div>
                    
                    <div>
                        <label for="check_in_time" class="block text-sm font-medium text-gray-700">Check In Time</label>
                        <input type="time" name="check_in_time" id="check_in_time" class="form-input mt-1" required>
                    </div>
                    
                    <div>
                        <label for="check_out_time" class="block text-sm font-medium text-gray-700">Check Out Time</label>
                        <input type="time" name="check_out_time" id="check_out_time" class="form-input mt-1">
                    </div>
                    
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea name="notes" id="notes" rows="3" class="form-textarea mt-1" placeholder="Optional notes..."></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeManualEntryModal()" class="btn btn-outline">Cancel</button>
                    <button type="submit" class="btn btn-primary">Record Attendance</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openManualEntryModal() {
    document.getElementById('manualEntryModal').classList.remove('hidden');
}

function closeManualEntryModal() {
    document.getElementById('manualEntryModal').classList.add('hidden');
}
</script>
@endsection
