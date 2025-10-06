@extends('layouts.dashboard')

@section('title', 'Database Backup & Restore')
@section('page-title', 'Database Backup & Restore')

@section('sidebar')
@include('admin.partials.sidebar')
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Database Backup & Restore</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Settings
            </a>
            <form method="POST" action="{{ route('admin.backup.create') }}" class="inline">
                @csrf
                <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to create a new backup?')">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Create New Backup
                </button>
            </form>
        </div>
    </div>

    <!-- Backup Information -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Backup Information</h3>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-primary-600">{{ count($backups) }}</div>
                    <div class="text-sm text-gray-600">Total Backups</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-success-600">
                        {{ count($backups) > 0 ? $backups[0]['formatted_size'] : '0 B' }}
                    </div>
                    <div class="text-sm text-gray-600">Latest Backup Size</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-warning-600">
                        {{ count($backups) > 0 ? $backups[0]['formatted_date'] : 'No backups' }}
                    </div>
                    <div class="text-sm text-gray-600">Latest Backup Date</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Restore Database -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Restore Database</h3>
        </div>
        <div class="card-body">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Warning</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Restoring a database will completely replace the current database with the backup data. This action cannot be undone. Make sure to create a backup before restoring.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <form method="POST" action="{{ route('admin.backup.restore') }}" enctype="multipart/form-data">
                @csrf
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label for="backup_file" class="block text-sm font-medium text-gray-700 mb-2">Select Backup File</label>
                        <input type="file" name="backup_file" id="backup_file" accept=".sql" required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('backup_file') border-red-300 @enderror">
                        <p class="mt-1 text-sm text-gray-500">Select a .sql backup file to restore (max 100MB)</p>
                        @error('backup_file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to restore the database? This will replace all current data!')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Restore Database
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Backup Files List -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Backup Files</h3>
        </div>
        <div class="card-body">
            @if(count($backups) > 0)
                <div class="table-responsive">
                    <table class="mobile-table">
                        <thead>
                            <tr>
                                <th>Filename</th>
                                <th>Size</th>
                                <th>Created</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($backups as $backup)
                                <tr>
                                    <td>
                                        <div class="flex items-center">
                                            <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <div class="text-sm font-medium text-gray-900">{{ $backup['filename'] }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $backup['formatted_size'] }}
                                    </td>
                                    <td>
                                        {{ $backup['formatted_date'] }}
                                    </td>
                                    <td class="text-right">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.backup.download', $backup['filename']) }}" 
                                               class="text-blue-600 hover:text-blue-900">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                Download
                                            </a>
                                            <form method="POST" action="{{ route('admin.backup.delete', $backup['filename']) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" 
                                                        onclick="return confirm('Are you sure you want to delete this backup?')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No backup files found</h3>
                    <p class="mt-1 text-sm text-gray-500">Create your first backup to get started.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
