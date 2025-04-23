<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    //metodo donde vamos a obtener consultas y el nombre del paciente
    //anotacion swagger
    public function index(){
        //select * from appointments join patients on appointments.patient_id = patients.id
        $appointments = Appointment::join('patients','appointments.patient_id', '=', 'patients.id')->select('appointments.*','patients.name as patient')->get();

        return response()->json($appointments, 200); //[]
    }

    //metodo para filtrar citas por fecha (opcional)
    public function filterAppointmentsByDate(Request $request){
        $validator = Validator::make($request->all(), [
            'start_date' => 'date|nullable|date_format:Y-m-d',
            'end_date' => 'date|nullable|date_format:Y-m-d|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //select * from appointments where date_appointment between '2025-04-01' and '2025-05-31'
        //obtener todas las citas
        $query_appointments = Appointment::select('*');

        //verificando los campos de la fecha y verificar si existen
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');

        if($start_date && $end_date){
            $query_appointments->whereBetween('date_appointment', [$start_date, $end_date]);
        }

        $data = $query_appointments->get();
        return response()->json($data, 200); //[]
    }

    //metodo para obtener pacientes por doctor en base al inicio de sesion
    public function getPatientByDoctor(Request $request){
        $user = $request->user(); //obteniendo el usuario logueado

        //validando si el usuario no es un doctor
        if($user->role_id == 2){
            return response()->json([
                'message' => 'Access denied, only doctors can view this information',
            ], 403);
        }

        $data = Appointment::join('patients','appointments.patient_id', 'patients.id')->join('users','appointments.user_id', 'users.id')->where('appointments.user_id', $user->id)
            ->select('appointments.*','patients.name as patient','users.name as doctor')
            ->get();

        return response()->json($data, 200); //[]
    }
}
