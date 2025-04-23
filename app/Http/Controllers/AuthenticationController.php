<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
/**
 * @OA\Tag(name="Users", description="API for authentication management")
 */
class AuthenticationController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     summary="log in to the system",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inicio de sesión exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="string", example="Juan Perez"),
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJK...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You are not authorized")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/v1/logout",
     *     summary="Logs out the authenticated user",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Sesión cerrada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="mensaje", type="string", example="Se ha cerrado la sesion")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
    public function logout(Request $request){
        //eliminando todos los tokens del usuario
        $request->user()->tokens()->delete(); //eliminar todos los tokens
        // $request->user()->currentAccessToken()->delete(); //eliminar el token actual
        return response()->json([
            'message' => 'Logout successful',
        ], 200);
    }
}
