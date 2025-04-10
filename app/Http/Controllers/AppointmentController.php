<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    //metodo donde vamos a obtener consultas y el nombre del paciente
    public function index(){
        //select * from appointments join patients on appointments.patient_id = patients.id
        $appointments = Appointment::join('patients','appointments.patient_id', '=', 'patients.id')->select('appointments.*','patients.name as patient')->get();

        return response()->json($appointments, 200);
    }
}
