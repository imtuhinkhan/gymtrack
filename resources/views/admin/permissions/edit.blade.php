@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    
    <div class="main-content">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Edit Permission</h1>
                <p class="text-gray-600">Update permission details and settings</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.permissions.show', $permission) }}" class="btn btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View Permission
                </a>
                <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Permissions
                </a>
            </div>
        </div>

        <!-- Edit Permission Form -->
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.permissions.update', $permission) }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- Permission Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Permission Name *</label>
                        <input type="text" name="name" id="name" required 
                               value="{{ old('name', $permission->name) }}"
                               class="form-input @error('name') border-red-300 @enderror"
                               placeholder="Enter permission name (e.g., create_members, edit_packages)">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Use lowercase letters and underscores (e.g., create_members, edit_packages)</p>
                    </div>

                    <!-- Permission Group -->
                    <div class="mb-6">
                        <label for="group" class="block text-sm font-medium text-gray-700 mb-2">Permission Group *</label>
                        <select name="group" id="group" required 
                                class="form-select @error('group') border-red-300 @enderror">
                            <option value="">Select a group</option>
                            @foreach($groups as $key => $label)
                                <option value="{{ $key }}" {{ old('group', $permission->group) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('group')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Group permissions by functionality for better organization</p>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="description" rows="3"
                                  class="form-textarea @error('description') border-red-300 @enderror"
                                  placeholder="Enter a description for this permission">{{ old('description', $permission->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Optional description to explain what this permission allows</p>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.permissions.show', $permission) }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Permission
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate permission name from display name
    const nameInput = document.getElementById('name');
    if (nameInput) {
        nameInput.addEventListener('input', function() {
            this.value = this.value.toLowerCase().replace(/[^a-z0-9]/g, '_');
        });
    }
});
</script>
@endsection
