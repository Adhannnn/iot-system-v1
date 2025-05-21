<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\loginController;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Controllers\RoomController;

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

    // Route::get('/settings', function () {
    //     if (request()->wantsJson() || request()->ajax()) {
    //         return view('rooms.settings')->render();
    //     }
    //     return view('rooms.settings');
    // })->name('settings');
});