@extends('layouts.dashboard')

@section('title', 'My Membership')
@section('page-title', 'My Membership')

@section('sidebar')
    @include('customer.partials.sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">My Membership</h1>
            <p class="text-gray-600">View your membership details and subscription information</p>
        </div>
    </div>

    <!-- Membership Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Personal Information -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Personal Information</h3>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            @if($customer->profile_image)
                                <img src="{{ Storage::url($customer->profile_image) }}" 
                                     alt="{{ $customer->first_name }}" 
                                     class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
                            @else
                                <div class="w-16 h-16 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center border-2 border-gray-200">
                                    <span class="text-white text-xl font-bold">
                                        {{ substr($customer->first_name, 0, 1) }}{{ substr($customer->last_name, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h4 class="text-lg font-medium text-gray-900">{{ $customer->first_name }} {{ $customer->last_name }}</h4>
                            <p class="text-gray-600">{{ $customer->email }}</p>
                            <p class="text-sm text-gray-500">{{ $customer->phone ?? 'No phone number' }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                            <p class="text-gray-900">{{ $customer->date_of_birth ? \Carbon\Carbon::parse($customer->date_of_birth)->format('M d, Y') : 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                            <p class="text-gray-900">{{ ucfirst($customer->gender ?? 'Not specified') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <p class="text-gray-900">{{ $customer->address ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                            <p class="text-gray-900">{{ $customer->city ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Membership Details -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Membership Details</h3>
            </div>
            <div class="card-body">
                @if($subscription)
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Membership Status</span>
                            <span class="badge badge-{{ $subscription->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($subscription->status) }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Package</span>
                            <span class="text-gray-900">{{ $subscription->package->name ?? 'N/A' }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Start Date</span>
                            <span class="text-gray-900">{{ \Carbon\Carbon::parse($subscription->start_date)->format('M d, Y') }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">End Date</span>
                            <span class="text-gray-900">{{ \Carbon\Carbon::parse($subscription->end_date)->format('M d, Y') }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Price</span>
                            <span class="text-gray-900">${{ number_format($subscription->package->price ?? 0, 2) }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Branch</span>
                            <span class="text-gray-900">{{ $customer->branch->name ?? 'N/A' }}</span>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Days Remaining</span>
                                <span class="text-gray-900">
                                    @if(\Carbon\Carbon::parse($subscription->end_date)->isFuture())
                                        {{ \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($subscription->end_date)) }} days
                                    @else
                                        Expired
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No active subscription</h3>
                        <p class="mt-1 text-sm text-gray-500">You don't have an active membership subscription.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    </div>
</div>
@endsection
