<?php

use App\Http\Controllers\PatientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

#ruta protegida
Route::get('/user', function (Request $request) {
    return $request->user(); //obtener todos los usuarios de la bd / info guillermo
})->middleware('auth:sanctum'); //token


Route::get('/v1/patients', [PatientController::class, 'index']);
Route::post('/v1/patients', [PatientController::class, 'store']);
//ruta que solicita un parametro
Route::get('/v1/patients/{patient_id}', [PatientController::class, 'findById']);
Route::patch('/v1/patients/{patient_id}', [PatientController::class, 'update']);
