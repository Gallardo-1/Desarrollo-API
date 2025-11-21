<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function register(Request $request)
    {
        try {
            // validamos los datos de la entrada o request
            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:users|regex:/^[a-zA-Z0-9_]+$/',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            return response()->json([
                'message' => 'User registered successfully', 
                'user' => $user,
                'status' => 201
        ], 201);

        } catch (\Exception $e) {
            //
            return response()->json([
                'message' => 'Registration failed', 
                'error' => $e->getMessage(),
                'status' => 400
        ], 400);
        }
    }

    public function login(Request $request){

        try {
            // validamos los datos del request de la peticion
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string|min:8',
            ]);

            // extraemos las credenciales, los datos que vamos a trabajar del body de la peticion
            $credentials = $request->only('email', 'password');

            // intentamos autenticar al usuario con las credenciales
            if (Auth::attempt($credentials)) {
                // si las credenciales son correctas obtenemos el usuario autenticado
                $user = $request->user();
                //$user = Auth::user(); -->>it is the same as above line

                // declaramos el tiempo de expiracion del token
                // el token se refresca cada 5 minutos
                $expirationTimeToken = Carbon::now()->addMinutes(5);

                // generamos un token de autenticacion para el usuario
                $token = $user->createToken('auth_token', ['server:update'], $expirationTimeToken)->plainTextToken;
                

                return response()->json([
                    'message' => 'Login successful',
                    'access_token' => $token,
                    'status' => 200
                ], 200);

            } else {

                // en caso de que las credenciales sean invalidas
                return response()->json([
                    'message' => 'Invalid credentials',
                    'status' => 401
                ], 401);
            }


        } catch (\Exception $e) {
            //
            return response()->json([
                'message' => 'Login failed', 
                'error' => $e->getMessage(),
                'status' => 400
            ], 400);
        }
    }
    
    public function logout(Request $request){
        // obtenemos el usuario autentucado a traves del request
        $user = $request->user();

        // revocamos todos los tokens del usuario autenticado
        // hace que el token invalido y que el usuario tenga que generar uno nuevo
        $user->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successful',
            'status' => 200
        ], 200);
    }
}
