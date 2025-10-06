@extends('layouts.dashboard')


@section('content')
    <!-- Welcome Header -->
    <div class="mb-6 lg:mb-8">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl lg:rounded-2xl p-6 lg:p-8 text-white shadow-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl lg:text-4xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h1>
                    <p class="text-blue-100 text-sm lg:text-lg">Here's what's happening with {{ $appName }} today.</p>
                </div>
                <div class="hidden md:block">
                    <div class="w-16 h-16 lg:w-24 lg:h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 lg:w-12 lg:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8">
        <div class="stats-card">
            <div class="stats-card-header">
                <div class="flex items-center">
                    <div class="stats-card-icon bg-gradient-to-r from-blue-500 to-blue-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="stats-card-title">Total Branches</dt>
                            <dd class="stats-card-value">{{ $stats['total_branches'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-header">
                <div class="flex items-center">
                    <div class="stats-card-icon bg-gradient-to-r from-green-500 to-green-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="stats-card-title">Total Members</dt>
                            <dd class="stats-card-value">{{ $stats['total_customers'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-header">
                <div class="flex items-center">
                    <div class="stats-card-icon bg-gradient-to-r from-purple-500 to-purple-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="stats-card-title">Total Trainers</dt>
                            <dd class="stats-card-value">{{ $stats['total_trainers'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-header">
                <div class="flex items-center">
                    <div class="stats-card-icon bg-gradient-to-r from-emerald-500 to-emerald-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="stats-card-title">Monthly Revenue</dt>
                            <dd class="stats-card-value">{{ \App\Services\SettingsService::formatCurrency($stats['monthly_revenue']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="stats-card">
            <div class="stats-card-header">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="stats-card-title">Active Subscriptions</dt>
                            <dd class="stats-card-value">{{ $stats['active_subscriptions'] }}</dd>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="stats-card-title">Pending Payments</dt>
                            <dd class="stats-card-value">{{ $stats['pending_payments'] }}</dd>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="stats-card-title">Overdue Payments</dt>
                            <dd class="stats-card-value">{{ $stats['overdue_payments'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Members -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900">Recent Members</h3>
            </div>
            <div class="card-body">
                <div class="flow-root">
                    <ul class="-my-5 divide-y divide-gray-200">
                        @forelse($recentCustomers as $customer)
                            <li class="py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <img class="h-10 w-10 rounded-full" src="{{ $customer->profile_image_url }}" alt="{{ $customer->full_name }}">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $customer->full_name }}</p>
                                        <p class="text-sm text-gray-500 truncate">{{ $customer->email }}</p>
                                    </div>
                                    <div>
                                        <span class="badge badge-{{ $customer->status === 'active' ? 'success' : 'warning' }}">
                                            {{ ucfirst($customer->status) }}
                                        </span>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="py-4 text-center text-gray-500">No recent members</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Expiring Subscriptions -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900">Expiring Subscriptions</h3>
            </div>
            <div class="card-body">
                <div class="flow-root">
                    <ul class="-my-5 divide-y divide-gray-200">
                        @forelse($expiringSubscriptions as $subscription)
                            <li class="py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-warning-100 flex items-center justify-center">
                                            <svg class="h-5 w-5 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $subscription->customer->full_name }}</p>
                                        <p class="text-sm text-gray-500 truncate">{{ $subscription->package->name }}</p>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $subscription->days_remaining }} days left
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="py-4 text-center text-gray-500">No expiring subscriptions</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
