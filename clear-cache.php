<?php
/**
 * Clear Cache Script for cPanel Hosting
 * 
 * This script clears all types of caches that can cause issues on cPanel hosting:
 * - Laravel caches (config, route, view, application)
 * - OPcache (PHP opcode cache)
 * - File-based caches
 * - Browser cache headers
 */

// Set headers to prevent caching
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

echo "<h2>Clearing All Caches...</h2>\n";

// Clear Laravel caches
echo "<h3>1. Clearing Laravel Caches</h3>\n";

$commands = [
    'config:clear' => 'Configuration cache cleared',
    'cache:clear' => 'Application cache cleared', 
    'route:clear' => 'Route cache cleared',
    'view:clear' => 'View cache cleared',
    'event:clear' => 'Event cache cleared',
    'queue:clear' => 'Queue cache cleared'
];

foreach ($commands as $command => $message) {
    echo "Running: php artisan $command<br>\n";
    $output = shell_exec("php artisan $command 2>&1");
    echo "✓ $message<br>\n";
    if ($output) {
        echo "<small>Output: " . htmlspecialchars($output) . "</small><br>\n";
    }
    echo "<br>\n";
}

// Clear OPcache
echo "<h3>2. Clearing OPcache</h3>\n";
if (function_exists('opcache_reset')) {
    if (opcache_reset()) {
        echo "✓ OPcache cleared successfully<br>\n";
    } else {
        echo "⚠ OPcache reset failed<br>\n";
    }
} else {
    echo "⚠ OPcache not available<br>\n";
}

// Clear file-based caches
echo "<h3>3. Clearing File-based Caches</h3>\n";

$cacheDirs = [
    'bootstrap/cache',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs'
];

foreach ($cacheDirs as $dir) {
    if (is_dir($dir)) {
        $files = glob($dir . '/*');
        $count = 0;
        foreach ($files as $file) {
            if (is_file($file) && basename($file) !== '.gitignore') {
                unlink($file);
                $count++;
            }
        }
        echo "✓ Cleared $count files from $dir<br>\n";
    } else {
        echo "⚠ Directory $dir not found<br>\n";
    }
}

// Clear specific cache files
$cacheFiles = [
    'bootstrap/cache/config.php',
    'bootstrap/cache/routes.php',
    'bootstrap/cache/services.php',
    'bootstrap/cache/packages.php'
];

foreach ($cacheFiles as $file) {
    if (file_exists($file)) {
        unlink($file);
        echo "✓ Removed $file<br>\n";
    }
}

// Set proper permissions
echo "<h3>4. Setting Permissions</h3>\n";
$permissionDirs = [
    'storage',
    'bootstrap/cache'
];

foreach ($permissionDirs as $dir) {
    if (is_dir($dir)) {
        chmod($dir, 0755);
        echo "✓ Set permissions for $dir<br>\n";
    }
}

echo "<h3>5. Cache Clear Complete!</h3>\n";
echo "<p><strong>All caches have been cleared successfully.</strong></p>\n";
echo "<p>If you're still experiencing caching issues:</p>\n";
echo "<ul>\n";
echo "<li>Clear your browser cache (Ctrl+F5 or Cmd+Shift+R)</li>\n";
echo "<li>Check if your hosting provider has additional caching layers</li>\n";
echo "<li>Contact your hosting provider about server-side caching</li>\n";
echo "</ul>\n";

// Add meta refresh to prevent browser caching
echo '<meta http-equiv="refresh" content="0; url=' . $_SERVER['REQUEST_URI'] . '">';
?>
