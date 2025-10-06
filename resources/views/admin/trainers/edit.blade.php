@extends('layouts.dashboard')

@section('title', 'Edit Trainer')
@section('page-title', 'Edit Trainer')

@section('sidebar')
    @include('admin.partials.sidebar')
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Edit Trainer: {{ $trainer->first_name }} {{ $trainer->last_name }}</h1>
        <a href="{{ route('admin.trainers.index') }}" class="btn btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Trainers
        </a>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900">Trainer Information</h3>
                <p class="text-sm text-gray-600">Update the trainer details</p>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.trainers.update', $trainer->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                            <input type="text" name="first_name" id="first_name" required 
                                   value="{{ old('first_name', $trainer->first_name) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('first_name') border-red-300 @enderror">
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                            <input type="text" name="last_name" id="last_name" required 
                                   value="{{ old('last_name', $trainer->last_name) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('last_name') border-red-300 @enderror">
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="md:col-span-2">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" name="email" id="email" required 
                                   value="{{ old('email', $trainer->email) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-300 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="tel" name="phone" id="phone" 
                                   value="{{ old('phone', $trainer->phone) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-300 @enderror">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" 
                                   value="{{ old('date_of_birth', $trainer->date_of_birth ? $trainer->date_of_birth->format('Y-m-d') : '') }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('date_of_birth') border-red-300 @enderror">
                            @error('date_of_birth')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                            <select name="gender" id="gender" 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('gender') border-red-300 @enderror">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $trainer->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $trainer->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $trainer->gender) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Branch -->
                        <div>
                            <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-2">Branch *</label>
                            <select name="branch_id" id="branch_id" required 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('branch_id') border-red-300 @enderror">
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id', $trainer->branch_id) == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('branch_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Specializations -->
                        <div class="md:col-span-2">
                            <label for="specializations" class="block text-sm font-medium text-gray-700 mb-2">Specializations</label>
                            <textarea name="specializations" id="specializations" rows="3" 
                                      placeholder="Enter specializations separated by commas (e.g., Weight Training, Cardio, Yoga)"
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('specializations') border-red-300 @enderror">{{ old('specializations', is_array($trainer->specializations) ? implode(', ', $trainer->specializations) : $trainer->specializations) }}</textarea>
                            @error('specializations')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Experience Years -->
                        <div>
                            <label for="experience_years" class="block text-sm font-medium text-gray-700 mb-2">Experience (Years)</label>
                            <input type="number" name="experience_years" id="experience_years" min="0" max="50"
                                   value="{{ old('experience_years', $trainer->experience_years) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('experience_years') border-red-300 @enderror">
                            @error('experience_years')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hourly Rate -->
                        <div>
                            <label for="hourly_rate" class="block text-sm font-medium text-gray-700 mb-2">Hourly Rate ({{ \App\Services\SettingsService::getCurrencySymbol() }})</label>
                            <input type="number" name="hourly_rate" id="hourly_rate" min="0" step="0.01"
                                   value="{{ old('hourly_rate', $trainer->hourly_rate) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('hourly_rate') border-red-300 @enderror">
                            @error('hourly_rate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <textarea name="address" id="address" rows="3" 
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-300 @enderror">{{ old('address', $trainer->address) }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Profile Photo -->
                        <div class="md:col-span-2">
                            <label for="profile_image" class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                            
                            <!-- Current Photo Display -->
                            @if($trainer->profile_image)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 mb-2">Current Photo:</p>
                                    <img src="{{ asset('storage/' . $trainer->profile_image) }}" alt="Current Profile Photo" class="h-32 w-32 object-cover rounded-lg">
                                </div>
                            @endif
                            
                            <!-- Upload New Photo -->
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="profile_image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>{{ $trainer->profile_image ? 'Change photo' : 'Upload a photo' }}</span>
                                            <input id="profile_image" name="profile_image" type="file" accept="image/*" class="sr-only" onchange="previewImage(this)">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB (Optional)</p>
                                </div>
                            </div>
                            <div id="image-preview" class="mt-2 hidden">
                                <p class="text-sm text-gray-600 mb-2">New Photo Preview:</p>
                                <img id="preview-img" src="" alt="Preview" class="h-32 w-32 object-cover rounded-lg mx-auto">
                            </div>
                            @error('profile_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="md:col-span-2">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" id="status" 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-300 @enderror">
                                <option value="active" {{ old('status', $trainer->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $trainer->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="on_leave" {{ old('status', $trainer->status) == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <a href="{{ route('admin.trainers.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Trainer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function previewImage(input) {
    const file = input.files[0];
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
}
</script>
@endsection
