<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
/**
 * @OA\Tag(name="Patients", description="API for managing patients")
 */
class PatientController extends Controller
{
    //query builder (manual) / ORM (metodos mapeados)(automatica)
    //metodo donde vamos obtener todos los pacientes (ruta)
    /**
     * @OA\Get(
     *     path="/api/v1/patients",
     *     summary="Get all patients",
     *     description="Returns a list of all registered patients",
     *     tags={"Patients"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of patients successfully obtained"
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No patients registered"
     *     )
     * )
     */
    public function index(){
        //consulta ORM (all()) => select * from patients
        $patients = Patient::all(); //[]

        if(count($patients) > 0){
            return response()->json($patients, 200); //Ok
        }

        return response()->json([], 200); //Ok
        //select * from patients where id = 7 
        // $patient = Patient::find(7); //ORM
        // Patient::where('id',7)->get(); //query builder

        // //select name,birthday from patients where name = 'guillermo'
        // $patient = Patient::where('name','guillermo')->select('name','birthday')->get();

        // //select SUM(age) from patients
        // $sum = Patient::sum('age')->get(); //ORM
        //  //query builder
        // //query builder

        // //select * from patients order by id DESC
        // $orden = Patient::orderBy('id', 'desc')->get();

        //save() => insert into
        //update(), delete()
    }

    //metodo de guardar un paciente (envio)
    /**
     * @OA\Post(
     *     path="/api/v1/patients",
     *     summary="Register a new patient",
     *     description="Registers a patient in the database",
     *     tags={"Patients"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "birthdate", "gender", "address", "phone"},
     *             @OA\Property(property="name", type="string", example="Juan Pérez"),
     *             @OA\Property(property="birthdate", type="string", format="date", example="2000-01-01"),
     *             @OA\Property(property="gender", type="string", enum={"Masculino", "Femenino"}, example="Masculino"),
     *             @OA\Property(property="address", type="string", example="Calle Falsa 123"),
     *             @OA\Property(property="phone", type="string", example="12345678"),
     *             @OA\Property(property="email", type="string", example="juan.perez@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successfly created"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(StorePatientRequest $request){
        //formar las reglas
        // $validators = Validator::make($request->all(), [
        //     'name' => 'required|string|max:50',
        //     'birthday' => 'required|date_format:Y-m-d',
        //     'age' => 'required|integer|min:0|max:120'
        // ]);
        //php artisan make:request 
        // if($validators->fails()) {
        //     return $validators->errors(); //errores personalizados
        // }

        //guardar los datos del paciente a la bd
        // $patient = new Patient(); //creacion de objeto
        // $patient->name = $request->name_input; 
        // $patient->birthdate = "2023-10-10";
        // $patient->save();

        Patient::create($request->all()); //insertar datos
        //save(), create()
        return response()->json(['message' => 'Successfly created'],201);
    }

    //metodo para obtener un paciente
    /**
     * @OA\Get(
     *     path="/api/v1/patients/{patient_id}",
     *     summary="Obtain a patient by ID",
     *     description="Obtains the information of a specific patient by patient ID",
     *     tags={"Patients"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="Patient ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Patient successfully found"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Patient not found"
     *     )
     * )
     */
    public function findById($id){
        $validator = Validator::make(['patient_id' => $id],[
            'patient_id' => 'required|integer|exists:patients,id'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        //select * from patients where id = ?
        $patient = Patient::find($id);
        //$patient = Patient::where('id', $id)->select('name','email')->get();
        return response()->json($patient, 200);
    }

    //metodo para actualizar un paciente (name,address,phone,email)
    /**
     * @OA\Patch(
     *     path="/api/v1/patients/{patient_id}",
     *     summary="Update patient information",
     *     description="Update existing patient data",
     *     tags={"Patients"},
     *     @OA\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="Patient ID to be updated",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "address", "phone"},
     *             @OA\Property(property="name", type="string", example="Juan Pérez Actualizado"),
     *             @OA\Property(property="address", type="string", example="Avenida Siempre Viva"),
     *             @OA\Property(property="phone", type="string", example="87654321"),
     *             @OA\Property(property="email", type="string", example="juan.actualizado@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfly updated"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error"
     *     )
     * )
     */
    public function update(UpdatePatientRequest $request, $id){
        $patient = Patient::find($id); //encontramos al paciente

        $patient->update($request->all()); //actualizamos sus datos
        return response()->json(['message' => 'Successfly updated'], 200);
    }
    //HTTP (PUT (actualiza todo), PATCH (parcialmente))
}
