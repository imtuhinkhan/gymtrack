@extends('layouts.dashboard')

@section('title', 'PWA Settings')
@section('page-title', 'PWA Settings')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">PWA Settings</h1>
            <p class="text-gray-600">Configure Progressive Web App settings and icons</p>
        </div>
    </div>

    <!-- PWA Settings Form -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">PWA Configuration</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.pwa-settings.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Settings -->
                    <div class="space-y-4">
                        <h4 class="text-md font-medium text-gray-900">Basic Settings</h4>
                        
                        <div>
                            <label for="app_name" class="block text-sm font-medium text-gray-700 mb-1">App Name</label>
                            <input type="text" id="app_name" name="app_name" value="{{ old('app_name', $pwaSettings->app_name) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   required>
                            @error('app_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="short_name" class="block text-sm font-medium text-gray-700 mb-1">Short Name</label>
                            <input type="text" id="short_name" name="short_name" value="{{ old('short_name', $pwaSettings->short_name) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   maxlength="50" required>
                            @error('short_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="description" name="description" rows="3"
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('description', $pwaSettings->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="start_url" class="block text-sm font-medium text-gray-700 mb-1">Start URL</label>
                            <input type="text" id="start_url" name="start_url" value="{{ old('start_url', $pwaSettings->start_url) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   required>
                            @error('start_url')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="scope" class="block text-sm font-medium text-gray-700 mb-1">Scope</label>
                            <input type="text" id="scope" name="scope" value="{{ old('scope', $pwaSettings->scope) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   required>
                            @error('scope')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Display Settings -->
                    <div class="space-y-4">
                        <h4 class="text-md font-medium text-gray-900">Display Settings</h4>
                        
                        <div>
                            <label for="display" class="block text-sm font-medium text-gray-700 mb-1">Display Mode</label>
                            <select id="display" name="display" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="standalone" {{ old('display', $pwaSettings->display) == 'standalone' ? 'selected' : '' }}>Standalone</option>
                                <option value="fullscreen" {{ old('display', $pwaSettings->display) == 'fullscreen' ? 'selected' : '' }}>Fullscreen</option>
                                <option value="minimal-ui" {{ old('display', $pwaSettings->display) == 'minimal-ui' ? 'selected' : '' }}>Minimal UI</option>
                                <option value="browser" {{ old('display', $pwaSettings->display) == 'browser' ? 'selected' : '' }}>Browser</option>
                            </select>
                            @error('display')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="orientation" class="block text-sm font-medium text-gray-700 mb-1">Orientation</label>
                            <select id="orientation" name="orientation" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="portrait" {{ old('orientation', $pwaSettings->orientation) == 'portrait' ? 'selected' : '' }}>Portrait</option>
                                <option value="landscape" {{ old('orientation', $pwaSettings->orientation) == 'landscape' ? 'selected' : '' }}>Landscape</option>
                                <option value="any" {{ old('orientation', $pwaSettings->orientation) == 'any' ? 'selected' : '' }}>Any</option>
                            </select>
                            @error('orientation')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="theme_color" class="block text-sm font-medium text-gray-700 mb-1">Theme Color</label>
                            <div class="flex items-center space-x-2">
                                <input type="color" id="theme_color" name="theme_color" value="{{ old('theme_color', $pwaSettings->theme_color) }}"
                                       class="w-12 h-10 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <input type="text" value="{{ old('theme_color', $pwaSettings->theme_color) }}"
                                       class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       readonly>
                            </div>
                            @error('theme_color')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="background_color" class="block text-sm font-medium text-gray-700 mb-1">Background Color</label>
                            <div class="flex items-center space-x-2">
                                <input type="color" id="background_color" name="background_color" value="{{ old('background_color', $pwaSettings->background_color) }}"
                                       class="w-12 h-10 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <input type="text" value="{{ old('background_color', $pwaSettings->background_color) }}"
                                       class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       readonly>
                            </div>
                            @error('background_color')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="is_enabled" name="is_enabled" value="1" 
                                   {{ old('is_enabled', $pwaSettings->is_enabled) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_enabled" class="ml-2 block text-sm text-gray-900">
                                Enable PWA
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Icons Section -->
                <div class="mt-8">
                    <h4 class="text-md font-medium text-gray-900 mb-4">PWA Icons</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Icon 192x192 -->
                        <div>
                            <label for="icon_192" class="block text-sm font-medium text-gray-700 mb-1">Icon 192x192</label>
                            <input type="file" id="icon_192" name="icon_192" accept="image/png"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @if($pwaSettings->icon_192)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($pwaSettings->icon_192) }}" alt="192x192 Icon" class="w-16 h-16 object-cover rounded">
                                </div>
                            @endif
                            @error('icon_192')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Icon 512x512 -->
                        <div>
                            <label for="icon_512" class="block text-sm font-medium text-gray-700 mb-1">Icon 512x512</label>
                            <input type="file" id="icon_512" name="icon_512" accept="image/png"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @if($pwaSettings->icon_512)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($pwaSettings->icon_512) }}" alt="512x512 Icon" class="w-16 h-16 object-cover rounded">
                                </div>
                            @endif
                            @error('icon_512')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Splash Icon -->
                        <div>
                            <label for="splash_icon" class="block text-sm font-medium text-gray-700 mb-1">Splash Icon</label>
                            <input type="file" id="splash_icon" name="splash_icon" accept="image/png"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @if($pwaSettings->splash_icon)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($pwaSettings->splash_icon) }}" alt="Splash Icon" class="w-16 h-16 object-cover rounded">
                                </div>
                            @endif
                            @error('splash_icon')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end">
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update PWA Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- PWA Preview -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">PWA Preview</h3>
        </div>
        <div class="card-body">
            <div class="bg-gray-100 rounded-lg p-6">
                <div class="text-center">
                    <div class="inline-block bg-white rounded-lg shadow-lg p-4 max-w-sm">
                        @if($pwaSettings->icon_192)
                            <img src="{{ Storage::url($pwaSettings->icon_192) }}" alt="App Icon" class="w-16 h-16 mx-auto mb-4 rounded-lg">
                        @else
                            <div class="w-16 h-16 bg-blue-500 rounded-lg mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        @endif
                        <h3 class="text-lg font-semibold text-gray-900">{{ $pwaSettings->app_name }}</h3>
                        <p class="text-sm text-gray-600">{{ $pwaSettings->short_name }}</p>
                        @if($pwaSettings->description)
                            <p class="text-xs text-gray-500 mt-2">{{ $pwaSettings->description }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Sync color inputs
document.getElementById('theme_color').addEventListener('input', function() {
    this.nextElementSibling.value = this.value;
});

document.getElementById('background_color').addEventListener('input', function() {
    this.nextElementSibling.value = this.value;
});
</script>
@endsection

