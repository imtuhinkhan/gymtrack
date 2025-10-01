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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Custom Styles -->
    <style>
        /* Prevent horizontal scrolling */
        html, body {
            overflow-x: hidden;
            max-width: 100%;
        }
        
        /* Ensure tables are responsive */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        /* Prevent content from overflowing */
        .max-w-full {
            max-width: 100%;
        }
        
        /* Ensure proper table cell spacing */
        .table-cell-compact {
            padding: 0.75rem 0.75rem;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="sidebar w-64 min-h-screen">
            @yield('sidebar')
            @if(!View::hasSection('sidebar'))
                @include('admin.partials.sidebar')
            @endif
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h2>
                    
                    <div class="flex items-center space-x-4">
                       
                        
                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                                <img src="{{ auth()->user()->profile_image_url }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full">
                                <span class="font-medium">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
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
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6">
                @if (session('success'))
                    <div class="mb-4 bg-success-100 border border-success-400 text-success-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 bg-danger-100 border border-danger-400 text-danger-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
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

        // PWA Install Prompt
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            
            // Show install button
            const installButton = document.createElement('button');
            installButton.textContent = 'Install App';
            installButton.className = 'fixed bottom-4 right-4 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
            installButton.onclick = () => {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                    }
                    deferredPrompt = null;
                    installButton.remove();
                });
            };
            document.body.appendChild(installButton);
        });
    </script>
</body>
</html>
