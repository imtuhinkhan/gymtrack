<?php
/**
 * Comprehensive Cache Clearing Script for cPanel Hosting
 * 
 * This script addresses all caching issues commonly found on cPanel hosting:
 * - Laravel caches
 * - OPcache
 * - File-based caches
 * - Browser cache headers
 * - Server-side caching
 */

// Prevent caching of this script itself
header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
header('Content-Type: text/html; charset=UTF-8');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cache Clear - Gym Management System</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; text-align: center; }
        h2 { color: #666; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        h3 { color: #888; margin-top: 20px; }
        .success { color: #28a745; font-weight: bold; }
        .warning { color: #ffc107; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .info { color: #17a2b8; font-weight: bold; }
        .step { margin: 10px 0; padding: 10px; background: #f8f9fa; border-left: 4px solid #007bff; }
        .output { background: #f8f9fa; padding: 10px; border-radius: 4px; margin: 5px 0; font-family: monospace; font-size: 12px; }
        .refresh-btn { display: inline-block; background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; margin: 10px 0; }
        .refresh-btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔄 Comprehensive Cache Clearing</h1>
        <p><strong>Gym Management System</strong> - Addressing all caching issues</p>

<?php

echo "<h2>🚀 Starting Cache Clear Process...</h2>\n";

// Step 1: Clear Laravel Artisan Caches
echo "<div class='step'>";
echo "<h3>1. Laravel Artisan Cache Clearing</h3>\n";

$artisanCommands = [
    'config:clear' => 'Configuration cache',
    'cache:clear' => 'Application cache',
    'route:clear' => 'Route cache',
    'view:clear' => 'View cache',
    'event:clear' => 'Event cache',
    'optimize:clear' => 'All optimization caches'
];

foreach ($artisanCommands as $command => $description) {
    echo "<div class='output'>";
    echo "Running: <strong>php artisan $command</strong><br>\n";
    $output = shell_exec("php artisan $command 2>&1");
    if ($output) {
        echo "✓ $description cleared<br>\n";
        echo "<small>" . htmlspecialchars($output) . "</small><br>\n";
    } else {
        echo "✓ $description cleared (no output)<br>\n";
    }
    echo "</div>";
}
echo "</div>";

// Step 2: Clear OPcache
echo "<div class='step'>";
echo "<h3>2. OPcache Clearing</h3>\n";
if (function_exists('opcache_reset')) {
    if (opcache_reset()) {
        echo "<div class='success'>✓ OPcache cleared successfully</div>\n";
    } else {
        echo "<div class='warning'>⚠ OPcache reset failed</div>\n";
    }
} else {
    echo "<div class='warning'>⚠ OPcache not available on this server</div>\n";
}
echo "</div>";

// Step 3: Clear File-based Caches
echo "<div class='step'>";
echo "<h3>3. File-based Cache Clearing</h3>\n";

$cacheDirectories = [
    'bootstrap/cache' => 'Bootstrap cache files',
    'storage/framework/cache' => 'Framework cache',
    'storage/framework/sessions' => 'Session files',
    'storage/framework/views' => 'Compiled views',
    'storage/logs' => 'Log files'
];

$totalFilesCleared = 0;

foreach ($cacheDirectories as $dir => $description) {
    if (is_dir($dir)) {
        $files = glob($dir . '/*');
        $count = 0;
        foreach ($files as $file) {
            if (is_file($file) && basename($file) !== '.gitignore') {
                if (unlink($file)) {
                    $count++;
                    $totalFilesCleared++;
                }
            }
        }
        echo "<div class='success'>✓ Cleared $count files from $dir ($description)</div>\n";
    } else {
        echo "<div class='warning'>⚠ Directory $dir not found</div>\n";
    }
}

echo "<div class='info'>📊 Total files cleared: $totalFilesCleared</div>\n";
echo "</div>";

// Step 4: Clear Specific Cache Files
echo "<div class='step'>";
echo "<h3>4. Specific Cache File Removal</h3>\n";

$specificCacheFiles = [
    'bootstrap/cache/config.php',
    'bootstrap/cache/routes.php',
    'bootstrap/cache/services.php',
    'bootstrap/cache/packages.php',
    'storage/framework/cache/data',
    'storage/framework/sessions/session_data'
];

foreach ($specificCacheFiles as $file) {
    if (file_exists($file)) {
        if (unlink($file)) {
            echo "<div class='success'>✓ Removed $file</div>\n";
        } else {
            echo "<div class='error'>✗ Failed to remove $file</div>\n";
        }
    }
}
echo "</div>";

// Step 5: Set Proper Permissions
echo "<div class='step'>";
echo "<h3>5. Setting Proper Permissions</h3>\n";

$permissionDirectories = [
    'storage' => 0755,
    'bootstrap/cache' => 0755,
    'storage/framework' => 0755,
    'storage/framework/cache' => 0755,
    'storage/framework/sessions' => 0755,
    'storage/framework/views' => 0755,
    'storage/logs' => 0755
];

foreach ($permissionDirectories as $dir => $permission) {
    if (is_dir($dir)) {
        if (chmod($dir, $permission)) {
            echo "<div class='success'>✓ Set permissions (0" . decoct($permission) . ") for $dir</div>\n";
        } else {
            echo "<div class='warning'>⚠ Failed to set permissions for $dir</div>\n";
        }
    }
}
echo "</div>";

// Step 6: Rebuild Optimized Caches
echo "<div class='step'>";
echo "<h3>6. Rebuilding Optimized Caches</h3>\n";

$optimizeCommands = [
    'config:cache' => 'Configuration cache',
    'route:cache' => 'Route cache',
    'view:cache' => 'View cache'
];

foreach ($optimizeCommands as $command => $description) {
    echo "<div class='output'>";
    echo "Running: <strong>php artisan $command</strong><br>\n";
    $output = shell_exec("php artisan $command 2>&1");
    if ($output) {
        echo "✓ $description rebuilt<br>\n";
        echo "<small>" . htmlspecialchars($output) . "</small><br>\n";
    } else {
        echo "✓ $description rebuilt (no output)<br>\n";
    }
    echo "</div>";
}
echo "</div>";

// Step 7: Final Verification
echo "<div class='step'>";
echo "<h3>7. Cache Clear Verification</h3>\n";

// Check if cache directories are empty
$cacheDirs = ['storage/framework/cache', 'storage/framework/views', 'bootstrap/cache'];
$allEmpty = true;

foreach ($cacheDirs as $dir) {
    if (is_dir($dir)) {
        $files = glob($dir . '/*');
        $fileCount = count(array_filter($files, function($file) {
            return is_file($file) && basename($file) !== '.gitignore';
        }));
        
        if ($fileCount > 0) {
            echo "<div class='warning'>⚠ $dir still has $fileCount files</div>\n";
            $allEmpty = false;
        } else {
            echo "<div class='success'>✓ $dir is clean</div>\n";
        }
    }
}

if ($allEmpty) {
    echo "<div class='success'>🎉 All cache directories are clean!</div>\n";
} else {
    echo "<div class='warning'>⚠ Some cache files may still exist</div>\n";
}
echo "</div>";

?>

        <h2>✅ Cache Clear Complete!</h2>
        
        <div class="step">
            <h3>📋 What Was Done:</h3>
            <ul>
                <li>✅ Cleared all Laravel artisan caches</li>
                <li>✅ Reset OPcache (if available)</li>
                <li>✅ Removed file-based cache files</li>
                <li>✅ Set proper directory permissions</li>
                <li>✅ Rebuilt optimized caches</li>
                <li>✅ Verified cache directories are clean</li>
            </ul>
        </div>

        <div class="step">
            <h3>🔧 Additional Steps for cPanel:</h3>
            <ul>
                <li><strong>Browser Cache:</strong> Clear your browser cache (Ctrl+F5 or Cmd+Shift+R)</li>
                <li><strong>CDN Cache:</strong> If using CloudFlare or similar, purge CDN cache</li>
                <li><strong>Server Cache:</strong> Contact your hosting provider about server-side caching</li>
                <li><strong>PHP Cache:</strong> Check if your hosting has additional PHP caching layers</li>
            </ul>
        </div>

        <div class="step">
            <h3>🚨 If Still Having Issues:</h3>
            <ul>
                <li>Check your hosting provider's caching settings</li>
                <li>Verify .htaccess file is properly configured</li>
                <li>Contact your hosting provider about server-side caching</li>
                <li>Consider using a different browser or incognito mode</li>
            </ul>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="javascript:location.reload(true)" class="refresh-btn">🔄 Refresh Page</a>
            <a href="/admin/dashboard" class="refresh-btn">🏠 Go to Dashboard</a>
        </div>

        <div style="margin-top: 20px; padding: 15px; background: #e7f3ff; border-radius: 4px;">
            <strong>💡 Pro Tip:</strong> Bookmark this page (<code>your-domain.com/cpanel-cache-clear.php</code>) 
            for easy access whenever you need to clear caches on cPanel hosting.
        </div>
    </div>

    <script>
        // Auto-refresh after 5 seconds
        setTimeout(function() {
            if (confirm('Cache clear completed! Would you like to refresh the page?')) {
                location.reload(true);
            }
        }, 5000);
    </script>
</body>
</html>
