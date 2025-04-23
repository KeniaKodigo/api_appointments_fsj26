<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    //
    public function login(Request $request){

        $email = $request->input('email');
        $password = $request->input('password');

        //select * from users where email = '' and password = ''
        $user = User::where('email',$email)->where('password',$password)->first(); //{}
        if($user){
            //generar un token
            $token = $user->createToken('token-fsj26')->plainTextToken; //token
            return response()->json([
                'user' => $user->name,
                'token' => $token,
            ], 200);
        }else{
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }
    }

    public function logout(Request $request){
        //eliminando todos los tokens del usuario
        $request->user()->tokens()->delete(); //eliminar todos los tokens
        // $request->user()->currentAccessToken()->delete(); //eliminar el token actual
        return response()->json([
            'message' => 'Logout successful',
        ], 200);
    }
}
