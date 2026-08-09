<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\{
    UserController as V1UserController,
    RbacController as V1RbacController,
    AuthController as V1AuthController,
    ProfileController as V1ProfileController,
    ClinicController as V1ClinicController,
    SpecialtyController as V1SpecialtyController,
    DoctorController as V1DoctorController,
    ScheduleController as V1ScheduleController,
    PatientController as V1PatientController,
    AppointmentController as V1AppointmentController,
    AvailabilityController as V1AvailabilityController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| This file contains all API routes of the system.
| Routes are grouped by modules for better organization and maintainability.
| Note: All routes are automatically prefixed with /api.
*/


Route::prefix('v1')->group(function () {

    /*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/

    Route::prefix('users')
        ->as('users.')
        ->middleware('auth:sanctum')
        ->controller(V1UserController::class)
        ->group(function () {
            Route::get('/', 'index')
                ->middleware('permission:users.view')
                ->name('index');

            Route::get('/{user}', 'show')
                ->middleware('permission:users.view')
                ->name('show');

            Route::post('/', 'store')
                ->middleware('permission:users.create')
                ->name('store');

            Route::patch('/{user}', 'update')
                ->middleware('permission:users.update')
                ->name('update');

            Route::delete('/{user}', 'destroy')
                ->middleware('permission:users.delete')
                ->name('destroy');

            Route::put('/{user}', 'activate')
                ->middleware('permission:users.activate')
                ->name('activate');

            Route::put('/{user}', 'deactivate')
                ->middleware('permission:users.deactivate')
                ->name('deactivate');
        });


    /*
|--------------------------------------------------------------------------
| Authorization Routes
|--------------------------------------------------------------------------
*/

    Route::prefix('authorization')
        ->as('authorization.')
        ->middleware('auth:sanctum')
        ->controller(V1RbacController::class)
        ->group(function () {

            Route::prefix('roles')
                ->as('roles.')
                ->group(function () {

                    Route::post('/', 'createRole')
                        ->middleware('permission:authorization.roles.create')
                        ->name('create');

                    Route::delete('/{role}', 'deleteRole')
                        ->middleware('permission:authorization.roles.delete')
                        ->name('delete');
                });

            Route::post('/users/{user}/roles', 'assignRole')
                ->middleware('permission:authorization.roles.assign')
                ->name('roles.assign');

            Route::delete('/users/{user}/roles/{role}', 'removeRole')
                ->middleware('permission:authorization.roles.remove')
                ->name('roles.remove');

            Route::prefix('permissions')
                ->as('permissions.')
                ->group(function () {

                    Route::post('/', 'createPermission')
                        ->middleware('permission:authorization.permissions.create')
                        ->name('create');

                    Route::delete('/{permission}', 'deletePermission')
                        ->middleware('permission:authorization.permissions.delete')
                        ->name('delete');
                });

            Route::post('/users/{user}/permissions', 'assignPermission')
                ->middleware('permission:authorization.permissions.assign')
                ->name('permissions.assign');

            Route::delete('/users/{user}/permissions/{permission}', 'removePermission')
                ->middleware('permission:authorization.permissions.remove')
                ->name('permissions.remove');

            Route::put('/roles/{role}/permissions', 'syncPermissions')
                ->middleware('permission:authorization.permissions.sync')
                ->name('permissions.sync');
        });

    /*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
    Route::prefix('auth')
        ->as('auth.')
        ->controller(V1AuthController::class)
        ->group(function () {

            //patient login
            Route::prefix('login')
                ->as('login.')
                ->group(function () {
                    Route::post('/request-otp', 'requestOtp')
                        ->middleware('throttle:5,1')
                        ->name('request-otp');

                    Route::post('/verify-otp', 'verifyOtp')
                        ->middleware('throttle:5,1')
                        ->name('verify-otp');
                });

            //staff login
            Route::post('/login', 'login')->name('login');

            //general
            Route::prefix('password')
                ->as('password.')
                ->group(function () {

                    Route::post('/request-otp', 'requestResetOtp')
                        ->middleware('throttle:5,1')
                        ->name('request-otp');

                    Route::post('/verify-otp', 'verifyResetOtp')
                        ->middleware('throttle:5,1')
                        ->name('verify-otp');

                    Route::post('/reset', 'resetPassword')
                        ->name('reset');
                });
            Route::post('/logout', 'logout')
                ->name('logout')->middleware('auth:sanctum');
        });

    /*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
    Route::prefix('me')
        ->as('me.')
        ->middleware('auth:sanctum')
        ->controller(V1ProfileController::class)
        ->group(function () {

            Route::get('/', 'show')
                ->name('show');

            // Route::patch('/', 'update')
            //     ->name('me.update');

            Route::patch('/password', 'updatePassword')
                ->name('password.update');
        });

    /*
|--------------------------------------------------------------------------
| Clinic Routes
|--------------------------------------------------------------------------
*/
    Route::prefix('clinics')
        ->as('clinics.')
        ->middleware('auth:sanctum')
        ->controller(V1ClinicController::class)
        ->group(function () {
            Route::get('/', 'index')
                ->middleware('permission:clinics.view')
                ->name('index');

            Route::get('/{clinic}', 'show')
                ->middleware('permission:clinics.view')
                ->name('show');

            Route::post('/', 'store')
                ->middleware('permission:clinics.create')
                ->name('store');

            Route::patch('/{clinic}', 'update')
                ->middleware('permission:clinics.update')
                ->name('update');

            Route::delete('/{clinic}', 'destroy')
                ->middleware('permission:clinics.delete')
                ->name('destroy');
        });

    /*
|--------------------------------------------------------------------------
| Specialty Routes
|--------------------------------------------------------------------------
*/
    Route::prefix('specialties')
        ->as('specialties.')
        ->controller(V1SpecialtyController::class)
        ->group(function () {

            Route::get('/', 'index')
                ->middleware('permission:specialties.view')
                ->name('index');

            Route::get('/{specialty}', 'show')
                ->middleware('permission:specialties.view')
                ->name('show');

            Route::post('/', 'store')
                ->middleware([
                    'auth:sanctum',
                    'permission:specialties.create'
                ])
                ->name('store');

            Route::patch('/{specialty}', 'update')
                ->middleware([
                    'auth:sanctum',
                    'permission:specialties.update'
                ])
                ->name('update');

            Route::delete('/{specialty}', 'destroy')
                ->middleware([
                    'auth:sanctum',
                    'permission:specialties.delete'
                ])
                ->name('destroy');

            Route::get('/{specialty}/doctors', 'doctorsBySpecialty')
                ->middleware('permission:specialties.view')
                ->name('doctors');
        });
    /*
|--------------------------------------------------------------------------
| Doctors Routes
|--------------------------------------------------------------------------
*/
    Route::prefix('doctors')
        ->as('doctors.')
        ->controller(V1DoctorController::class)
        ->group(function () {

            Route::get('/', 'index')
                ->middleware('permission:doctors.view')
                ->name('index');

            Route::get('/{doctor}', 'show')
                ->middleware('permission:doctors.view')
                ->name('show');

            Route::post('/', 'store')
                ->middleware([
                    'auth:sanctum',
                    'permission:doctors.create'
                ])
                ->name('store');

            Route::patch('/{doctor}', 'update')
                ->middleware([
                    'auth:sanctum',
                    'permission:doctors.update'
                ])
                ->name('update');

            Route::delete('/{doctor}', 'destroy')
                ->middleware([
                    'auth:sanctum',
                    'permission:doctors.delete'
                ])
                ->name('destroy');
        });

    Route::prefix('doctors')
        ->as('doctors.patients.')
        ->middleware([
            'auth:sanctum',
            'permission:patients.view'
        ])
        ->controller(V1PatientController::class)
        ->group(function () {

            // Doctor self - view all
            Route::get('/me/patients', 'doctorSelfPatients')
                ->middleware('role:doctor')
                ->name('me');

            // Doctor.X's patients
            Route::get('/{doctor}/patients', 'patientsByDoctor')
                ->name('index');
        });

    Route::prefix('doctors')
        ->as('doctors.appointments.')
        ->middleware([
            'auth:sanctum',
            'permission:appointments.view'
        ])
        ->controller(V1AppointmentController::class)
        ->group(function () {
            // Doctor self - view all
            Route::get(
                '/me/appointments',
                'doctorSelfAppointments'
            )
                ->name('me');

            // Doctr.X's appointments
            Route::get(
                '/{doctor}/appointments',
                'appointmentsByDoctor'
            )
                ->name('index');
        });

    Route::prefix('doctors')
        ->as('doctors.schedules.')
        ->middleware([
            'auth:sanctum',
            'permission:schedules.view'
        ])
        ->controller(V1ScheduleController::class)
        ->group(function () {

            //doctor self - view all
            Route::get(
                '/me/schedules',
                'doctorSelfSchedules'
            )
                ->name('me');

            //Dr.X's schedules
            Route::get(
                '/{doctor}/schedules',
                'schedulesByDoctor'
            )
                ->name('index');
        });

    Route::prefix('doctors')
        ->as('doctors.availabilities.')
        ->middleware([
            'auth:sanctum',
        ])
        ->controller(V1AvailabilityController::class)
        ->group(function () {

            Route::get(
                '/{doctor}/available-slots',
                'availableSlotsByDoctor'
            )->middleware('permission:availabilities.view')
                ->name('index');
        });
    /*
|--------------------------------------------------------------------------
| Availability Routes
|--------------------------------------------------------------------------
*/

    /*
|--------------------------------------------------------------------------
| Schedule Exception Routes
|--------------------------------------------------------------------------
*/


    /*
|--------------------------------------------------------------------------
| Patient Routes
|--------------------------------------------------------------------------
*/
    Route::prefix('patients')
        ->as('patients.')
        ->controller(V1PatientController::class)
        ->middleware('auth:sanctum')
        ->group(function () {

            // Staff
            Route::get('/', 'index')
                ->middleware('permission:patients.view')
                ->name('index');

            Route::get('/{patient}', 'show')
                ->middleware('permission:patients.view')
                ->name('show');

            Route::post('/', 'store')
                ->middleware('permission:patients.create')
                ->name('store');

            Route::patch('/{patient}', 'update')
                ->middleware('permission:patients.update')
                ->name('update');

            Route::delete('/{patient}', 'destroy')
                ->middleware('permission:patients.delete')
                ->name('destroy');
        });

    Route::prefix('patients')
        ->as('patients.appointments.')
        ->controller(V1AppointmentController::class)
        ->middleware([
            'auth:sanctum',
            'permission:appointments.view'
        ])
        ->group(function () {

            // Patient self - view all
            Route::get(
                '/me/appointments',
                'patientSelfAppointments'
            )
                ->name('me');

            //Patient Y - view all
            Route::get(
                '/{patient}/appointments',
                'appointmentsByPatient'
            )
                ->name('index');
        });

    /*
|--------------------------------------------------------------------------
| Appointments Routes
|--------------------------------------------------------------------------
*/
    Route::prefix('appointments')
        ->as('appointments.')
        ->controller(V1AppointmentController::class)
        ->middleware('auth:sanctum')
        ->group(function () {

            // view all 
            Route::get('/', 'index')
                ->middleware('permission:appointments.view')
                ->name('index');

            // show one
            Route::get('/{appointment}', 'show')
                ->middleware('permission:appointments.view')
                ->name('show');

            //create
            Route::post('/', 'store')
                ->middleware('permission:appointments.create')
                ->name('store');

            //update one
            Route::patch('/{appointment}', 'update')
                ->middleware('permission:appointments.update')
                ->name('update');

            //update one
            Route::delete('/{appointment}', 'destroy')
                ->middleware('permission:appointments.delete')
                ->name('destroy');

            // cancel one
            Route::patch('/{appointment}/cancel', 'cancel')
                ->middleware('permission:appointments.cancel')
                ->name('cancel');

            // change status
            Route::patch('/{appointment}/status', 'updateStatus')
                ->middleware('permission:appointments.update-status')
                ->name('update-status');
        });

    /*
|--------------------------------------------------------------------------
| Schedule Routes
|--------------------------------------------------------------------------
*/
    Route::prefix('schedules')
        ->as('schedules.')
        ->controller(V1ScheduleController::class)
        ->group(function () {

            Route::middleware('auth:sanctum')
                ->group(function () {

                    //view all
                    Route::get('/', 'index')
                        ->middleware('permission:schedules.view')
                        ->name('index');

                    //create
                    Route::post('/', 'store')
                        ->middleware('permission:schedules.create')
                        ->name('store');

                    //show one
                    Route::get('/{schedule}', 'show')
                        ->middleware('permission:schedules.view')
                        ->name('show');

                    //update one
                    Route::patch('/{schedule}', 'update')
                        ->middleware('permission:schedules.update')
                        ->name('update');

                    //delete one
                    Route::delete('/{schedule}', 'destroy')
                        ->middleware('permission:schedules.delete')
                        ->name('destroy');

                    Route::patch('/{schedule}/activate', 'activate')
                        ->middleware('permission:schedules.update')
                        ->name('activate');

                    Route::patch('/{schedule}/deactivate', 'deactivate')
                        ->middleware('permission:schedules.update')
                        ->name('deactivate');
                });
        });
});
