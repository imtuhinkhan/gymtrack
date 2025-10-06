@extends('layouts.dashboard')

@section('title', 'Package Details')
@section('page-title', 'Package Details')

@section('sidebar')
    @include('admin.partials.sidebar')
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">{{ $package->name }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.packages.edit', $package->id) }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Package
            </a>
            <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Packages
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Package Information -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">Package Information</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Package Name</label>
                            <p class="text-sm text-gray-900">{{ $package->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                            <p class="text-sm text-gray-900">{{ $package->formatted_price }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                            <p class="text-sm text-gray-900">{{ $package->duration_description }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Max Visits</label>
                            <p class="text-sm text-gray-900">{{ $package->max_visits ?? 'Unlimited' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <span class="badge badge-{{ $package->is_active ? 'success' : 'secondary' }}">
                                {{ $package->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Popular</label>
                            <span class="badge badge-{{ $package->is_popular ? 'warning' : 'secondary' }}">
                                {{ $package->is_popular ? 'Yes' : 'No' }}
                            </span>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <p class="text-sm text-gray-900">{{ $package->description ?? 'No description provided' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Features</label>
                            <div class="text-sm text-gray-900">
                                @if($package->features && is_array($package->features))
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach($package->features as $feature)
                                            <li>{{ $feature }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>No features listed</p>
                                @endif
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Package Includes</label>
                            <div class="space-y-2">
                                @if($package->includes_trainer)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Personal Trainer
                                    </span>
                                @endif
                                @if($package->includes_locker)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Locker Access
                                    </span>
                                @endif
                                @if($package->includes_towel)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Towel Service
                                    </span>
                                @endif
                                @if(!$package->includes_trainer && !$package->includes_locker && !$package->includes_towel)
                                    <p class="text-sm text-gray-500">No additional services included</p>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Created Date</label>
                            <p class="text-sm text-gray-900">{{ $package->created_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                            <p class="text-sm text-gray-900">{{ $package->updated_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Subscriptions -->
            <div class="card mt-6">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">Active Subscriptions</h3>
                </div>
                <div class="card-body">
                    @if($package->subscriptions && $package->subscriptions->count() > 0)
                        <div class="space-y-3">
                            @foreach($package->subscriptions->where('status', 'active')->take(10) as $subscription)
                                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-700">
                                            {{ substr($subscription->customer->first_name ?? 'N/A', 0, 2) }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $subscription->customer->first_name }} {{ $subscription->customer->last_name }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ $subscription->customer->email }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span class="text-sm text-gray-500">
                                            Expires: {{ \Carbon\Carbon::parse($subscription->end_date)->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($package->subscriptions->where('status', 'active')->count() > 10)
                            <p class="text-sm text-gray-500 text-center mt-3">
                                And {{ $package->subscriptions->where('status', 'active')->count() - 10 }} more subscriptions...
                            </p>
                        @endif
                    @else
                        <p class="text-sm text-gray-500 text-center py-4">No active subscriptions for this package.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <a href="{{ route('admin.packages.edit', $package->id) }}" class="btn btn-primary w-full">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Package
                        </a>
                        
                        <form method="POST" action="{{ route('admin.packages.duplicate', $package->id) }}" class="inline-block w-full">
                            @csrf
                            <button type="submit" class="btn btn-secondary w-full">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                Duplicate Package
                            </button>
                        </form>
                        
                        <form method="POST" action="{{ route('admin.packages.toggle-status', $package->id) }}" class="inline-block w-full">
                            @csrf
                            <button type="submit" class="btn btn-{{ $package->is_active ? 'warning' : 'success' }} w-full">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                </svg>
                                {{ $package->is_active ? 'Deactivate' : 'Activate' }} Package
                            </button>
                        </form>
                        
                        <form method="POST" action="{{ route('admin.packages.destroy', $package->id) }}" 
                              onsubmit="return confirm('Are you sure you want to delete this package? This action cannot be undone.')" 
                              class="inline-block w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-full">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Package
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">Statistics</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Total Subscriptions</span>
                            <span class="text-sm font-bold text-gray-900">{{ $package->subscriptions ? $package->subscriptions->count() : 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Active Subscriptions</span>
                            <span class="text-sm font-bold text-green-600">{{ $package->subscriptions ? $package->subscriptions->where('status', 'active')->count() : 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Expired Subscriptions</span>
                            <span class="text-sm font-bold text-red-600">{{ $package->subscriptions ? $package->subscriptions->where('status', 'expired')->count() : 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Total Revenue</span>
                            <span class="text-sm font-bold text-green-600">{{ \App\Services\SettingsService::formatCurrency($package->subscriptions ? $package->subscriptions->sum('package.price') : 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">Package Created</p>
                                <p class="text-xs text-gray-500">{{ $package->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        
                        @if($package->updated_at != $package->created_at)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">Package Updated</p>
                                <p class="text-xs text-gray-500">{{ $package->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection