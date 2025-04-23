<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\PatientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

#ruta protegida
Route::get('/user', function (Request $request) {
    return $request->user(); //obtener todos los usuarios de la bd / info guillermo
})->middleware('auth:sanctum'); //token

//agrupando las rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/v1/patients', [PatientController::class, 'index']);
    Route::get('/v1/patients/{patient_id}', [PatientController::class, 'findById']);
    Route::post('/v1/logout', [AuthenticationController::class, 'logout']);

    Route::get('/v1/appointments/patient', [AppointmentController::class, 'getPatientByDoctor']);
});


Route::post('/v1/patients', [PatientController::class, 'store']);
//ruta que solicita un parametro
Route::patch('/v1/patients/{patient_id}', [PatientController::class, 'update']);

Route::get('/v1/appointments', [AppointmentController::class, 'index']);
//ruta que filtra citas por fecha
Route::get('/v1/appointments/filter', [AppointmentController::class, 'filterAppointmentsByDate'])->name('appointments.filter');

/** 
 * nombre uri (url)
 * asignar nombre a la ruta
 * 
 * url('/v1/appointments/filter') -> hipervinculo
 * route('appointments.filter') -> accion
 */

Route::get('/v1/auth', function(){
    return response()->json([
        'message' => 'Access denied, please login to continue',
    ], 401);
})->name('login');

Route::post('/v1/login', [AuthenticationController::class, 'login']);
