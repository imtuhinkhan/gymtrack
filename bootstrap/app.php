<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'check.permission' => \App\Http\Middleware\CheckPermission::class,
            'check.any.permission' => \App\Http\Middleware\CheckAnyPermission::class,
            'check.all.permissions' => \App\Http\Middleware\CheckAllPermissions::class,
            'installation' => \App\Http\Middleware\InstallationMiddleware::class,
            'no.cache' => \App\Http\Middleware\NoCacheMiddleware::class,
        ]);

        // Apply NoCacheMiddleware globally to prevent caching issues
        $middleware->web(append: [
            \App\Http\Middleware\NoCacheMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
