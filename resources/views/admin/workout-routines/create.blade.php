@extends('layouts.dashboard')

@section('title', 'Create Workout Routine')
@section('page-title', 'Create Workout Routine')

@section('sidebar')
    @include('admin.partials.sidebar')
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Create New Workout Routine</h1>
        <a href="{{ route('admin.workout-routines.index') }}" class="btn btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Workout Routines
        </a>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900">Workout Routine Information</h3>
                <p class="text-sm text-gray-600">Fill in the details for the new workout routine</p>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.workout-routines.store') }}">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Routine Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Routine Name *</label>
                            <input type="text" name="name" id="name" required 
                                   value="{{ old('name') }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" id="description" rows="4" 
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-300 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Member Selection -->
                        <div class="md:col-span-2">
                            <label for="member_id" class="block text-sm font-medium text-gray-700 mb-2">Member (Optional)</label>
                            <select name="member_id" id="member_id" 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('member_id') border-red-300 @enderror">
                                <option value="">Select a member (optional)</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                        {{ $member->first_name }} {{ $member->last_name }} ({{ $member->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('member_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Trainer Selection -->
                        <div class="md:col-span-2">
                            <label for="trainer_id" class="block text-sm font-medium text-gray-700 mb-2">Trainer *</label>
                            <select name="trainer_id" id="trainer_id" required 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('trainer_id') border-red-300 @enderror">
                                <option value="">Select a trainer</option>
                                @foreach($trainers as $trainer)
                                    <option value="{{ $trainer->id }}" {{ old('trainer_id') == $trainer->id ? 'selected' : '' }}>
                                        {{ $trainer->first_name }} {{ $trainer->last_name }} ({{ $trainer->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('trainer_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Duration -->
                        <div>
                            <label for="estimated_duration_minutes" class="block text-sm font-medium text-gray-700 mb-2">Duration (Minutes) *</label>
                            <input type="number" name="estimated_duration_minutes" id="estimated_duration_minutes" required min="1" max="480"
                                   value="{{ old('estimated_duration_minutes') }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('estimated_duration_minutes') border-red-300 @enderror">
                            @error('estimated_duration_minutes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Target Muscle Group -->
                        <div>
                            <label for="target_muscle_group" class="block text-sm font-medium text-gray-700 mb-2">Target Muscle Group *</label>
                            <select name="target_muscle_group" id="target_muscle_group" required 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('target_muscle_group') border-red-300 @enderror">
                                <option value="">Select Target Muscle Group</option>
                                <option value="full_body" {{ old('target_muscle_group') == 'full_body' ? 'selected' : '' }}>Full Body</option>
                                <option value="upper_body" {{ old('target_muscle_group') == 'upper_body' ? 'selected' : '' }}>Upper Body</option>
                                <option value="lower_body" {{ old('target_muscle_group') == 'lower_body' ? 'selected' : '' }}>Lower Body</option>
                                <option value="core" {{ old('target_muscle_group') == 'core' ? 'selected' : '' }}>Core</option>
                                <option value="cardio" {{ old('target_muscle_group') == 'cardio' ? 'selected' : '' }}>Cardio</option>
                                <option value="flexibility" {{ old('target_muscle_group') == 'flexibility' ? 'selected' : '' }}>Flexibility</option>
                            </select>
                            @error('target_muscle_group')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div>
                            <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="is_active" id="is_active" 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('is_active') border-red-300 @enderror">
                                <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Instructions -->
                        <div class="md:col-span-2">
                            <label for="instructions" class="block text-sm font-medium text-gray-700 mb-2">Instructions</label>
                            <textarea name="instructions" id="instructions" rows="4" 
                                      placeholder="Enter any special instructions or notes for this workout routine"
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('instructions') border-red-300 @enderror">{{ old('instructions') }}</textarea>
                            @error('instructions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <a href="{{ route('admin.workout-routines.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Workout Routine</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
