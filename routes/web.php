<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\{
    UserController,
    AppointmentController as AdminAppointmentController,
    DashboardController
};

use App\Http\Controllers\Client\{
    AppointmentController as ClientAppointmentController,
    DoctorController,
    HomeController,
    PaymentController as ClientPaymentController
};
use App\Http\Controllers\Common\SpecialtyController;



Route::get('/{city}/{specialty}', [HomeController::class,'index'])->name('doctors-with-city-specialty');
Route::get('/', [HomeController::class,'index']);
Route::post('/search',[HomeController::class,'searchForm'])->name('search');

Route::get('/doctors/{doctor}', [DoctorController::class,'show']);


Route::get('/specialties', [SpecialtyController::class, 'getAll']);
Route::get('/specialties/{id}/doctors', [SpecialtyController::class, 'getByIdWithDoctors']);
Route::get('/specialties/doctors', [SpecialtyController::class, 'getAllWithDoctors']);



// Route::get('/specialties/{specialty:slug}', ...)
Route::resource('appointments', ClientAppointmentController::class);
Route::post('payment', [ClientPaymentController::class, 'store']);




Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('appointments', AdminAppointmentController::class);
    Route::get('dashboard', [DashboardController::class, 'index']);
});
