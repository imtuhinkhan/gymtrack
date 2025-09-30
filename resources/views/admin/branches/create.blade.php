@extends('layouts.dashboard')

@section('title', 'Create Branch')
@section('page-title', 'Create Branch')

@section('sidebar')
    @include('admin.partials.sidebar')
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Create New Branch</h1>
        <a href="{{ route('admin.branches.index') }}" class="btn btn-outline">Back to Branches</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.branches.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                    </div>

                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">Branch Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               class="form-input @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description" name="description" rows="3"
                                  class="form-textarea @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Information -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               class="form-input @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone *</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required
                               class="form-input mt-1 block w-full @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address Information -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Address Information</h3>
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700">Address *</label>
                        <input type="text" id="address" name="address" value="{{ old('address') }}" required
                               class="form-input mt-1 block w-full @error('address') border-red-500 @enderror">
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">City *</label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}" required
                               class="form-input mt-1 block w-full @error('city') border-red-500 @enderror">
                        @error('city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700">State *</label>
                        <input type="text" id="state" name="state" value="{{ old('state') }}" required
                               class="form-input mt-1 block w-full @error('state') border-red-500 @enderror">
                        @error('state')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code *</label>
                        <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required
                               class="form-input mt-1 block w-full @error('postal_code') border-red-500 @enderror">
                        @error('postal_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700">Country *</label>
                        <input type="text" id="country" name="country" value="{{ old('country', 'United States') }}" required
                               class="form-input mt-1 block w-full @error('country') border-red-500 @enderror">
                        @error('country')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Manager Information -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Manager Information</h3>
                    </div>

                    <div>
                        <label for="manager_name" class="block text-sm font-medium text-gray-700">Manager Name</label>
                        <input type="text" id="manager_name" name="manager_name" value="{{ old('manager_name') }}"
                               class="form-input mt-1 block w-full @error('manager_name') border-red-500 @enderror">
                        @error('manager_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="manager_email" class="block text-sm font-medium text-gray-700">Manager Email</label>
                        <input type="email" id="manager_email" name="manager_email" value="{{ old('manager_email') }}"
                               class="form-input mt-1 block w-full @error('manager_email') border-red-500 @enderror">
                        @error('manager_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="manager_phone" class="block text-sm font-medium text-gray-700">Manager Phone</label>
                        <input type="text" id="manager_phone" name="manager_phone" value="{{ old('manager_phone') }}"
                               class="form-input mt-1 block w-full @error('manager_phone') border-red-500 @enderror">
                        @error('manager_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Operating Hours -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Operating Hours</h3>
                    </div>

                    <div>
                        <label for="opening_time" class="block text-sm font-medium text-gray-700">Opening Time *</label>
                        <input type="time" id="opening_time" name="opening_time" value="{{ old('opening_time', '06:00') }}" required
                               class="form-input mt-1 block w-full @error('opening_time') border-red-500 @enderror">
                        @error('opening_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="closing_time" class="block text-sm font-medium text-gray-700">Closing Time *</label>
                        <input type="time" id="closing_time" name="closing_time" value="{{ old('closing_time', '22:00') }}" required
                               class="form-input mt-1 block w-full @error('closing_time') border-red-500 @enderror">
                        @error('closing_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Working Days *</label>
                        <div class="mt-2 grid grid-cols-7 gap-2">
                            @php
                                $workingDays = old('working_days', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']);
                            @endphp
                            @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                <label class="flex items-center">
                                    <input type="checkbox" name="working_days[]" value="{{ $day }}" 
                                           {{ in_array($day, $workingDays) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                    <span class="ml-2 text-sm text-gray-700">{{ ucfirst($day) }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('working_days')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Images -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Images</h3>
                    </div>

                    <div>
                        <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                        <input type="file" id="logo" name="logo" accept="image/*"
                               class="form-input mt-1 block w-full @error('logo') border-red-500 @enderror">
                        @error('logo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="banner" class="block text-sm font-medium text-gray-700">Banner</label>
                        <input type="file" id="banner" name="banner" accept="image/*"
                               class="form-input mt-1 block w-full @error('banner') border-red-500 @enderror">
                        @error('banner')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Coordinates -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Location Coordinates (Optional)</h3>
                    </div>

                    <div>
                        <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                        <input type="number" id="latitude" name="latitude" value="{{ old('latitude') }}" step="any"
                               class="form-input mt-1 block w-full @error('latitude') border-red-500 @enderror">
                        @error('latitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                        <input type="number" id="longitude" name="longitude" value="{{ old('longitude') }}" step="any"
                               class="form-input mt-1 block w-full @error('longitude') border-red-500 @enderror">
                        @error('longitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.branches.index') }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Branch</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection