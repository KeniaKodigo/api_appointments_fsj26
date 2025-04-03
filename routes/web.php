<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    //devolver una vista
    return view('welcome');
});

