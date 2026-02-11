<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Client\ScheduleController;
use App\Http\Controllers\Client\BookingController;

use App\Http\Controllers\Admin\HallController as AdminHallController;
use App\Http\Controllers\Admin\MovieController as AdminMovieController;
use App\Http\Controllers\Admin\ShowingController as AdminShowingController;

Route::get('/', [ScheduleController::class, 'index'])->name('client.index');

Route::get('/showings/{showing}/hall', [BookingController::class, 'hall'])
     ->name('client.hall');

Route::post('/showings/{showing}/reserve', [BookingController::class, 'reserve'])
     ->name('client.reserve');

Route::get('/payment/{code}', [BookingController::class, 'payment'])
     ->name('client.payment');

Route::post('/pay/{code}', [BookingController::class, 'pay'])
     ->name('client.pay');

Route::get('/ticket/{code}', [BookingController::class, 'ticket'])
     ->name('client.ticket');

Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
         ->middleware('guest')
         ->name('admin.login');

    Route::post('/login', [AuthController::class, 'login'])
         ->middleware('guest')
         ->name('admin.login.post');

    Route::post('/logout', [AuthController::class, 'logout'])
         ->middleware('auth')
         ->name('admin.logout');

    Route::middleware('auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('admin.index');

        Route::post('/halls', [AdminHallController::class, 'store'])->name('admin.halls.store');
        Route::post('/halls/{hall}/toggle-sales', [AdminHallController::class, 'toggleSales'])->name('admin.halls.toggleSales');
        Route::patch('/halls/{hall}/config', [AdminHallController::class, 'updateConfig'])
             ->name('admin.halls.config');
        Route::patch('/halls/{hall}/prices', [AdminHallController::class, 'updatePrices'])
             ->name('admin.halls.prices');
        Route::delete('/halls/{hall}', [AdminHallController::class, 'destroy'])->name('admin.halls.destroy');

        Route::post('/movies', [AdminMovieController::class, 'store'])->name('admin.movies.store');
        Route::delete('/movies/{movie}', [AdminMovieController::class, 'destroy'])->name('admin.movies.destroy');

        Route::post('/showings', [AdminShowingController::class, 'store'])->name('admin.showings.store');
        Route::delete('/showings/{showing}', [AdminShowingController::class, 'destroy'])->name('admin.showings.destroy');
    });
});
