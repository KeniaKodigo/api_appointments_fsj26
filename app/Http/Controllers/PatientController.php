<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    //query builder (manual) / ORM (metodos mapeados)(automatica)
    //metodo donde vamos obtener todos los pacientes (ruta)
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
    //Request
    public function store(StorePatientRequest $request){
        //formar las reglas
        // $validators = Validator::make($request->all(), [
        //     'name' => 'required|string|max:50',
        //     'birthday' => 'required|date_format:Y-m-d',
        //     'age' => 'required|integer|min:0|max:120'
        // ]);

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
    public function update(UpdatePatientRequest $request, $id){
        $patient = Patient::find($id); //encontramos al paciente

        $patient->update($request->all()); //actualizamos sus datos
        return response()->json(['message' => 'Successfly updated'], 200);
    }
    //HTTP (PUT (actualiza todo), PATCH (parcialmente))
}
