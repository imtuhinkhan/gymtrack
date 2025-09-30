<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>

    <!-- PWA Meta Tags -->
    <meta name="application-name" content="{{ \App\Models\PwaSetting::getCurrent()->app_name }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ \App\Models\PwaSetting::getCurrent()->short_name }}">
    <meta name="description" content="{{ \App\Models\PwaSetting::getCurrent()->description }}">
    <meta name="format-detection" content="telephone=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="msapplication-config" content="/browserconfig.xml">
    <meta name="msapplication-TileColor" content="{{ \App\Models\PwaSetting::getCurrent()->theme_color }}">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="theme-color" content="{{ \App\Models\PwaSetting::getCurrent()->theme_color }}">

    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ route('admin.pwa-settings.manifest') }}">

    <!-- Apple Touch Icons -->
    @if(\App\Models\PwaSetting::getCurrent()->icon_192)
        <link rel="apple-touch-icon" href="{{ Storage::url(\App\Models\PwaSetting::getCurrent()->icon_192) }}">
    @endif
    @if(\App\Models\PwaSetting::getCurrent()->icon_512)
        <link rel="apple-touch-icon" sizes="512x512" href="{{ Storage::url(\App\Models\PwaSetting::getCurrent()->icon_512) }}">
    @endif

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="shortcut icon" href="/favicon.ico">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div id="app">
        @yield('content')
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
