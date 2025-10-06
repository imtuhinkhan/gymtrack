<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $appName ?? config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>

    <!-- PWA Meta Tags -->
    <meta name="application-name" content="{{ $appName ?? config('app.name', 'Laravel') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ $appName ?? config('app.name', 'Laravel') }}">
    <meta name="description" content="{{ $pwaSettings->description ?? 'Professional Gym Management System' }}">
    <meta name="format-detection" content="telephone=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="msapplication-config" content="/browserconfig.xml">
    <meta name="msapplication-TileColor" content="{{ $pwaSettings->theme_color ?? '#3B82F6' }}">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="theme-color" content="{{ $pwaSettings->theme_color ?? '#3B82F6' }}">

    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ route('pwa.manifest') }}">

    <!-- Apple Touch Icons -->
    @if(isset($pwaSettings) && $pwaSettings->icon_192)
        <link rel="apple-touch-icon" href="{{ Storage::url($pwaSettings->icon_192) }}">
    @endif

    <!-- Favicon -->
    @if(isset($pwaSettings) && $pwaSettings->icon_192)
        <link rel="icon" type="image/png" sizes="192x192" href="{{ Storage::url($pwaSettings->icon_192) }}">
    @endif
    @if(isset($pwaSettings) && $pwaSettings->icon_512)
        <link rel="icon" type="image/png" sizes="512x512" href="{{ Storage::url($pwaSettings->icon_512) }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Scripts with cache busting -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js with cache busting -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js?v={{ time() }}"></script>
    
    <!-- Chart.js with cache busting -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js?v={{ time() }}"></script>
    
    <!-- Cache busting meta tag -->
    <meta name="cache-buster" content="{{ time() }}">
    
    <!-- Aggressive Cache Busting Script -->
    <script>
        // Force cache invalidation
        (function() {
            const timestamp = Date.now();
            console.log('Cache buster activated:', timestamp);
            
            // Clear all caches
            if ('caches' in window) {
                caches.keys().then(function(names) {
                    names.forEach(function(name) {
                        caches.delete(name);
                    });
                });
            }
            
            // Force reload if this is a cached version
            const lastLoad = localStorage.getItem('lastPageLoad');
            const currentTime = Date.now();
            
            if (lastLoad && (currentTime - parseInt(lastLoad)) < 1000) {
                console.log('Detected cached page, forcing reload');
                window.location.reload(true);
            }
            
            localStorage.setItem('lastPageLoad', currentTime.toString());
            
            // Add timestamp to all forms to prevent form caching
            document.addEventListener('DOMContentLoaded', function() {
                const forms = document.querySelectorAll('form');
                forms.forEach(function(form) {
                    const timestampInput = document.createElement('input');
                    timestampInput.type = 'hidden';
                    timestampInput.name = '_cache_buster';
                    timestampInput.value = timestamp;
                    form.appendChild(timestampInput);
                });
            });
        })();
    </script>
    
    <!-- Custom Styles -->
    <style>
        /* Prevent horizontal scrolling */
        html, body {
            overflow-x: hidden;
            max-width: 100%;
        }
        
        /* Mobile Sidebar */
        .mobile-sidebar {
            position: fixed;
            top: 0;
            left: -100%;
            width: 280px;
            height: 100vh;
            background: white;
            z-index: 50;
            transition: left 0.3s ease;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
        }
        
        .mobile-sidebar.open {
            left: 0;
        }
        
        /* Mobile Overlay */
        .mobile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 40;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .mobile-overlay.open {
            opacity: 1;
            visibility: visible;
        }
        
        /* Mobile Header */
        .mobile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            background: white;
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 20;
        }
        
        /* Mobile Content */
        .mobile-content {
            padding: 1rem;
            min-height: calc(100vh - 140px);
            padding-bottom: 80px; /* Space for bottom nav */
            overflow-x: hidden; /* Prevent horizontal overflow */
            max-width: 100vw; /* Ensure content doesn't exceed viewport */
        }
        
        /* Mobile Navigation */
        .mobile-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-around;
            padding: 0.5rem 0;
            z-index: 30;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        }
        
        .mobile-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0.5rem;
            text-decoration: none;
            color: #6b7280;
            transition: color 0.2s;
            min-width: 60px;
        }
        
        .mobile-nav-item.active {
            color: #3b82f6;
        }
        
        .mobile-nav-item svg {
            width: 1.5rem;
            height: 1.5rem;
            margin-bottom: 0.25rem;
        }
        
        .mobile-nav-item span {
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        /* Responsive Tables */
        .table-responsive {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
            width: 100%;
            max-width: 100%;
        }
        
        .mobile-table {
            display: table !important;
            width: 100% !important;
        }
        
        /* Force all tables to be responsive */
        table {
            min-width: 100%;
            width: max-content;
        }
        
        /* Mobile table styles */
        .mobile-table {
            width: 100% !important;
            border-collapse: collapse;
            background: white;
            min-width: 600px !important; /* Ensure minimum width for horizontal scroll */
            table-layout: auto;
        }
        
        .mobile-table th,
        .mobile-table td {
            padding: 0.75rem 0.5rem !important;
            text-align: left;
            border-bottom: 1px solid #f3f4f6;
            white-space: nowrap !important; /* Prevent text wrapping */
            min-width: auto;
        }
        
        .mobile-table th {
            background: #f9fafb !important;
            font-weight: 600;
            font-size: 0.875rem;
            color: #374151;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .mobile-table td {
            font-size: 0.875rem;
        }
        
        /* Mobile-specific table fixes */
        @media (max-width: 1023px) {
            .table-responsive {
                overflow-x: scroll !important;
                -webkit-overflow-scrolling: touch !important;
                scrollbar-width: thin;
            }
            
            .mobile-table {
                min-width: 800px !important; /* Increased minimum width for better mobile experience */
            }
            
            .mobile-table th,
            .mobile-table td {
                padding: 0.5rem 0.25rem !important;
                font-size: 0.8rem !important;
            }
            
            .mobile-table th {
                background: #f8f9fa !important;
                font-weight: 600;
                color: #495057;
            }
            
            .mobile-table td {
                border-bottom: 1px solid #e9ecef !important;
            }
            
            /* Ensure tables don't break layout */
            .card-body {
                overflow-x: hidden;
                padding: 0.5rem;
            }
            
            .table-responsive {
                margin-left: -0.5rem;
                margin-right: -0.5rem;
            }
        }
        
        /* Desktop table styles */
        @media (min-width: 1024px) {
            .mobile-table th,
            .mobile-table td {
                padding: 1rem 0.75rem !important;
                white-space: normal !important; /* Allow text wrapping on desktop */
            }
            
            .mobile-table {
                min-width: auto !important; /* Remove minimum width on desktop */
            }
            
            .card-body {
                padding: 1.5rem;
            }
            
            .table-responsive {
                margin-left: 0;
                margin-right: 0;
            }
        }
        
        /* Mobile Cards */
        .mobile-card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
            overflow: hidden;
        }
        
        .mobile-card-header {
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
            background: #f9fafb;
        }
        
        .mobile-card-body {
            padding: 1rem;
        }
        
        /* Mobile Buttons */
        .mobile-btn {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            text-align: center;
            transition: all 0.2s;
        }
        
        .mobile-btn-primary {
            background: #3b82f6;
            color: white;
        }
        
        .mobile-btn-primary:hover {
            background: #2563eb;
        }
        
        /* Mobile Form Groups */
        .mobile-form-group {
            margin-bottom: 1rem;
        }
        
        .mobile-form-label {
            display: block;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        
        .mobile-form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 1rem;
        }
        
        /* Mobile Stats */
        .mobile-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .mobile-stat-card {
            background: white;
            padding: 1rem;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .mobile-stat-number {
            font-size: 1.25rem;
            font-weight: 700;
            color: #3b82f6;
        }
        
        .mobile-stat-label {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }
        
        /* Desktop Styles */
        @media (min-width: 1024px) {
            .mobile-sidebar {
                display: none !important;
            }
            
            .mobile-overlay {
                display: none !important;
            }
            
            .mobile-header {
                display: none !important;
            }
            
            .mobile-content {
                padding: 1.5rem;
                padding-bottom: 1.5rem; /* Remove bottom padding on desktop */
            }
            
            .mobile-nav {
                display: none !important;
            }
            
            .mobile-stats {
                grid-template-columns: repeat(4, 1fr);
            }
            
            .mobile-btn {
                width: auto;
            }
            
            .sidebar {
                display: block !important;
            }
        }
        
        /* Tablet Styles */
        @media (min-width: 768px) and (max-width: 1023px) {
            .mobile-stats {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        /* PWA Safe Area Support */
        @supports (padding: max(0px)) {
            .mobile-nav {
                padding-bottom: max(0.5rem, env(safe-area-inset-bottom));
            }
            
            .mobile-content {
                padding-top: max(1rem, env(safe-area-inset-top));
            }
        }
        
        /* Sidebar Styles */
        .sidebar {
            background: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
            position: relative;
            z-index: 10;
            flex-shrink: 0;
        }
        
        /* Ensure desktop sidebar is visible */
        @media (min-width: 1024px) {
            .sidebar {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
            }
        }
        
        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #6b7280;
            text-decoration: none;
            transition: all 0.2s;
            border-radius: 0.5rem;
            margin: 0.25rem 0.5rem;
        }
        
        .sidebar-item:hover {
            background: #f3f4f6;
            color: #374151;
        }
        
        .sidebar-item.active {
            background: #3b82f6;
            color: white;
        }
        
        .sidebar-item svg {
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.75rem;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex" x-data="{ sidebarOpen: false }">
        <!-- Mobile Overlay -->
        <div class="mobile-overlay" 
             :class="{ 'open': sidebarOpen }" 
             @click="sidebarOpen = false"
             x-show="sidebarOpen"></div>

        <!-- Mobile Sidebar -->
        <div class="mobile-sidebar lg:hidden" :class="{ 'open': sidebarOpen }">
            @yield('sidebar')
            @if(!View::hasSection('sidebar'))
                @include('admin.partials.sidebar')
            @endif
        </div>

        <!-- Desktop Sidebar -->
        <div class="sidebar w-64 min-h-screen hidden lg:block lg:flex-shrink-0">
            @yield('sidebar')
            @if(!View::hasSection('sidebar'))
                @include('admin.partials.sidebar')
            @endif
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- Mobile Header -->
            <header class="mobile-header lg:hidden">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-md text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    
                    <h2 class="text-lg font-semibold text-gray-900 ml-3">@yield('page-title', 'Dashboard')</h2>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                            <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                                @if(auth()->user()->profile_image)
                                    <img class="w-8 h-8 rounded-full object-cover" src="{{ auth()->user()->profile_image_url }}" alt="{{ auth()->user()->name }}">
                                @else
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                @endif
                            </div>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Desktop Header -->
            <header class="hidden lg:block bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                        
                        <div class="flex items-center space-x-4">
                            <!-- User Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                                    <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                                        @if(auth()->user()->profile_image)
                                            <img class="w-8 h-8 rounded-full object-cover" src="{{ auth()->user()->profile_image_url }}" alt="{{ auth()->user()->name }}">
                                        @else
                                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <span class="text-sm font-medium">{{ auth()->user()->name }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                    <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="mobile-content lg:p-6">
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
        
        <!-- Mobile Bottom Navigation -->
        <nav class="mobile-nav lg:hidden">
            <!-- Dashboard - Always visible -->
            <a href="{{ route('admin.dashboard') }}" class="mobile-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                </svg>
                <span>Dashboard</span>
            </a>
            
            @if(auth()->user()->hasRole(['admin', 'branch_manager']))
                <!-- Members - Admin and Branch Manager only -->
                <a href="{{ route('admin.members.index') }}" class="mobile-nav-item {{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    <span>Members</span>
                </a>
                
                <!-- Trainers - Admin and Branch Manager only -->
                <a href="{{ route('admin.trainers.index') }}" class="mobile-nav-item {{ request()->routeIs('admin.trainers.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>Trainers</span>
                </a>
                
                <!-- Payments - Admin and Branch Manager only -->
                <a href="{{ route('admin.payments.index') }}" class="mobile-nav-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <span>Payments</span>
                </a>
                
                <!-- Attendance - Admin and Branch Manager only -->
                <a href="{{ route('admin.attendance.index') }}" class="mobile-nav-item {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span>Attendance</span>
                </a>
            @else
                <!-- Profile - For other roles -->
                <a href="{{ route('profile.index') }}" class="mobile-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>Profile</span>
                </a>
            @endif
        </nav>
    </div>

    @stack('scripts')

    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('ServiceWorker registration successful');
                    })
                    .catch(function(err) {
                        console.log('ServiceWorker registration failed: ', err);
                    });
            });
        }
    </script>
</body>
</html>