<?php

use App\Http\Middleware\ClientMiddleware;
use App\Http\Middleware\SalonOwnerMiddleware;
use App\Http\Middleware\SpecialistMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
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
            'super_admin' => SuperAdminMiddleware::class,
            'salon_owner' => SalonOwnerMiddleware::class,
            'specialist' => SpecialistMiddleware::class,
            'client' => ClientMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
