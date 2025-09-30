<div class="bg-gradient-to-br from-green-700 to-green-800 min-h-screen p-6 shadow-lg">
    <!-- Logo -->
    <div class="mb-8">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center overflow-hidden">
                @if($appLogo)
                    <img class="w-full h-full object-cover" src="{{ Storage::url($appLogo) }}" alt="{{ $appName }}">
                @else
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                @endif
            </div>
            <div>
                <h1 class="text-xl font-bold text-white">{{ $appName }}</h1>
                <p class="text-green-100 text-sm font-medium">Branch Manager Portal</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="space-y-2">
        <!-- Dashboard -->
        <a href="{{ route('branch.dashboard') }}"
           class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-white hover:bg-white hover:bg-opacity-20 transition-all duration-300 {{ request()->routeIs('branch.dashboard') ? 'bg-white bg-opacity-20' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0l-7 7-7-7m9-2h2M10 10v10a1 1 0 001 1h3"></path>
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- Members -->
        <a href="{{ route('admin.members.index') }}"
           class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-white hover:bg-white hover:bg-opacity-20 transition-all duration-300 {{ request()->routeIs('admin.members.*') ? 'bg-white bg-opacity-20' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
            <span>Members</span>
        </a>

        <!-- Trainers -->
        <a href="{{ route('admin.trainers.index') }}"
           class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-white hover:bg-white hover:bg-opacity-20 transition-all duration-300 {{ request()->routeIs('admin.trainers.*') ? 'bg-white bg-opacity-20' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span>Trainers</span>
        </a>

        <!-- Attendance -->
        <a href="{{ route('admin.attendance.index') }}"
           class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-white hover:bg-white hover:bg-opacity-20 transition-all duration-300 {{ request()->routeIs('admin.attendance.*') ? 'bg-white bg-opacity-20' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
            <span>Attendance</span>
        </a>

        <!-- Payments -->
        <a href="{{ route('admin.payments.index') }}"
           class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-white hover:bg-white hover:bg-opacity-20 transition-all duration-300 {{ request()->routeIs('admin.payments.*') ? 'bg-white bg-opacity-20' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
            <span>Payments</span>
        </a>

        <!-- Packages -->
        <a href="{{ route('admin.packages.index') }}"
           class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-white hover:bg-white hover:bg-opacity-20 transition-all duration-300 {{ request()->routeIs('admin.packages.*') ? 'bg-white bg-opacity-20' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <span>Packages</span>
        </a>

        <!-- Reports -->
        <a href="{{ route('admin.reports.index') }}"
           class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-white hover:bg-white hover:bg-opacity-20 transition-all duration-300 {{ request()->routeIs('admin.reports.*') ? 'bg-white bg-opacity-20' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <span>Reports</span>
        </a>

        <!-- My Profile -->
        <a href="{{ route('profile.index') }}"
           class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-white hover:bg-white hover:bg-opacity-20 transition-all duration-300 {{ request()->routeIs('profile.index') ? 'bg-white bg-opacity-20' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span>My Profile</span>
        </a>
    </nav>

    <!-- User Info -->
    <div class="mt-8 pt-6 border-t border-green-400 border-opacity-50">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                @if(auth()->user()->profile_image)
                    <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url(auth()->user()->profile_image) }}" alt="{{ auth()->user()->name }}">
                @else
                    <div class="h-10 w-10 rounded-full bg-green-400 flex items-center justify-center">
                        <span class="text-white text-sm font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                @endif
            </div>
            <div>
                <p class="text-white text-sm font-medium">{{ auth()->user()->name }}</p>
                <p class="text-green-100 text-xs font-medium">{{ ucfirst(auth()->user()->roles->first()->name ?? 'Branch Manager') }}</p>
            </div>
        </div>
    </div>
</div>
