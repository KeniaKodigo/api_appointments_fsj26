<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
/**
 * @OA\Tag(name="Appointments", description="API for managing appointments")
 */
class AppointmentController extends Controller
{
    //metodo donde vamos a obtener consultas y el nombre del paciente
    //anotacion swagger
    /**
     * @OA\Get(
     *     path="/api/v1/appointments",
     *     summary="Obtener todas las citas con el nombre del paciente",
     *     tags={"Appointments"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de citas con información del paciente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="date_appointment", type="string", format="date", example="2025-04-25"),
     *                 @OA\Property(property="patient", type="string", example="Juan Pérez")
     *             )
     *         )
     *     )
     * )
     */
    public function index(){
        //select * from appointments join patients on appointments.patient_id = patients.id
        $appointments = Appointment::join('patients','appointments.patient_id', '=', 'patients.id')->select('appointments.*','patients.name as patient')->get();

        return response()->json($appointments, 200); //[]
    }

    //metodo para filtrar citas por fecha (opcional)
    /**
     * @OA\Get(
     *     path="/api/v1/appointments/filter",
     *     summary="Get appointments in a date range",
     *     tags={"Appointments"},
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Fecha de inicio (opcional)",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2025-01-10")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="Fecha de fin (opcional)",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2025-02-15")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de citas",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Validation Error"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
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
    /**
     * @OA\Get(
     *     path="/api/v1/appointments/patient",
     *     summary="Obtener pacientes asignados al doctor logueado",
     *     tags={"Appointments"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de citas con datos del paciente y del doctor",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="date_appointment", type="string", format="date", example="2025-04-25"),
     *                 @OA\Property(property="patient", type="string", example="Ana Gómez"),
     *                 @OA\Property(property="doctor", type="string", example="Dr. Carlos Martínez")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Acceso denegado si no es doctor",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Access denied, only doctors can view this information")
     *         )
     *     )
     * )
     */
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
