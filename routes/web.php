<?php

use App\Http\Controllers\Platform\AuthController as PlatformAuthController;
use App\Http\Controllers\Platform\CompanyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Installation routes (must be before other routes)
use App\Http\Controllers\InstallController;

Route::prefix('install')->name('install.')->group(function () {
    Route::get('/', [InstallController::class, 'index'])->name('index');
    Route::get('/requirements', [InstallController::class, 'requirements'])->name('requirements');
    Route::get('/database', [InstallController::class, 'database'])->name('database');
    Route::post('/database/test', [InstallController::class, 'testDatabase'])->name('database.test');
    Route::post('/database', [InstallController::class, 'processDatabase'])->name('database.process');
    Route::get('/admin', [InstallController::class, 'admin'])->name('admin');
    Route::post('/admin', [InstallController::class, 'processAdmin'])->name('admin.process');
    Route::get('/payment', [InstallController::class, 'payment'])->name('payment');
    Route::post('/payment', [InstallController::class, 'processPayment'])->name('payment.process');
    Route::get('/email', [InstallController::class, 'email'])->name('email');
    Route::post('/email', [InstallController::class, 'processEmail'])->name('email.process');
    Route::get('/process', [InstallController::class, 'process'])->name('process');
    Route::get('/complete', [InstallController::class, 'complete'])->name('complete');
});

Route::get('/', function () {
    $installService = app(\App\Services\InstallService::class);
    if (! $installService->isInstalled()) {
        return redirect()->route('install.index');
    }

    return redirect('/platform/login');
});

Route::middleware('guest:platform')->group(function () {
    Route::get('/platform/login', [PlatformAuthController::class, 'create'])->name('platform.login');
    Route::post('/platform/login', [PlatformAuthController::class, 'store'])->name('platform.login.store');
});

Route::middleware('auth:platform')->prefix('platform')->name('platform.')->group(function () {
    Route::post('/logout', [PlatformAuthController::class, 'destroy'])->name('logout');
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
    Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('/companies/{tenant}', [CompanyController::class, 'show'])->name('companies.show');
    Route::get('/companies/{tenant}/provision', [CompanyController::class, 'provision'])->name('companies.provision');
    Route::post('/companies/{tenant}/provision', [CompanyController::class, 'provisionStep'])->name('companies.provision.step');
});
