<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\MovieController as AdminMovieController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\BookingController as CustomerBookingController;
use App\Http\Controllers\Customer\MovieController as CustomerMovieController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

// Landing
Route::get('/', [AuthController::class, 'index'])->name('index');

// Guest
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')->name('logout');

// ====================== ADMIN ======================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Movie CRUD
        Route::resource('movies', AdminMovieController::class);
        Route::patch('movies/{movie}/toggle', [AdminMovieController::class, 'toggleStatus'])
            ->name('movies.toggle');

        // Bookings
        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])
            ->name('bookings.status');
    });

// ====================== CUSTOMER ======================

Route::middleware(['auth', 'role:customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');

        // Movies
        Route::get('/movies', [CustomerMovieController::class, 'index'])->name('movies.index');
        Route::get('/movies/{movie}', [CustomerMovieController::class, 'show'])->name('movies.show');

        // Bookings
        Route::get('/bookings', [CustomerBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [CustomerBookingController::class, 'show'])->name('bookings.show'); // NEW
        Route::get('/movies/{movie}/book', [CustomerBookingController::class, 'create'])->name('bookings.create');
        Route::post('/movies/{movie}/book', [CustomerBookingController::class, 'store'])->name('bookings.store');
        Route::patch('/bookings/{booking}/cancel', [CustomerBookingController::class, 'cancel'])->name('bookings.cancel');
    });

    