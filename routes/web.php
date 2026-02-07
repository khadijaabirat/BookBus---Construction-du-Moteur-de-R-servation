<?php

use App\Http\Controllers\TripController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

 Route::get('/', function () {
    return view('welcome');
});
 
Route::get('/search', [TripController::class, 'index'])->name('search.index');
 
Route::post('/search/results', [TripController::class, 'search'])->name('search.trip');
 
Route::post('/reserve/{segment_id}', [ReservationController::class, 'store'])->name('reservation.store');
 
Route::get('/reservation-success', [ReservationController::class, 'success'])->name('reservation.success');