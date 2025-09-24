<?php

use App\Http\Controllers\Admin\ResponsableController;
use App\Http\Controllers\Responsable\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'responsable'])->group(function () {
    Route::get('responsable/dashboard', [DashboardController::class, 'dashboard'])->name('responsable.dashboard');
    Route::resource('responsables', ResponsableController::class);
});
