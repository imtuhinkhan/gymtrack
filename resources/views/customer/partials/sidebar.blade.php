<div class="bg-gradient-to-br from-blue-600 to-indigo-700 min-h-screen p-6">
    <!-- Logo -->
    <div class="mb-8">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center overflow-hidden">
                @if($appLogo)
                    <img class="w-full h-full object-cover" src="{{ Storage::url($appLogo) }}" alt="{{ $appName }}">
                @else
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                @endif
            </div>
            <div>
                <h1 class="text-xl font-bold text-white">{{ $appName }}</h1>
                <p class="text-blue-200 text-sm">Member Portal</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="space-y-2">
        <!-- Dashboard -->
        <a href="{{ route('customer.dashboard') }}" 
           class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-white hover:bg-white hover:bg-opacity-20 transition-all duration-300 {{ request()->routeIs('customer.dashboard') ? 'bg-white bg-opacity-20' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- My Profile -->
        <a href="{{ route('profile.index') }}" 
           class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-white hover:bg-white hover:bg-opacity-20 transition-all duration-300 {{ request()->routeIs('profile.*') ? 'bg-white bg-opacity-20' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span>My Profile</span>
        </a>

        <!-- My Workouts -->
        <a href="{{ route('customer.workouts') }}" 
           class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-white hover:bg-white hover:bg-opacity-20 transition-all duration-300 {{ request()->routeIs('customer.workouts') ? 'bg-white bg-opacity-20' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            <span>My Workouts</span>
        </a>

        <!-- Attendance History -->
        <a href="{{ route('customer.attendance') }}" 
           class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-white hover:bg-white hover:bg-opacity-20 transition-all duration-300 {{ request()->routeIs('customer.attendance') ? 'bg-white bg-opacity-20' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
            <span>Attendance</span>
        </a>

        <!-- Membership -->
        <a href="{{ route('customer.membership') }}" 
           class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-white hover:bg-white hover:bg-opacity-20 transition-all duration-300 {{ request()->routeIs('customer.membership') ? 'bg-white bg-opacity-20' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
            <span>Membership</span>
        </a>

        <!-- Payments -->
        <a href="{{ route('customer.payments') }}" 
           class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-white hover:bg-white hover:bg-opacity-20 transition-all duration-300 {{ request()->routeIs('customer.payments') ? 'bg-white bg-opacity-20' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
            <span>Payments</span>
        </a>
    </nav>

    <!-- User Info -->
    <div class="mt-8 pt-6 border-t border-blue-500 border-opacity-30">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                <span class="text-blue-600 text-sm font-bold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </span>
            </div>
            <div>
                <p class="text-white text-sm font-medium">{{ auth()->user()->name }}</p>
                <p class="text-blue-200 text-xs">Member</p>
            </div>
        </div>
    </div>
</div>
