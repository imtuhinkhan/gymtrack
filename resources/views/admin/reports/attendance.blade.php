@extends('layouts.dashboard')

@section('title', 'Attendance Reports')
@section('page-title', 'Attendance Reports')

@section('sidebar')
@include('admin.partials.sidebar')
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Attendance Reports</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Reports
            </a>
            <button onclick="exportReport()" class="btn btn-success">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Report
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Filter Options</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.attendance') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                @if(auth()->user()->hasRole('admin'))
                <div>
                    <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-2">Branch</label>
                    <select name="branch_id" id="branch_id" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="flex items-end">
                    <button type="submit" class="btn btn-primary w-full">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Attendance Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="stats-card">
            <div class="stats-card-header">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="stats-card-title">Present</dt>
                            <dd class="stats-card-value">{{ $attendanceRecords->where('status', 'present')->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-header">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-danger-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="stats-card-title">Absent</dt>
                            <dd class="stats-card-value">{{ $attendanceRecords->where('status', 'absent')->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-header">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="stats-card-title">On Leave</dt>
                            <dd class="stats-card-value">{{ $attendanceRecords->where('status', 'leave')->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-header">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="stats-card-title">Total Records</dt>
                            <dd class="stats-card-value">{{ $totalAttendance }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Rate -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Attendance Rate</h3>
        </div>
        <div class="card-body">
            <div class="flex items-center">
                <div class="flex-1">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>Overall Attendance Rate</span>
                        <span>{{ $totalAttendance > 0 ? number_format(($presentCount / $totalAttendance) * 100, 1) : 0 }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="bg-success-600 h-4 rounded-full" style="width: {{ $totalAttendance > 0 ? ($presentCount / $totalAttendance) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Attendance by Date Chart -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Attendance Trend</h3>
            </div>
            <div class="card-body">
                <canvas id="attendanceByDateChart" height="300"></canvas>
            </div>
        </div>

        <!-- Attendance by Type Chart -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Attendance by Type</h3>
            </div>
            <div class="card-body">
                <canvas id="attendanceByTypeChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Attendance Details Table -->
    <div class="card">
        <div class="card-header">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Attendance Details</h3>
                <div class="flex space-x-2">
                    <form method="GET" action="{{ route('admin.reports.attendance') }}" class="flex items-center space-x-2">
                        <input type="hidden" name="start_date" value="{{ $startDate }}">
                        <input type="hidden" name="end_date" value="{{ $endDate }}">
                        @if(auth()->user()->hasRole('admin'))
                        <input type="hidden" name="branch_id" value="{{ $branchId }}">
                        @endif
                        <select name="per_page" class="text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                            <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15 per page</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 per page</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per page</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per page</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($attendanceRecords->count() > 0)
                <div class="table-responsive">
                    <table class="mobile-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Check In Time</th>
                                <th>Status</th>
                                @if(auth()->user()->hasRole('admin'))
                                <th>Branch</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendanceRecords as $record)
                                <tr>
                                    <td>
                                        <div class="text-sm font-medium text-gray-900">
                                            @if($record->attendable_type === 'App\\Models\\Customer')
                                                {{ $record->attendable->first_name ?? 'N/A' }} {{ $record->attendable->last_name ?? '' }}
                                            @else
                                                {{ $record->attendable->first_name ?? 'N/A' }} {{ $record->attendable->last_name ?? '' }}
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $record->attendable_type === 'App\\Models\\Customer' ? 'badge-primary' : 'badge-secondary' }}">
                                            {{ $record->attendable_type === 'App\\Models\\Customer' ? 'Member' : 'Trainer' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}
                                    </td>
                                    <td>
                                        {{ $record->check_in_time ? \Carbon\Carbon::parse($record->check_in_time)->format('h:i A') : 'N/A' }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $record->status === 'present' ? 'badge-success' : ($record->status === 'absent' ? 'badge-danger' : 'badge-warning') }}">
                                            {{ ucfirst($record->status) }}
                                        </span>
                                    </td>
                                    @if(auth()->user()->hasRole('admin'))
                                    <td>
                                        {{ $record->branch->name ?? 'N/A' }}
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-6 flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing {{ $attendanceRecords->firstItem() ?? 0 }} to {{ $attendanceRecords->lastItem() ?? 0 }} of {{ $attendanceRecords->total() }} results
                    </div>
                    <div class="flex items-center space-x-4">
                        <div>
                            {{ $attendanceRecords->appends(request()->query())->links() }}
                        </div>
                        @if($attendanceRecords->lastPage() > 1)
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-700">Go to page:</span>
                            <form method="GET" action="{{ route('admin.reports.attendance') }}" class="flex items-center space-x-1">
                                <input type="hidden" name="start_date" value="{{ $startDate }}">
                                <input type="hidden" name="end_date" value="{{ $endDate }}">
                                <input type="hidden" name="per_page" value="{{ request('per_page', 15) }}">
                                @if(auth()->user()->hasRole('admin'))
                                <input type="hidden" name="branch_id" value="{{ $branchId }}">
                                @endif
                                <input type="number" name="page" min="1" max="{{ $attendanceRecords->lastPage() }}" 
                                       value="{{ $attendanceRecords->currentPage() }}" 
                                       class="w-16 text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <button type="submit" class="text-sm bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700">
                                    Go
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No attendance records found</h3>
                    <p class="mt-1 text-sm text-gray-500">No attendance records match your current filters.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Auto-set end date when start date is selected
document.getElementById('start_date').addEventListener('change', function() {
    const startDate = this.value;
    const endDateInput = document.getElementById('end_date');
    
    // If end date is empty or before start date, set it to start date
    if (!endDateInput.value || endDateInput.value < startDate) {
        endDateInput.value = startDate;
    }
});

// Attendance by Date Chart
const attendanceByDateData = @json($attendanceByDate);
const attendanceByDateLabels = Object.keys(attendanceByDateData).map(date => {
    return new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
});

const attendanceByDateCtx = document.getElementById('attendanceByDateChart').getContext('2d');
new Chart(attendanceByDateCtx, {
    type: 'bar',
    data: {
        labels: attendanceByDateLabels,
        datasets: [
            {
                label: 'Present',
                data: Object.values(attendanceByDateData).map(item => item.present),
                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                borderColor: 'rgba(16, 185, 129, 1)',
                borderWidth: 1
            },
            {
                label: 'Absent',
                data: Object.values(attendanceByDateData).map(item => item.absent),
                backgroundColor: 'rgba(239, 68, 68, 0.8)',
                borderColor: 'rgba(239, 68, 68, 1)',
                borderWidth: 1
            },
            {
                label: 'On Leave',
                data: Object.values(attendanceByDateData).map(item => item.leave),
                backgroundColor: 'rgba(245, 158, 11, 0.8)',
                borderColor: 'rgba(245, 158, 11, 1)',
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                stacked: true
            },
            x: {
                stacked: true
            }
        }
    }
});

// Attendance by Type Chart
const attendanceByTypeData = @json($attendanceByType);
const attendanceByTypeLabels = Object.keys(attendanceByTypeData).map(type => {
    return type === 'App\\Models\\Customer' ? 'Members' : 'Trainers';
});

const attendanceByTypeCtx = document.getElementById('attendanceByTypeChart').getContext('2d');
new Chart(attendanceByTypeCtx, {
    type: 'doughnut',
    data: {
        labels: attendanceByTypeLabels,
        datasets: [{
            data: Object.values(attendanceByTypeData).map(item => item.present),
            backgroundColor: [
                'rgba(59, 130, 246, 0.8)',
                'rgba(16, 185, 129, 0.8)'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': ' + context.parsed + ' present';
                    }
                }
            }
        }
    }
});

function exportReport() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.reports.export") }}';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    const type = document.createElement('input');
    type.type = 'hidden';
    type.name = 'type';
    type.value = 'attendance';
    form.appendChild(type);
    
    const startDate = document.createElement('input');
    startDate.type = 'hidden';
    startDate.name = 'start_date';
    startDate.value = '{{ $startDate }}';
    form.appendChild(startDate);
    
    const endDate = document.createElement('input');
    endDate.type = 'hidden';
    endDate.name = 'end_date';
    endDate.value = '{{ $endDate }}';
    form.appendChild(endDate);
    
    @if(auth()->user()->hasRole('admin'))
    const branchId = document.createElement('input');
    branchId.type = 'hidden';
    branchId.name = 'branch_id';
    branchId.value = '{{ $branchId }}';
    form.appendChild(branchId);
    @endif
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
</script>
@endpush
@endsection
