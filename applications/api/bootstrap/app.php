<?php

/**
 * |--------------------------------------------------------------------------
 * | Laravel Application Bootstrap
 * |--------------------------------------------------------------------------
 * |
 * | This file bootstraps the Laravel application and configures the core
 * | services including routing, middleware, and exception handling.
 * |
 */

use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Pixielity\Foundation\Application;
use Pixielity\Localization\Middlewares\SetLocale;
use Pixielity\Localization\Middlewares\TimezoneMiddleware;

/**
 * |--------------------------------------------------------------------------
 * | Create The Application
 * |--------------------------------------------------------------------------
 * |
 * | The first thing we will do is create a new Laravel application instance
 * | which serves as the "glue" for all the components of Laravel, and is
 * | the IoC container for the system binding all of the various parts.
 * |
 */

return Application::configure(basePath: dirname(__DIR__))

    /**
     * |--------------------------------------------------------------------------
     * | Register Application Routes
     * |--------------------------------------------------------------------------
     * |
     * | Configure the application's routing. The web routes file contains
     * | routes for your web interface. The health check endpoint provides
     * | a simple way to verify the application is running.
     * |
     */
    ->withRouting(
        health: '/up',
    )

    /**
     * |--------------------------------------------------------------------------
     * | Register Middleware
     * |--------------------------------------------------------------------------
     * |
     * | Configure the application's middleware stack. Middleware provide a
     * | convenient mechanism for filtering HTTP requests entering your
     * | application. You can add global, route, and group middleware here.
     * |
     * | Example:
     * | $middleware->web(append: [
     * |     \App\Http\Middleware\HandleInertiaRequests::class,
     * | ]);
     * |
     */
    ->withMiddleware(function (Middleware $middleware): void {
        // Register localization middleware for API routes
        $middleware->api(append: [
            SetLocale::class,
            TimezoneMiddleware::class,
        ]);
    })

    /**
     * |--------------------------------------------------------------------------
     * | Register Exception Handler
     * |--------------------------------------------------------------------------
     * |
     * | Configure how exceptions are reported and rendered. You can customize
     * | exception handling, add custom exception reporters, or define how
     * | specific exceptions should be rendered to the user.
     * |
     * | Example:
     * | $exceptions->report(function (InvalidOrderException $e) {
     * |     // Custom exception reporting logic
     * | });
     * |
     */
    ->withExceptions(function (Exceptions $exceptions): void {
        // Register custom exception handlers here
    })

    /**
     * |--------------------------------------------------------------------------
     * | Return The Application
     * |--------------------------------------------------------------------------
     * |
     * | This script returns the application instance. The instance is given to
     * | the calling script so we can separate the building of the instances
     * | from the actual running of the application and sending responses.
     * |
     */
    ->create();
