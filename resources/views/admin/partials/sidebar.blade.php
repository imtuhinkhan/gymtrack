<!-- Sidebar Header -->
<div class="px-4 py-4 border-b border-gray-200">
    <div class="flex items-center">
        <div class="w-8 h-8 lg:w-10 lg:h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center overflow-hidden">
            @if($appLogo)
                <img class="w-full h-full object-cover" src="{{ Storage::url($appLogo) }}" alt="{{ $appName }}">
            @else
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            @endif
        </div>
        <div class="ml-3">
            <h2 class="text-base lg:text-lg font-bold text-gray-900">{{ $appName }}</h2>
            <p class="text-xs lg:text-sm text-gray-500">Admin Panel</p>
        </div>
    </div>
</div>

<!-- Navigation Menu -->
<div class="px-2 py-4 space-y-1">
    <!-- Dashboard Link - Always visible for authenticated users -->
    <a href="{{ route('dashboard') }}" class="sidebar-item {{ request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <svg class="w-4 h-4 lg:w-5 lg:h-5 mr-2 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
        </svg>
        <span class="text-sm lg:text-base">Dashboard</span>
    </a>
    
    <!-- Admin Dashboard Link - Only for admin users -->
    <!-- @can('view-admin-dashboard')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <svg class="w-4 h-4 lg:w-5 lg:h-5 mr-2 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
        </svg>
        Admin Dashboard
    </a>
    @endcan -->
    
    @can('view_branches')
    <a href="{{ route('admin.branches.index') }}" class="sidebar-item {{ request()->routeIs('admin.branches.*') ? 'active' : '' }}">
        <svg class="w-4 h-4 lg:w-5 lg:h-5 mr-2 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
        </svg>
        <span class="text-sm lg:text-base">Branches</span>
    </a>
    @endcan
    
    @can('view_members')
    <a href="{{ route('admin.members.index') }}" class="sidebar-item {{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
        <svg class="w-4 h-4 lg:w-5 lg:h-5 mr-2 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
        </svg>
        <span class="text-sm lg:text-base">Members</span>
    </a>
    @endcan
    
    @can('view_trainers')
    <a href="{{ route('admin.trainers.index') }}" class="sidebar-item {{ request()->routeIs('admin.trainers.*') ? 'active' : '' }}">
        <svg class="w-4 h-4 lg:w-5 lg:h-5 mr-2 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
        <span class="text-sm lg:text-base">Trainers</span>
    </a>
    @endcan
    
    @can('view_packages')
    <a href="{{ route('admin.packages.index') }}" class="sidebar-item {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
        <svg class="w-4 h-4 lg:w-5 lg:h-5 mr-2 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <span class="text-sm lg:text-base">Packages</span>
    </a>
    @endcan
    
    @can('view_payments')
    <a href="{{ route('admin.payments.index') }}" class="sidebar-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
        <svg class="w-4 h-4 lg:w-5 lg:h-5 mr-2 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <span class="text-sm lg:text-base">Payments</span>
    </a>
    @endcan
    
    @can('view_attendance')
    <a href="{{ route('admin.attendance.index') }}" class="sidebar-item {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
        <svg class="w-4 h-4 lg:w-5 lg:h-5 mr-2 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
        </svg>
        <span class="text-sm lg:text-base">Attendance</span>
    </a>
    @endcan
    
    @can('view_workout_routines')
    <a href="{{ route('admin.workout-routines.index') }}" class="sidebar-item {{ request()->routeIs('admin.workout-routines.*') ? 'active' : '' }}">
        <svg class="w-4 h-4 lg:w-5 lg:h-5 mr-2 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
        </svg>
        <span class="text-sm lg:text-base">Workout Routines</span>
    </a>
    @endcan
    
    @can('view_reports')
    <a href="{{ route('admin.reports.index') }}" class="sidebar-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
        <svg class="w-4 h-4 lg:w-5 lg:h-5 mr-2 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
        </svg>
        Reports
    </a>
    @endcan
    @can('view_users')
    <a href="{{ route('admin.users.index') }}" class="sidebar-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <svg class="w-4 h-4 lg:w-5 lg:h-5 mr-2 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
        </svg>
        <span class="text-sm lg:text-base">Users</span>
    </a>
    @endcan
    
    @can('view_roles')
    <a href="{{ route('admin.roles.index') }}" class="sidebar-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
        <svg class="w-4 h-4 lg:w-5 lg:h-5 mr-2 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
        </svg>
        <span class="text-sm lg:text-base">Roles</span>
    </a>
    @endcan
    
    @can('view_permissions')
    <a href="{{ route('admin.permissions.index') }}" class="sidebar-item {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
        <svg class="w-4 h-4 lg:w-5 lg:h-5 mr-2 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
        </svg>
        Permissions
    </a>
    @endcan

    @can('view_settings')
    <a href="{{ route('admin.settings.index') }}" class="sidebar-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
        <svg class="w-4 h-4 lg:w-5 lg:h-5 mr-2 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
        </svg>
        Settings
    </a>
    @endcan

    @can('view_settings')
    <a href="{{ route('admin.pwa-settings.index') }}" class="sidebar-item {{ request()->routeIs('admin.pwa-settings.*') ? 'active' : '' }}">
        <svg class="w-4 h-4 lg:w-5 lg:h-5 mr-2 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
        </svg>
        PWA Settings
    </a>
    @endcan
    
</div>


