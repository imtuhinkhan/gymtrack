@extends('layouts.dashboard')

@section('title', 'Branch Details')
@section('page-title', 'Branch Details')

@section('sidebar')
    @include('admin.partials.sidebar')
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">{{ $branch->name }}</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.branches.edit', $branch->id) }}" class="btn btn-primary">Edit Branch</a>
            <a href="{{ route('admin.branches.index') }}" class="btn btn-outline">Back to Branches</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Branch Information -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Branch Information</h3>
                </div>
                <div class="card-body">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Branch Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $branch->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $branch->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $branch->phone }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="badge badge-{{ $branch->is_active ? 'success' : 'warning' }}">
                                    {{ $branch->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Address</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $branch->full_address }}</dd>
                        </div>
                        @if($branch->description)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $branch->description }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Manager Information -->
            <div class="card mt-6">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Manager Information</h3>
                </div>
                <div class="card-body">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Manager Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $branch->manager_name ?? 'Not Assigned' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Manager Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $branch->manager_email ?? 'Not Provided' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Manager Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $branch->manager_phone ?? 'Not Provided' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Operating Hours -->
            <div class="card mt-6">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Operating Hours</h3>
                </div>
                <div class="card-body">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Opening Time</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($branch->opening_time)->format('g:i A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Closing Time</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($branch->closing_time)->format('g:i A') }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Working Days</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($branch->working_days)
                                    @php
                                        $workingDays = is_string($branch->working_days) ? json_decode($branch->working_days, true) : $branch->working_days;
                                    @endphp
                                    {{ implode(', ', array_map('ucfirst', $workingDays)) }}
                                @else
                                    Not specified
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="space-y-6">
            <!-- Branch Stats -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Branch Statistics</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Total Members</span>
                            <span class="text-sm font-medium text-gray-900">{{ $branch->customers->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Total Trainers</span>
                            <span class="text-sm font-medium text-gray-900">{{ $branch->trainers->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Total Staff</span>
                            <span class="text-sm font-medium text-gray-900">{{ $branch->users->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Members -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Members</h3>
                </div>
                <div class="card-body">
                    @if($branch->customers->count() > 0)
                        <div class="space-y-3">
                            @foreach($branch->customers->take(5) as $customer)
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-xs font-medium text-gray-700">
                                            {{ substr($customer->first_name ?? 'N/A', 0, 2) }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $customer->first_name }} {{ $customer->last_name }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $customer->email }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-4">No members found.</p>
                    @endif
                </div>
            </div>

            <!-- Recent Trainers -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Branch Trainers</h3>
                </div>
                <div class="card-body">
                    @if($branch->trainers->count() > 0)
                        <div class="space-y-3">
                            @foreach($branch->trainers->take(5) as $trainer)
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-xs font-medium text-gray-700">
                                            {{ substr($trainer->first_name ?? 'N/A', 0, 2) }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $trainer->first_name }} {{ $trainer->last_name }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ is_array($trainer->specializations) ? implode(', ', $trainer->specializations) : ($trainer->specializations ?? 'N/A') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-4">No trainers found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
