@extends('layouts.dashboard')

@section('title', 'Revenue Reports')
@section('page-title', 'Revenue Reports')

@section('sidebar')
@include('admin.partials.sidebar')
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Revenue Reports</h1>
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
            <form method="GET" action="{{ route('admin.reports.revenue') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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

    <!-- Revenue Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="stats-card">
            <div class="stats-card-header">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="stats-card-title">Total Revenue</dt>
                            <dd class="stats-card-value">{{ \App\Services\SettingsService::formatCurrency($totalRevenue) }}</dd>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="stats-card-title">Total Transactions</dt>
                            <dd class="stats-card-value">{{ $payments->count() }}</dd>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="stats-card-title">Average Transaction</dt>
                            <dd class="stats-card-value">{{ $payments->count() > 0 ? \App\Services\SettingsService::formatCurrency($totalRevenue / $payments->count()) : \App\Services\SettingsService::formatCurrency(0) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Revenue by Date Chart -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Revenue Trend</h3>
            </div>
            <div class="card-body">
                <canvas id="revenueByDateChart" height="300"></canvas>
            </div>
        </div>

        <!-- Revenue by Branch Chart -->
        @if(auth()->user()->hasRole('admin'))
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Revenue by Branch</h3>
            </div>
            <div class="card-body">
                <canvas id="revenueByBranchChart" height="300"></canvas>
            </div>
        </div>
        @endif
    </div>

    <!-- Payment Details Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Payment Details</h3>
        </div>
        <div class="card-body">
            @if($payments->count() > 0)
                <div class="table-responsive">
                    <table class="mobile-table">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Payment Date</th>
                                <th>Status</th>
                                @if(auth()->user()->hasRole('admin'))
                                <th>Branch</th>
                                @endif
                                <th>Payment Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $payment->customer->first_name ?? 'N/A' }} {{ $payment->customer->last_name ?? '' }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $payment->customer->email ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ \App\Services\SettingsService::formatCurrency($payment->amount) }}
                                    </td>
                                    <td>
                                        {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $payment->status === 'paid' ? 'badge-success' : ($payment->status === 'pending' ? 'badge-warning' : 'badge-danger') }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    @if(auth()->user()->hasRole('admin'))
                                    <td>
                                        {{ $payment->branch->name ?? 'N/A' }}
                                    </td>
                                    @endif
                                    <td>
                                        {{ ucfirst($payment->payment_method ?? 'N/A') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No payments found</h3>
                    <p class="mt-1 text-sm text-gray-500">No payment records match your current filters.</p>
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

// Revenue by Date Chart
const revenueByDateData = @json($revenueByDate);
const revenueByDateLabels = Object.keys(revenueByDateData).map(date => {
    return new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
});
const revenueByDateValues = Object.values(revenueByDateData);

const revenueByDateCtx = document.getElementById('revenueByDateChart').getContext('2d');
new Chart(revenueByDateCtx, {
    type: 'line',
    data: {
        labels: revenueByDateLabels,
        datasets: [{
            label: 'Revenue ({{ \App\Services\SettingsService::getCurrencySymbol() }})',
            data: revenueByDateValues,
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '{{ \App\Services\SettingsService::getCurrencySymbol() }}' + value.toLocaleString();
                    }
                }
            }
        }
    }
});

@if(auth()->user()->hasRole('admin'))
// Revenue by Branch Chart
const revenueByBranchData = @json($revenueByBranch);
const revenueByBranchLabels = Object.keys(revenueByBranchData).map(branchId => {
    const branch = @json($branches->keyBy('id'));
    return branch[branchId] ? branch[branchId].name : 'Unknown Branch';
});
const revenueByBranchValues = Object.values(revenueByBranchData);

const revenueByBranchCtx = document.getElementById('revenueByBranchChart').getContext('2d');
new Chart(revenueByBranchCtx, {
    type: 'doughnut',
    data: {
        labels: revenueByBranchLabels,
        datasets: [{
            data: revenueByBranchValues,
            backgroundColor: [
                'rgba(59, 130, 246, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(239, 68, 68, 0.8)',
                'rgba(139, 92, 246, 0.8)',
                'rgba(236, 72, 153, 0.8)'
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
                        return context.label + ': {{ \App\Services\SettingsService::getCurrencySymbol() }}' + context.parsed.toLocaleString();
                    }
                }
            }
        }
    }
});
@endif

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
    type.value = 'revenue';
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
