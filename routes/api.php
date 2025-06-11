<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AppointmentController;

Route::middleware(['web', 'auth'])->group(function () {
    Route::resource('appointments', AppointmentController::class);
    Route::get('/appointments/{appointment}/related', [App\Http\Controllers\API\AppointmentController::class, 'showRelatedAppointments'])->name('appointments.showRelatedAppointments');

});
