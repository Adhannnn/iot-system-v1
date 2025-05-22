<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\loginController;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SensorController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [loginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [loginController::class, 'login']);

Route::middleware([PreventBackHistory::class, 'auth'])->group(function () {
    Route::get('/dashboard', [RoomController::class, 'index'])->name('dashboard');

    // Room Routes
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');

    // These routes should return partial content for SPA behavior
    Route::get('/rooms/{room}', [RoomController::class, 'show'])
        ->name('rooms.show')
        ->middleware('can:view,room');

    // Export route
    Route::get('/rooms/{room}/export', [SensorController::class, 'export'])
        ->name('rooms.export');


    // Delete route
    Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])
        ->name('rooms.destroy');

    // Route::get('/settings', fn() => view('components.setting'))
    //     ->name('settings');

    
});

Route::post('/logout', [loginController::class, 'logout'])->name('logout');

