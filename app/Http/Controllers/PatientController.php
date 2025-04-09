<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    
    //metodo donde vamos obtener todos los pacientes (ruta)
    public function index(){
        //consulta ORM (all()) => select * from patients
        $patients = Patient::all(); //[]

        if(count($patients) > 0){
            return response()->json($patients, 200);
        }

        return response()->json([], 200);
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
    public function store(StorePatientRequest $request){
        //formar las reglas
        // $validators = Validator::make($request->all(), [
        //     'name' => 'required|string|max:50',
        //     'birthday' => 'required|date_format:Y-m-d',
        //     'age' => 'required|integer|min:0|max:120'
        // ]);

        // if($validators->fails()) {
        //     return $validators->errors();
        // }


        $name = $request->input('name'); //capturo lo que la persona me envia en el input()
        $birthday = $request->input('birthday'); 
        $age = $request->input('age');

        
        echo $name . $birthday . $age;
    }
}
