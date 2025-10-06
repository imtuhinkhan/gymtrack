<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NoCacheMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Add aggressive no-cache headers
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        
        // Additional headers for different types of caching
        $response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT');
        $response->headers->set('ETag', '"' . md5(time() . rand()) . '"');
        
        // Prevent proxy caching
        $response->headers->set('X-Accel-Expires', '0');
        $response->headers->set('X-Cache', 'MISS');
        $response->headers->set('X-Cache-Lookup', 'MISS');
        
        // Add cache busting parameter to response
        if ($response->headers->get('Content-Type') && str_contains($response->headers->get('Content-Type'), 'text/html')) {
            $content = $response->getContent();
            // Add cache busting script to HTML
            $cacheBusterScript = '<script>console.log("Cache buster: ' . time() . '");</script>';
            $content = str_replace('</head>', $cacheBusterScript . '</head>', $content);
            $response->setContent($content);
        }

        return $response;
    }
}
