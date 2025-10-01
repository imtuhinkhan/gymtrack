# cPanel Caching Issues - Complete Solution

## Problem Description
After deploying to cPanel, you experience:
- Changes not showing immediately
- After hard reload, changes appear
- After normal refresh, changes disappear
- Changes reappear after another hard reload

This is caused by multiple layers of caching on cPanel hosting.

## Root Causes

### 1. **OPcache (PHP Opcode Cache)**
- PHP caches compiled code for performance
- Changes to PHP files don't reflect until OPcache is cleared
- Most common cause of the issue

### 2. **Laravel Caches**
- Configuration cache (`config:cache`)
- Route cache (`route:cache`) 
- View cache (`view:cache`)
- Application cache (`cache:cache`)

### 3. **Browser Caching**
- Browser caches static assets
- Hard reload bypasses cache, normal refresh uses cache

### 4. **Server-side Caching**
- cPanel hosting providers often have additional caching layers
- CDN caching
- Reverse proxy caching

## Solutions

### Solution 1: Use the Clear Cache Script
1. Upload `clear-cache.php` to your project root
2. Access it via browser: `https://yourdomain.com/clear-cache.php`
3. This will clear all types of caches automatically

### Solution 2: Manual Cache Clearing
Run these commands via SSH or cPanel Terminal:

```bash
# Clear Laravel caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

# Clear OPcache (if available)
php -r "if (function_exists('opcache_reset')) opcache_reset();"

# Clear file-based caches
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*
```

### Solution 3: Disable Caching in Development
Add to your `.env` file:
```env
APP_ENV=local
APP_DEBUG=true
CACHE_DRIVER=array
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### Solution 4: Use No-Cache Middleware
The `NoCacheMiddleware` has been created and registered. Apply it to routes that need immediate updates:

```php
Route::middleware('no.cache')->group(function () {
    // Routes that need immediate updates
});
```

### Solution 5: Browser Cache Busting
Add version parameters to your assets:
```html
<link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ time() }}">
<script src="{{ asset('js/app.js') }}?v={{ time() }}"></script>
```

## Prevention Strategies

### 1. **Environment-based Caching**
Only enable caching in production:
```php
// In AppServiceProvider.php
if (app()->environment('production')) {
    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');
}
```

### 2. **Development Mode**
Always use development mode during development:
```env
APP_ENV=local
APP_DEBUG=true
```

### 3. **Cache Invalidation**
Implement proper cache invalidation when data changes:
```php
// Clear specific caches when data changes
Cache::forget('key');
Cache::flush();
```

## cPanel Specific Solutions

### 1. **Disable OPcache in cPanel**
- Go to cPanel → PHP Selector
- Disable OPcache extension
- Or set `opcache.enable=0` in php.ini

### 2. **Clear Server-side Caches**
- Contact your hosting provider
- Ask them to clear server-side caches
- Some providers have cache clearing tools in cPanel

### 3. **Use Development Subdomain**
- Create a subdomain for development
- Use different caching settings for development vs production

## Testing Cache Clearing

### 1. **Test Script**
Create a simple test file to verify changes are reflected:
```php
<?php
echo "Current time: " . date('Y-m-d H:i:s');
echo "<br>File modified: " . date('Y-m-d H:i:s', filemtime(__FILE__));
?>
```

### 2. **Version Check**
Add a version endpoint to your application:
```php
Route::get('/version', function () {
    return [
        'version' => '1.0.0',
        'timestamp' => time(),
        'file_time' => filemtime(__FILE__)
    ];
});
```

## Emergency Cache Clearing

If you can't access SSH or Terminal:

1. **Via File Manager**
   - Delete files in `bootstrap/cache/`
   - Delete files in `storage/framework/cache/`
   - Delete files in `storage/framework/sessions/`
   - Delete files in `storage/framework/views/`

2. **Via Database**
   - Clear cache table if using database cache driver
   - Truncate cache table

3. **Contact Hosting Provider**
   - Ask them to clear OPcache
   - Ask them to clear server-side caches

## Best Practices

1. **Never cache in development**
2. **Use version control for cache clearing**
3. **Implement proper cache invalidation**
4. **Monitor cache performance**
5. **Use appropriate cache drivers for environment**

## Troubleshooting

### Issue: Changes still not showing
- Check if OPcache is enabled
- Verify cache drivers in `.env`
- Check server-side caching
- Contact hosting provider

### Issue: Performance problems after clearing cache
- Re-enable appropriate caches for production
- Use Redis or Memcached for better performance
- Implement selective caching

### Issue: Cache clearing not working
- Check file permissions
- Verify PHP version compatibility
- Check for syntax errors in cache files

## Files Created/Modified

1. `clear-cache.php` - Comprehensive cache clearing script
2. `app/Http/Middleware/NoCacheMiddleware.php` - Middleware to prevent caching
3. `bootstrap/app.php` - Registered no-cache middleware
4. `app/Http/Controllers/Admin/BranchController.php` - Added permission checks
5. `routes/web.php` - Updated branch routes with proper permissions

## Usage Instructions

1. **For Development**: Use `clear-cache.php` whenever you make changes
2. **For Production**: Implement proper cache invalidation
3. **For Emergency**: Use manual cache clearing methods
4. **For Prevention**: Follow best practices and use appropriate middleware

This solution addresses all common caching issues on cPanel hosting and provides multiple fallback options.
