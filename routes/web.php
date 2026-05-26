<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BusController;
use App\Http\Controllers\Admin\RouteController as AdminRouteController;
use App\Http\Controllers\Admin\TripController as AdminTripController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\AssignmentController;
use Illuminate\Support\Facades\Route;

 Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [TripController::class, 'index'])->name('search.index');
Route::get('/search/results', [TripController::class, 'search'])->name('search.trip'); // Changed to GET for bookmarking

 Route::middleware(['auth'])->group(function () {
    Route::get('/reserve/{segment_id}', [ReservationController::class, 'create'])->name('reservation.create');
    Route::post('/reserve/{segment_id}', [ReservationController::class, 'store'])->name('reservation.store');
    Route::get('/reservation-success', [ReservationController::class, 'success'])->name('reservation.success');
    
    Route::get('/my-bookings', [ReservationController::class, 'index'])->name('bookings.index');
    Route::get('/my-bookings/{id}', [ReservationController::class, 'show'])->name('bookings.show');
    Route::post('/my-bookings/{id}/cancel', [ReservationController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/my-bookings/{id}/download', [ReservationController::class, 'downloadTicket'])->name('bookings.download');
    Route::post('/promo/verify', [ReservationController::class, 'verifyPromo'])->name('promo.verify');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
     Route::get('/dashboard', function () {
        if(auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('bookings.index');
    })->name('dashboard');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('buses', BusController::class);
    Route::patch('buses/{bus}/maintenance', [BusController::class, 'toggleMaintenance'])->name('buses.maintenance');
    Route::resource('routes', AdminRouteController::class);
    Route::resource('trips', AdminTripController::class);
    Route::resource('employees', EmployeeController::class);
    
    Route::get('bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{id}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::post('bookings/{id}/cancel', [AdminBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('bookings/{id}/validate', [AdminBookingController::class, 'validatePayment'])->name('bookings.validate');

    Route::get('assignments/create', [AssignmentController::class, 'create'])->name('assignments.create');
    Route::post('assignments', [AssignmentController::class, 'store'])->name('assignments.store');
});

require __DIR__.'/auth.php';
