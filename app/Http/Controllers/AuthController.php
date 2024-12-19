<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validare date
        $rules = [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Creare user nou
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // hash-ul parolei
        ]);

        // Crearea unui token pentru accesul la API
        $token = $user->createToken('Personal Access Token')->plainTextToken;

        // Răspunsul cu datele utilizatorului și token-ul
        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response()->json($response, 200);
    }

    public function login(Request $request)
    {
        // Validare date
        $rules = [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Verificăm dacă utilizatorul există
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Creăm un token pentru utilizator
        $token = $user->createToken('Personal Access Token')->plainTextToken;

        // Răspunsul cu datele utilizatorului și token-ul
        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response()->json($response, 200);
    }
}



