@extends('layouts.dashboard')

@section('title', 'System Settings')
@section('page-title', 'System Settings')


@section('sidebar')
    @include('admin.partials.sidebar')
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">System Settings</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- General Settings -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">General Settings</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.general') }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="app_name" class="block text-sm font-medium text-gray-700">Application Name</label>
                            <input type="text" id="app_name" name="app_name" value="{{ $settings['app_name'] ?? config('app.name') }}" 
                                   class="form-input mt-1 block w-full">
                        </div>
                        <div>
                            <label for="app_url" class="block text-sm font-medium text-gray-700">Application URL</label>
                            <input type="url" id="app_url" name="app_url" value="{{ $settings['app_url'] ?? config('app.url') }}" 
                                   class="form-input mt-1 block w-full">
                        </div>
                        <div>
                            <label for="app_timezone" class="block text-sm font-medium text-gray-700">Timezone</label>
                            <select id="app_timezone" name="app_timezone" class="form-select mt-1 block w-full">
                                <option value="UTC" {{ ($settings['app_timezone'] ?? 'UTC') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                <option value="America/New_York" {{ ($settings['app_timezone'] ?? '') == 'America/New_York' ? 'selected' : '' }}>Eastern Time</option>
                                <option value="America/Chicago" {{ ($settings['app_timezone'] ?? '') == 'America/Chicago' ? 'selected' : '' }}>Central Time</option>
                                <option value="America/Denver" {{ ($settings['app_timezone'] ?? '') == 'America/Denver' ? 'selected' : '' }}>Mountain Time</option>
                                <option value="America/Los_Angeles" {{ ($settings['app_timezone'] ?? '') == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time</option>
                                <option value="UTC" {{ ($settings['app_timezone'] ?? 'UTC') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                <option value="America/New_York" {{ ($settings['app_timezone'] ?? '') == 'America/New_York' ? 'selected' : '' }}>Eastern Time</option>
                                <option value="America/Chicago" {{ ($settings['app_timezone'] ?? '') == 'America/Chicago' ? 'selected' : '' }}>Central Time</option>
                                <option value="America/Denver" {{ ($settings['app_timezone'] ?? '') == 'America/Denver' ? 'selected' : '' }}>Mountain Time</option>
                                <option value="America/Los_Angeles" {{ ($settings['app_timezone'] ?? '') == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time</option>
                                <option value="Asia/Kolkata" {{ ($settings['app_timezone'] ?? '') == 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata</option>
                                <option value="Asia/Dubai" {{ ($settings['app_timezone'] ?? '') == 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai</option>
                                <option value="Asia/Karachi" {{ ($settings['app_timezone'] ?? '') == 'Asia/Karachi' ? 'selected' : '' }}>Asia/Karachi</option>
                                <option value="Asia/Kathmandu" {{ ($settings['app_timezone'] ?? '') == 'Asia/Kathmandu' ? 'selected' : '' }}>Asia/Kathmandu</option>
                                <option value="Asia/Kuala_Lumpur" {{ ($settings['app_timezone'] ?? '') == 'Asia/Kuala_Lumpur' ? 'selected' : '' }}>Asia/Kuala Lumpur</option>
                                <option value="Asia/Muscat" {{ ($settings['app_timezone'] ?? '') == 'Asia/Muscat' ? 'selected' : '' }}>Asia/Muscat</option>
                                <option value="Asia/Riyadh" {{ ($settings['app_timezone'] ?? '') == 'Asia/Riyadh' ? 'selected' : '' }}>Asia/Riyadh</option>
                                <option value="Asia/Tokyo" {{ ($settings['app_timezone'] ?? '') == 'Asia/Tokyo' ? 'selected' : '' }}>Asia/Tokyo</option>
                                <option value="Asia/Tashkent" {{ ($settings['app_timezone'] ?? '') == 'Asia/Tashkent' ? 'selected' : '' }}>Asia/Tashkent</option>
                                <option value="Asia/Tehran" {{ ($settings['app_timezone'] ?? '') == 'Asia/Tehran' ? 'selected' : '' }}>Asia/Tehran</option>
                                <option value="Asia/Thimphu" {{ ($settings['app_timezone'] ?? '') == 'Asia/Thimphu' ? 'selected' : '' }}>Asia/Thimphu</option>
                                <option value="Asia/Tokyo" {{ ($settings['app_timezone'] ?? '') == 'Asia/Tokyo' ? 'selected' : '' }}>Asia/Tokyo</option>
                                <option value="Asia/Ulaanbaatar" {{ ($settings['app_timezone'] ?? '') == 'Asia/Ulaanbaatar' ? 'selected' : '' }}>Asia/Ulaanbaatar</option>
                                <option value="Asia/Urumqi" {{ ($settings['app_timezone'] ?? '') == 'Asia/Urumqi' ? 'selected' : '' }}>Asia/Urumqi</option>
                                <option value="Asia/Vientiane" {{ ($settings['app_timezone'] ?? '') == 'Asia/Vientiane' ? 'selected' : '' }}>Asia/Vientiane</option>
                                <option value="Asia/Vladivostok" {{ ($settings['app_timezone'] ?? '') == 'Asia/Vladivostok' ? 'selected' : '' }}>Asia/Vladivostok</option>
                                <option value="Asia/Yakutsk" {{ ($settings['app_timezone'] ?? '') == 'Asia/Yakutsk' ? 'selected' : '' }}>Asia/Yakutsk</option>
                                <option value="Asia/Yangon" {{ ($settings['app_timezone'] ?? '') == 'Asia/Yangon' ? 'selected' : '' }}>Asia/Yangon</option>
                                <option value="Asia/Yekaterinburg" {{ ($settings['app_timezone'] ?? '') == 'Asia/Yekaterinburg' ? 'selected' : '' }}>Asia/Yekaterinburg</option>
                                <option value="Asia/Yerevan" {{ ($settings['app_timezone'] ?? '') == 'Asia/Yerevan' ? 'selected' : '' }}>Asia/Yerevan</option>
                                <option value="Asia/Zhongshan" {{ ($settings['app_timezone'] ?? '') == 'Asia/Zhongshan' ? 'selected' : '' }}>Asia/Zhongshan</option>
                            </select>
                        </div>
                        <div>
                            <label for="app_locale" class="block text-sm font-medium text-gray-700">Language</label>
                            <select id="app_locale" name="app_locale" class="form-select mt-1 block w-full">
                                <option value="en" {{ ($settings['app_locale'] ?? 'en') == 'en' ? 'selected' : '' }}>English</option>
                                <option value="es" {{ ($settings['app_locale'] ?? '') == 'es' ? 'selected' : '' }}>Spanish</option>
                                <option value="fr" {{ ($settings['app_locale'] ?? '') == 'fr' ? 'selected' : '' }}>French</option>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">Save General Settings</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Currency Settings -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Currency Settings</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.currency') }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="currency_code" class="block text-sm font-medium text-gray-700">Currency Code</label>
                            <select id="currency_code" name="currency_code" class="form-select mt-1 block w-full" onchange="updateCurrencySymbol()">
                                @php
                                    $currencies = \App\Helpers\CurrencyHelper::getAvailableCurrencies();
                                    $currentCode = $settings['currency_code'] ?? 'USD';
                                @endphp
                                @foreach($currencies as $code => $currency)
                                    <option value="{{ $code }}" {{ $currentCode == $code ? 'selected' : '' }}>
                                        {{ $code }} - {{ $currency['name'] }} ({{ $currency['symbol'] }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="currency_symbol" class="block text-sm font-medium text-gray-700">Currency Symbol</label>
                            <input type="text" id="currency_symbol" name="currency_symbol" 
                                   value="{{ $settings['currency_symbol'] ?? '$' }}" 
                                   class="form-input mt-1 block w-full" maxlength="10">
                            <p class="mt-1 text-sm text-gray-500">Custom currency symbol (max 10 characters)</p>
                        </div>
                        <div>
                            <label for="currency_position" class="block text-sm font-medium text-gray-700">Currency Position</label>
                            <select id="currency_position" name="currency_position" class="form-select mt-1 block w-full">
                                <option value="before" {{ ($settings['currency_position'] ?? 'before') == 'before' ? 'selected' : '' }}>
                                    Before amount (e.g., $100.00)
                                </option>
                                <option value="after" {{ ($settings['currency_position'] ?? '') == 'after' ? 'selected' : '' }}>
                                    After amount (e.g., 100.00$)
                                </option>
                            </select>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-blue-900 mb-2">Preview:</h4>
                            <p class="text-lg font-semibold text-blue-800" id="currency_preview">
                                {{ \App\Services\SettingsService::formatCurrency(1234.56) }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Currency Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- App Logo Settings -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Application Logo</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.logo') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="logo" class="block text-sm font-medium text-gray-700">Upload Logo</label>
                            <div class="mt-1 flex items-center space-x-4">
                                @if(isset($settings['app_logo']) && $settings['app_logo'])
                                    <div class="flex-shrink-0">
                                        <img class="h-16 w-16 rounded-lg object-cover border-2 border-gray-200" 
                                             src="{{ Storage::url($settings['app_logo']) }}" 
                                             alt="App Logo">
                                    </div>
                                @else
                                    <div class="flex-shrink-0">
                                        <div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center border-2 border-gray-300">
                                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <input type="file" id="logo" name="logo" accept="image/*" 
                                           class="form-input @error('logo') border-red-500 @enderror">
                                    <p class="mt-1 text-sm text-gray-500">PNG, JPG, GIF up to 2MB. Recommended size: 200x200px</p>
                                    @error('logo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">Update Logo</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- System Actions -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">System Actions</h3>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    <div>
                        <h4 class="text-md font-medium text-gray-900 mb-2">Cache Management</h4>
                        <p class="text-sm text-gray-600 mb-3">Clear application cache to refresh data and improve performance.</p>
                        <form method="POST" action="{{ route('admin.settings.clear-cache') }}" class="inline">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Clear Cache
                            </button>
                        </form>
                    </div>
                    
                    <div>
                        <h4 class="text-md font-medium text-gray-900 mb-2">Database Backup & Restore</h4>
                        <p class="text-sm text-gray-600 mb-3">Manage database backups and restore from backup files.</p>
                        <a href="{{ route('admin.backup.index') }}" class="btn btn-success">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Manage Backups
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Email Settings -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Email Settings</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.email') }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="mail_driver" class="block text-sm font-medium text-gray-700">Mail Driver</label>
                            <select id="mail_driver" name="mail_driver" class="form-select mt-1 block w-full">
                                <option value="smtp" {{ ($settings['mail_driver'] ?? 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                <option value="mailgun" {{ ($settings['mail_driver'] ?? '') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                <option value="ses" {{ ($settings['mail_driver'] ?? '') == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                            </select>
                        </div>
                        <div>
                            <label for="mail_host" class="block text-sm font-medium text-gray-700">Mail Host</label>
                            <input type="text" id="mail_host" name="mail_host" value="{{ $settings['mail_host'] ?? '' }}" 
                                   class="form-input mt-1 block w-full">
                        </div>
                        <div>
                            <label for="mail_port" class="block text-sm font-medium text-gray-700">Mail Port</label>
                            <input type="number" id="mail_port" name="mail_port" value="{{ $settings['mail_port'] ?? '587' }}" 
                                   class="form-input mt-1 block w-full">
                        </div>
                        <div>
                            <label for="mail_username" class="block text-sm font-medium text-gray-700">Username</label>
                            <input type="text" id="mail_username" name="mail_username" value="{{ $settings['mail_username'] ?? '' }}" 
                                   class="form-input mt-1 block w-full">
                        </div>
                        <div>
                            <label for="mail_password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" id="mail_password" name="mail_password" value="{{ $settings['mail_password'] ?? '' }}" 
                                   class="form-input mt-1 block w-full">
                        </div>
                        <div>
                            <label for="mail_from_address" class="block text-sm font-medium text-gray-700">From Address</label>
                            <input type="email" id="mail_from_address" name="mail_from_address" value="{{ $settings['mail_from_address'] ?? '' }}" 
                                   class="form-input mt-1 block w-full">
                        </div>
                        <div>
                            <label for="mail_from_name" class="block text-sm font-medium text-gray-700">From Name</label>
                            <input type="text" id="mail_from_name" name="mail_from_name" value="{{ $settings['mail_from_name'] ?? '' }}" 
                                   class="form-input mt-1 block w-full">
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">Save Email Settings</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
    // Currency settings JavaScript
    const currencies = @json(\App\Helpers\CurrencyHelper::getAvailableCurrencies());
    
    function updateCurrencySymbol() {
        const currencyCode = document.getElementById('currency_code').value;
        const currencySymbol = document.getElementById('currency_symbol');
        const preview = document.getElementById('currency_preview');
        
        if (currencies[currencyCode]) {
            currencySymbol.value = currencies[currencyCode].symbol;
        }
        
        updatePreview();
    }
    
    function updatePreview() {
        const symbol = document.getElementById('currency_symbol').value;
        const position = document.getElementById('currency_position').value;
        const amount = '1,234.56';
        
        const formattedAmount = position === 'before' 
            ? symbol + amount 
            : amount + symbol;
            
        document.getElementById('currency_preview').textContent = formattedAmount;
    }
    
    // Add event listeners
    document.addEventListener('DOMContentLoaded', function() {
        const currencySymbol = document.getElementById('currency_symbol');
        const currencyPosition = document.getElementById('currency_position');
        
        if (currencySymbol) {
            currencySymbol.addEventListener('input', updatePreview);
        }
        
        if (currencyPosition) {
            currencyPosition.addEventListener('change', updatePreview);
        }
    });
</script>
@endsection
