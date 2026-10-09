<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Alias middleware yang sudah ada
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'legacy-user' => \App\Http\Middleware\EnsureLegacyUser::class,
        ]);

        // Trust the local Cloudflare Tunnel proxy so HTTPS URLs and redirects use
        // the public host instead of the local HTTP origin.
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
