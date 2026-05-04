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
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->reportable(function (\Illuminate\Session\TokenMismatchException $e) {
            \Illuminate\Support\Facades\Log::error('CSRF Mismatch: ', [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'input_token' => request()->input('_token'),
                'session_token' => request()->session()->token(),
                'session_id' => request()->session()->getId(),
                'cookies' => request()->cookies->all(),
            ]);
        });
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(\App\Http\Middleware\EnforceLocalhost::class);

        $middleware->validateCsrfTokens(except: [
            '/login',
            '/logout',
            '*/logout',
            '/central-logout'
        ]);
        
        $middleware->alias([
        'role' => \App\Http\Middleware\CheckRole::class,
        'tenant' => \App\Http\Middleware\IdentifyTenant::class,
    ]);
})
    ->create();

    
