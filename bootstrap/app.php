<?php

use App\Http\Middleware\SetLayout;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\UpdateLastSeenAt;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        using: function () {
            Route::middleware([
                'web',
                'layout:guest',
            ])->prefix('auth')->group(base_path('routes/auth.php'));

            /**
             * @url /admin/*
             */
            Route::middleware([
                'web',
                'auth:web',
                'verified',
                'layout:admin',
                'locale',
                'see',
            ])->prefix('admin')->group(base_path('routes/admin.php'));

            /**
             * @url /*
             */
            Route::middleware([
                'web',
                'layout:app',
                'locale',
            ])->group(base_path('routes/web.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'layout' => SetLayout::class,
            'locale' => SetLocale::class,
            'see' => UpdateLastSeenAt::class,
        ]);

        $middleware->redirectGuestsTo(fn (Request $request) => route('login'));
        $middleware->redirectUsersTo(fn (Request $request) => route('admin'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
