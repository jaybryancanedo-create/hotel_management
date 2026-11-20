<?php

use App\Http\Controllers\GuestProfileController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReservationController;
use App\Models\Room;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
})->name('login');

Route::post('login', LoginController::class)
    ->middleware('throttle:5,1')
    ->name('login.attempt');

Route::get('dashboard', function () {
    $user = Auth::user();
    $rooms = Room::query()
        ->where('status', 'available')
        ->orderBy('room_number')
        ->get();
    $services = Service::query()
        ->where('status', 'available')
        ->orderBy('name')
        ->get();
    $reservations = $user->reservations()
        ->with(['room', 'payments'])
        ->latest()
        ->get();

    return view('dashboard', [
        'rooms' => $rooms,
        'services' => $services,
        'reservations' => $reservations,
        'guestProfile' => $user->guestProfile,
    ]);
})->middleware('auth')->name('dashboard');

Route::post('reservations', [ReservationController::class, 'store'])
    ->middleware('auth')
    ->name('reservations.store');

Route::post('guest-profile', [GuestProfileController::class, 'store'])
    ->middleware('auth')
    ->name('guest-profile.store');

Route::post('logout', function () {
    Auth::guard('web')->logout();

    Session::invalidate();
    Session::regenerateToken();

    return redirect('/');

})->name('logout');

Route::view('register', 'register')->name('register');

Route::post('register', RegisterController::class)->name('register.store');

Route::view('login', 'login')->name('login');
