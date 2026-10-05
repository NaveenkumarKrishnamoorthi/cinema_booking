<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\MovieController as AdminMovieController;
use App\Http\Controllers\Admin\OrganizerController as AdminOrganizerController;
use App\Http\Controllers\Admin\OrganizerRequestController as AdminOrganizerRequestController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\BookingController as CustomerBookingController;
use App\Http\Controllers\Customer\MovieController as CustomerMovieController;
use App\Http\Controllers\Customer\OrganizerRequestController as CustomerOrganizerRequestController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Organizer\MovieController as OrganizerMovieController;
use App\Http\Controllers\OrganizerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/', [AuthController::class, 'index'])->name('index');

/*
|--------------------------------------------------------------------------
| Guest (login / register)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /* Dashboard */
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        /* Movies CRUD */
        Route::resource('movies', AdminMovieController::class);
        Route::patch('movies/{movie}/toggle',   [AdminMovieController::class, 'toggleStatus'])
            ->name('movies.toggle');
        Route::patch('movies/{movie}/reassign', [AdminMovieController::class, 'reassign'])
            ->name('movies.reassign');

        /* Bookings */
        Route::get('/bookings', [AdminBookingController::class, 'index'])
            ->name('bookings.index');
        Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])
            ->name('bookings.status');

        /* Organizer Requests (approval flow) */
        Route::get('/organizer-requests', [AdminOrganizerRequestController::class, 'index'])
            ->name('organizer-requests.index');
        Route::patch('/organizer-requests/{organizerRequest}/accept',
            [AdminOrganizerRequestController::class, 'accept'])
            ->name('organizer-requests.accept');
        Route::patch('/organizer-requests/{organizerRequest}/ignore',
            [AdminOrganizerRequestController::class, 'ignore'])
            ->name('organizer-requests.ignore');

        /* ============================================================
         |  ORGANIZERS MANAGEMENT (NEW)
         |  ⚠️ Static routes (/organizers/promote, /organizers/create)
         |     MUST come before wildcard routes (/organizers/{organizer})
         |     or "promote" and "create" will be treated as IDs.
         * ============================================================ */

        // Promote flow (static)
        Route::get('/organizers/promote',  [AdminOrganizerController::class, 'promoteForm'])
            ->name('organizers.promote.form');
        Route::post('/organizers/promote', [AdminOrganizerController::class, 'promote'])
            ->name('organizers.promote');

        // List + create
        Route::get('/organizers',        [AdminOrganizerController::class, 'index'])->name('organizers.index');
        Route::get('/organizers/create', [AdminOrganizerController::class, 'create'])->name('organizers.create');
        Route::post('/organizers',       [AdminOrganizerController::class, 'store'])->name('organizers.store');

        // Edit / update / demote / delete (wildcard)
        Route::get('/organizers/{organizer}/edit',      [AdminOrganizerController::class, 'edit'])->name('organizers.edit');
        Route::put('/organizers/{organizer}',           [AdminOrganizerController::class, 'update'])->name('organizers.update');
        Route::patch('/organizers/{organizer}/demote',  [AdminOrganizerController::class, 'demote'])->name('organizers.demote');
        Route::delete('/organizers/{organizer}',        [AdminOrganizerController::class, 'destroy'])->name('organizers.destroy');

        // View an organizer's movies
        Route::get('/organizers/{organizer}/movies', [AdminOrganizerController::class, 'movies'])
            ->name('organizers.movies');
    });

/*
|--------------------------------------------------------------------------
| CUSTOMER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {

        /* Dashboard */
        Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');

        /* Browse movies */
        Route::get('/movies',         [CustomerMovieController::class, 'index'])->name('movies.index');
        Route::get('/movies/{movie}', [CustomerMovieController::class, 'show'])->name('movies.show');

        /* Bookings */
        Route::get('/bookings',           [CustomerBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [CustomerBookingController::class, 'show'])->name('bookings.show');
        Route::get('/movies/{movie}/book',  [CustomerBookingController::class, 'create'])->name('bookings.create');
        Route::post('/movies/{movie}/book', [CustomerBookingController::class, 'store'])->name('bookings.store');
        Route::patch('/bookings/{booking}/cancel', [CustomerBookingController::class, 'cancel'])->name('bookings.cancel');

        /* Organizer request */
        Route::get('/request-organizer', [CustomerOrganizerRequestController::class, 'create'])
            ->name('organizer-request.create');
        Route::post('/request-organizer', [CustomerOrganizerRequestController::class, 'store'])
            ->name('organizer-request.store');
        Route::get('/request-organizer/status', [CustomerOrganizerRequestController::class, 'status'])
            ->name('organizer-request.status');
    });


   Route::middleware(['auth', 'role:organizer'])
    ->prefix('organizer')
    ->name('organizer.')
    ->group(function () {
        Route::get('/dashboard', [OrganizerController::class, 'dashboard'])->name('dashboard');

        Route::resource('movies', OrganizerMovieController::class);
        Route::patch('movies/{movie}/toggle', [OrganizerMovieController::class, 'toggleStatus'])
            ->name('movies.toggle');

        Route::get('/bookings', [OrganizerMovieController::class, 'bookings'])
            ->name('bookings.index');
    });