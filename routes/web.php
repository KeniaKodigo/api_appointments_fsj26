<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    //devolver una vista
    return view('welcome');
});

//get, post, put, delete
Route::get('/patients', function () {
    echo "Hola desde la rutas de tipo web";
});