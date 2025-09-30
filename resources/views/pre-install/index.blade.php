<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Initial Setup - {{ config('app.name', 'Gym Management System') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    Welcome to Gym Management System
                </h1>
                <p class="text-lg text-gray-600">Let's get started with the initial setup</p>
            </div>

            <!-- Setup Form -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">Basic Configuration</h2>
                    <p class="text-gray-600">Please provide some basic information to get started.</p>
                </div>

                <form id="setup-form" class="space-y-6">
                    <div>
                        <label for="app_name" class="block text-sm font-medium text-gray-700 mb-2">Application Name</label>
                        <input type="text" id="app_name" name="app_name" value="Gym Management System" required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="app_url" class="block text-sm font-medium text-gray-700 mb-2">Application URL</label>
                        <input type="url" id="app_url" name="app_url" value="{{ url('/') }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-sm text-gray-500 mt-1">This should be the full URL where your application will be accessible.</p>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">What's Next?</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>After this initial setup, you'll be taken to the full installation wizard where you can configure your database and create your admin account.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" id="setup-btn" class="bg-blue-600 text-white py-2 px-6 rounded-md hover:bg-blue-700 transition-colors">
                            <span id="setup-text">Continue to Installation</span>
                            <span id="setup-loading" class="hidden">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Setting up...
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="text-center text-sm text-gray-500">
                <p>Gym Management System - Professional Gym Management Solution</p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('setup-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            // Show loading state
            document.getElementById('setup-text').classList.add('hidden');
            document.getElementById('setup-loading').classList.remove('hidden');
            document.getElementById('setup-btn').disabled = true;
            
            fetch('{{ route("pre-install.create-env") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to installation wizard
                    window.location.href = data.redirect_url;
                } else {
                    alert('Setup failed: ' + data.message);
                    // Reset button state
                    document.getElementById('setup-text').classList.remove('hidden');
                    document.getElementById('setup-loading').classList.add('hidden');
                    document.getElementById('setup-btn').disabled = false;
                }
            })
            .catch(error => {
                alert('Setup error: ' + error.message);
                // Reset button state
                document.getElementById('setup-text').classList.remove('hidden');
                document.getElementById('setup-loading').classList.add('hidden');
                document.getElementById('setup-btn').disabled = false;
            });
        });
    </script>
</body>
</html>
