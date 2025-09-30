@extends('layouts.dashboard')

@section('title', 'Edit Package')
@section('page-title', 'Edit Package')

@section('sidebar')
    @include('admin.partials.sidebar')
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Edit Package: {{ $package->name }}</h1>
        <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Packages
        </a>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900">Package Information</h3>
                <p class="text-sm text-gray-600">Update the package details</p>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.packages.update', $package->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Package Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Package Name *</label>
                            <input type="text" name="name" id="name" required 
                                   value="{{ old('name', $package->name) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" id="description" rows="4" 
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-300 @enderror">{{ old('description', $package->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price ($) *</label>
                            <input type="number" name="price" id="price" required min="0" step="0.01"
                                   value="{{ old('price', $package->price) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-300 @enderror">
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Duration Type -->
                        <div>
                            <label for="duration_type" class="block text-sm font-medium text-gray-700 mb-2">Duration Type *</label>
                            <select name="duration_type" id="duration_type" required 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('duration_type') border-red-300 @enderror">
                                <option value="">Select Duration Type</option>
                                <option value="days" {{ old('duration_type', $package->duration_type) == 'days' ? 'selected' : '' }}>Days</option>
                                <option value="weeks" {{ old('duration_type', $package->duration_type) == 'weeks' ? 'selected' : '' }}>Weeks</option>
                                <option value="months" {{ old('duration_type', $package->duration_type) == 'months' ? 'selected' : '' }}>Months</option>
                                <option value="years" {{ old('duration_type', $package->duration_type) == 'years' ? 'selected' : '' }}>Years</option>
                            </select>
                            @error('duration_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Duration Value -->
                        <div>
                            <label for="duration_value" class="block text-sm font-medium text-gray-700 mb-2">Duration Value *</label>
                            <input type="number" name="duration_value" id="duration_value" required min="1"
                                   value="{{ old('duration_value', $package->duration_value) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('duration_value') border-red-300 @enderror">
                            @error('duration_value')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Max Visits -->
                        <div>
                            <label for="max_visits" class="block text-sm font-medium text-gray-700 mb-2">Max Visits</label>
                            <input type="number" name="max_visits" id="max_visits" min="1"
                                   value="{{ old('max_visits', $package->max_visits) }}"
                                   placeholder="Leave empty for unlimited"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('max_visits') border-red-300 @enderror">
                            @error('max_visits')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Features -->
                        <div class="md:col-span-2">
                            <label for="features" class="block text-sm font-medium text-gray-700 mb-2">Features</label>
                            <textarea name="features" id="features" rows="4" 
                                      placeholder="Enter features separated by commas (e.g., Gym Access, Personal Training, Sauna)"
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('features') border-red-300 @enderror">{{ old('features', is_array($package->features) ? implode(', ', $package->features) : $package->features) }}</textarea>
                            @error('features')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Includes Options -->
                        <div class="md:col-span-2">
                            <h4 class="text-md font-medium text-gray-700 mb-3">Package Includes</h4>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="includes_trainer" value="1" 
                                           {{ old('includes_trainer', $package->includes_trainer) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Personal Trainer</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="includes_locker" value="1" 
                                           {{ old('includes_locker', $package->includes_locker) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Locker Access</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="includes_towel" value="1" 
                                           {{ old('includes_towel', $package->includes_towel) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Towel Service</span>
                                </label>
                            </div>
                        </div>

                        <!-- Popular Package -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_popular" value="1" 
                                       {{ old('is_popular', $package->is_popular) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Mark as Popular</span>
                            </label>
                        </div>

                        <!-- Active Status -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" 
                                       {{ old('is_active', $package->is_active) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Active Package</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Package</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection