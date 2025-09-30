<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Installation - DCODER</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">
                    DCODER
                </h1>
                <p class="text-xl text-gray-600">Installation Wizard</p>
            </div>

            <!-- Installation Steps -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <!-- Progress Bar -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Installation Progress</span>
                        <span class="text-sm font-medium text-gray-700" id="progress-text">Step 1 of 4</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" id="progress-bar" style="width: 25%"></div>
                    </div>
                </div>

                <!-- Step 1: Requirements Check -->
                <div id="step-1" class="step">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">System Requirements</h2>
                    <div id="requirements-list" class="space-y-3 mb-6">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-gray-700">Checking requirements...</span>
                            <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-600"></div>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button id="next-1" class="btn btn-primary" disabled>
                            Next Step
                        </button>
                    </div>
                </div>

                <!-- Step 2: Database Configuration -->
                <div id="step-2" class="step hidden">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Database Configuration</h2>
                    <form id="database-form" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="db_host" class="block text-sm font-medium text-gray-700 mb-2">Database Host</label>
                                <input type="text" id="db_host" name="db_host" value="127.0.0.1" required
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="db_port" class="block text-sm font-medium text-gray-700 mb-2">Database Port</label>
                                <input type="number" id="db_port" name="db_port" value="3306" required
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="db_name" class="block text-sm font-medium text-gray-700 mb-2">Database Name</label>
                                <input type="text" id="db_name" name="db_name" required
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="db_username" class="block text-sm font-medium text-gray-700 mb-2">Database Username</label>
                                <input type="text" id="db_username" name="db_username" required
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="md:col-span-2">
                                <label for="db_password" class="block text-sm font-medium text-gray-700 mb-2">Database Password</label>
                                <input type="password" id="db_password" name="db_password"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <button type="button" id="back-2" class="btn btn-outline">
                                Previous
                            </button>
                            <button type="button" id="test-db" class="btn btn-secondary">
                                Test Connection
                            </button>
                            <button type="button" id="next-2" class="btn btn-primary" disabled>
                                Next Step
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Step 3: Application Configuration -->
                <div id="step-3" class="step hidden">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Application Configuration</h2>
                    <form id="app-form" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="app_name" class="block text-sm font-medium text-gray-700 mb-2">Application Name</label>
                                <input type="text" id="app_name" name="app_name" value="Gym Management System" required
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="app_url" class="block text-sm font-medium text-gray-700 mb-2">Application URL</label>
                                <input type="url" id="app_url" name="app_url" value="{{ url('/') }}" required
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <button type="button" id="back-3" class="btn btn-outline">
                                Previous
                            </button>
                            <button type="button" id="next-3" class="btn btn-primary">
                                Next Step
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Step 4: Admin Account -->
                <div id="step-4" class="step hidden">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Create Admin Account</h2>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Important Notice</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>This will create the first admin account for your gym management system. Only administrators can create additional user accounts after installation.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form id="admin-form" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="admin_name" class="block text-sm font-medium text-gray-700 mb-2">Admin Name</label>
                                <input type="text" id="admin_name" name="admin_name" required
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-2">Admin Email</label>
                                <input type="email" id="admin_email" name="admin_email" required
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="admin_password" class="block text-sm font-medium text-gray-700 mb-2">Admin Password</label>
                                <input type="password" id="admin_password" name="admin_password" required minlength="8"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <p class="text-sm text-gray-500 mt-1">Password must be at least 8 characters long.</p>
                            </div>
                            <div>
                                <label for="admin_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                                <input type="password" id="admin_password_confirmation" name="admin_password_confirmation" required minlength="8"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <p class="text-sm text-gray-500 mt-1">Please confirm your password.</p>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <button type="button" id="back-4" class="btn btn-outline">
                                Previous
                            </button>
                            <button type="button" id="install-btn" class="btn btn-primary">
                                Install System
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Installation Progress -->
                <div id="step-installing" class="step hidden">
                    <div class="text-center">
                        <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-blue-600 mx-auto mb-6"></div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Installing System...</h2>
                        <p class="text-gray-600 mb-6">Please wait while we set up your gym management system.</p>
                        <div id="install-progress" class="space-y-2 text-sm text-gray-600">
                            <div>Preparing installation...</div>
                        </div>
                    </div>
                </div>

                <!-- Success -->
                <div id="step-success" class="step hidden">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Installation Complete!</h2>
                        <p class="text-gray-600 mb-6">Your gym management system has been successfully installed.</p>
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            Go to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentStep = 1;
        const totalSteps = 4;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            checkRequirements();
            setupEventListeners();
        });

        function setupEventListeners() {
            // Step navigation
            document.getElementById('next-1').addEventListener('click', () => goToStep(2));
            document.getElementById('next-2').addEventListener('click', () => goToStep(3));
            document.getElementById('next-3').addEventListener('click', () => goToStep(4));
            document.getElementById('back-2').addEventListener('click', () => goToStep(1));
            document.getElementById('back-3').addEventListener('click', () => goToStep(2));
            document.getElementById('back-4').addEventListener('click', () => goToStep(3));

            // Database test
            document.getElementById('test-db').addEventListener('click', testDatabase);

            // Install
            document.getElementById('install-btn').addEventListener('click', installSystem);
        }

        function goToStep(step) {
            // Hide current step
            document.getElementById(`step-${currentStep}`).classList.add('hidden');
            
            // Show new step
            currentStep = step;
            document.getElementById(`step-${currentStep}`).classList.remove('hidden');
            
            // Update progress
            updateProgress();
        }

        function updateProgress() {
            const progress = (currentStep / totalSteps) * 100;
            document.getElementById('progress-bar').style.width = `${progress}%`;
            document.getElementById('progress-text').textContent = `Step ${currentStep} of ${totalSteps}`;
        }

        function checkRequirements() {
            fetch('{{ route("install.check-requirements") }}')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('requirements-list');
                    container.innerHTML = '';
                    
                    Object.entries(data.requirements).forEach(([requirement, passed]) => {
                        const div = document.createElement('div');
                        div.className = `flex items-center justify-between p-3 rounded-lg ${passed ? 'bg-green-50' : 'bg-red-50'}`;
                        div.innerHTML = `
                            <span class="${passed ? 'text-green-700' : 'text-red-700'}">${requirement}</span>
                            <svg class="w-5 h-5 ${passed ? 'text-green-600' : 'text-red-600'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                ${passed ? 
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>' :
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                                }
                            </svg>
                        `;
                        container.appendChild(div);
                    });
                    
                    document.getElementById('next-1').disabled = !data.all_passed;
                })
                .catch(error => {
                    console.error('Error checking requirements:', error);
                });
        }

        function testDatabase() {
            const formData = new FormData(document.getElementById('database-form'));
            const data = Object.fromEntries(formData);
            
            fetch('{{ route("install.check-database") }}', {
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
                    alert('Database connection successful!');
                    document.getElementById('next-2').disabled = false;
                } else {
                    alert('Database connection failed: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error testing database connection: ' + error.message);
            });
        }

        function installSystem() {
            // Validate admin form first
            if (!validateAdminForm()) {
                return;
            }
            
            // Collect all form data
            const dbData = Object.fromEntries(new FormData(document.getElementById('database-form')));
            const appData = Object.fromEntries(new FormData(document.getElementById('app-form')));
            const adminData = Object.fromEntries(new FormData(document.getElementById('admin-form')));
            
            const installData = { ...dbData, ...appData, ...adminData };
            
            // Show installing step
            document.getElementById(`step-${currentStep}`).classList.add('hidden');
            document.getElementById('step-installing').classList.remove('hidden');
            
            // Update progress
            document.getElementById('progress-bar').style.width = '100%';
            document.getElementById('progress-text').textContent = 'Installing...';
            
            // Simulate installation steps
            const steps = [
                'Updating configuration...',
                'Running database migrations...',
                'Creating roles and permissions...',
                'Creating admin account...',
                'Finalizing installation...'
            ];
            
            let stepIndex = 0;
            const progressDiv = document.getElementById('install-progress');
            
            const stepInterval = setInterval(() => {
                if (stepIndex < steps.length) {
                    progressDiv.innerHTML += `<div>✓ ${steps[stepIndex]}</div>`;
                    stepIndex++;
                } else {
                    clearInterval(stepInterval);
                    // Make actual installation request
                    performInstallation(installData);
                }
            }, 1000);
        }

        function validateAdminForm() {
            const adminName = document.getElementById('admin_name').value.trim();
            const adminEmail = document.getElementById('admin_email').value.trim();
            const adminPassword = document.getElementById('admin_password').value;
            const adminPasswordConfirmation = document.getElementById('admin_password_confirmation').value;
            
            // Clear previous error messages
            clearErrorMessages();
            
            let isValid = true;
            
            // Validate admin name
            if (adminName.length < 2) {
                showError('admin_name', 'Admin name must be at least 2 characters long');
                isValid = false;
            }
            
            // Validate email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(adminEmail)) {
                showError('admin_email', 'Please enter a valid email address');
                isValid = false;
            }
            
            // Validate password
            if (adminPassword.length < 8) {
                showError('admin_password', 'Password must be at least 8 characters long');
                isValid = false;
            }
            
            // Validate password confirmation
            if (adminPassword !== adminPasswordConfirmation) {
                showError('admin_password_confirmation', 'Password confirmation does not match');
                isValid = false;
            }
            
            return isValid;
        }

        function showError(fieldId, message) {
            const field = document.getElementById(fieldId);
            const errorDiv = document.createElement('div');
            errorDiv.className = 'text-red-600 text-sm mt-1 error-message';
            errorDiv.textContent = message;
            field.parentNode.appendChild(errorDiv);
            field.classList.add('border-red-500');
        }

        function clearErrorMessages() {
            // Remove existing error messages
            document.querySelectorAll('.error-message').forEach(el => el.remove());
            // Remove error styling
            document.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));
        }

        function performInstallation(data) {
            fetch('{{ route("install.install") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    // If not JSON, return the text content
                    return response.text().then(text => {
                        throw new Error('Server returned HTML instead of JSON. Response: ' + text.substring(0, 200));
                    });
                }
            })
            .then(data => {
                if (data.success) {
                    // Show success
                    document.getElementById('step-installing').classList.add('hidden');
                    document.getElementById('step-success').classList.remove('hidden');
                } else {
                    alert('Installation failed: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Installation error:', error);
                alert('Installation error: ' + error.message);
            });
        }
    </script>
</body>
</html>
