<?php
/**
 * Cache Test Script
 * 
 * This script tests if caching is properly disabled
 */

// Prevent caching of this script
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
    <title>Cache Test - Gym Management System</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .test-item { margin: 10px 0; padding: 10px; background: #f8f9fa; border-radius: 4px; }
        .success { color: #28a745; font-weight: bold; }
        .warning { color: #ffc107; font-weight: bold; }
        .info { color: #17a2b8; font-weight: bold; }
        .timestamp { font-family: monospace; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Cache Test</h1>
        <p>This page tests if caching is properly disabled.</p>

        <div class="test-item">
            <h3>Current Timestamp</h3>
            <div class="timestamp"><?php echo date('Y-m-d H:i:s'); ?></div>
            <div class="info">If this timestamp updates on refresh, caching is disabled ✅</div>
        </div>

        <div class="test-item">
            <h3>Random Number</h3>
            <div class="timestamp"><?php echo rand(1000, 9999); ?></div>
            <div class="info">This number should change on every refresh</div>
        </div>

        <div class="test-item">
            <h3>Server Headers</h3>
            <div class="timestamp">
                Cache-Control: <?php echo $_SERVER['HTTP_CACHE_CONTROL'] ?? 'Not set'; ?><br>
                Pragma: <?php echo $_SERVER['HTTP_PRAGMA'] ?? 'Not set'; ?><br>
                Expires: <?php echo $_SERVER['HTTP_EXPIRES'] ?? 'Not set'; ?>
            </div>
        </div>

        <div class="test-item">
            <h3>Test Instructions</h3>
            <ol>
                <li>Refresh this page (F5 or Ctrl+R)</li>
                <li>Check if the timestamp and random number change</li>
                <li>If they change, caching is disabled ✅</li>
                <li>If they stay the same, caching is still active ❌</li>
            </ol>
        </div>

        <div style="text-align: center; margin-top: 20px;">
            <button onclick="location.reload(true)" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
                🔄 Refresh Test
            </button>
        </div>
    </div>

    <script>
        // Auto-refresh every 5 seconds to test caching
        let refreshCount = 0;
        const maxRefreshes = 3;
        
        function autoRefresh() {
            if (refreshCount < maxRefreshes) {
                refreshCount++;
                setTimeout(() => {
                    location.reload(true);
                }, 5000);
            }
        }
        
        // Start auto-refresh
        autoRefresh();
    </script>
</body>
</html>
