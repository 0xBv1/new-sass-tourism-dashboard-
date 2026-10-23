<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenantStorageController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    require __DIR__.'/auth.php';
    require __DIR__.'/admin.php';

    Route::get('/', function () {
        return redirect()->route('login');
    });

    Route::get('/storage/{path}', [TenantStorageController::class, 'show'])
        ->where('path', '.*')
        ->name('tenant.storage');

    Route::get('/dashboard', function () {
        return view('dashboard.home.index');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});
