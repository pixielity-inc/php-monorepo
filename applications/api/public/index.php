<?php

/**
 * |--------------------------------------------------------------------------
 * | Laravel Application Entry Point
 * |--------------------------------------------------------------------------
 * |
 * | This file is the entry point for all requests to the application. It
 * | bootstraps the framework, handles the incoming request, and sends the
 * | response back to the client.
 * |
 */

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

/**
 * |--------------------------------------------------------------------------
 * | Define Application Start Time
 * |--------------------------------------------------------------------------
 * |
 * | Record the start time of the application for performance monitoring.
 * | The LARAVEL_START constant is available throughout the application.
 * |
 */
define('LARAVEL_START', microtime(true));

/**
 * |--------------------------------------------------------------------------
 * | Check For Application Maintenance Mode
 * |--------------------------------------------------------------------------
 * |
 * | If the application is in maintenance mode, we will load the maintenance
 * | file which will display a user-friendly message to visitors. This allows
 * | you to perform updates without disrupting your users.
 * |
 */
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/**
 * |--------------------------------------------------------------------------
 * | Register The Composer Autoloader
 * |--------------------------------------------------------------------------
 * |
 * | Composer provides a convenient, automatically generated class loader for
 * | this application. We just need to utilize it! We'll simply require it
 * | into the script here so we don't need to manually load our classes.
 * |
 */

require __DIR__ . '/../vendor/autoload.php';

/**
 * |--------------------------------------------------------------------------
 * | Bootstrap Laravel & Handle The Request
 * |--------------------------------------------------------------------------
 * |
 * | Bootstrap the Laravel application and handle the incoming HTTP request.
 * | The application instance is created, the request is captured, and the
 * | response is sent back to the client.
 * |
 */

/** @var Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->handleRequest(Request::capture());
