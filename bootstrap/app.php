<?php

declare(strict_types=1);

use App\Http\Middleware\AddSecurityHeaders;
use App\Http\Middleware\AdminAuthenticate;
use App\Http\Middleware\RequirePasswordChange;
use App\Http\Middleware\ThrottleLoginByIp;
use App\Services\Logging\KamusErrorLogger;
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
        $middleware->web(append: [
            AddSecurityHeaders::class,
        ]);

        $middleware->alias([
            'require.password.change' => RequirePasswordChange::class,
            'throttle.login'          => ThrottleLoginByIp::class,
            'admin'                   => AdminAuthenticate::class,
        ]);

        $middleware->redirectGuestsTo(fn () => route('login'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->report(function (Throwable $e): void {
            try {
                app(KamusErrorLogger::class)->report($e, request());
            } catch (Throwable $secondary) {
                error_log('KamusErrorLogger report failure: '.$secondary->getMessage());
            }
        });
    })->create();
