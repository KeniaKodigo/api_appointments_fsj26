<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Patient extends Model
{
    //Patient => patients (table)
    //autorizamos a laravel para crear datos quemados
    use HasFactory;
}
