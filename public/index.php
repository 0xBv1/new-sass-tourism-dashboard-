<?php

// Suppress deprecation warnings from vendor packages (PHP 8.1+ compatibility)
// Must be set before autoloader to prevent warnings during class loading
ini_set('display_errors', '1');
ini_set('error_reporting', (string)(E_ALL & ~E_DEPRECATED));
error_reporting(E_ALL & ~E_DEPRECATED);

// Custom error handler to filter out deprecation warnings
$previousErrorHandler = set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    // Suppress deprecation warnings completely
    if ($errno === E_DEPRECATED || $errno === E_USER_DEPRECATED) {
        return true; // Suppress the error
    }
    // Let other errors through to be handled normally
    return false;
}, E_ALL);

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
