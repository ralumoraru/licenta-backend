<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
{
    Log::info('Received registration request: ', $request->all()); // ✅ Debugging log

    $rules = [
        'uid' => 'required|string', 
        'name' => 'required|string',
        'email' => 'required|string|email|unique:users,email',
        'password' => 'required|string|min:6',
    ];

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }

    $user = User::create([
        'uid' => $request->uid,  // ✅ Salvează UID-ul
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    Log::info('User created: ', $user->toArray()); 

    $response = [
        'user' => [
            'id' => $user->id,
            'uid' => $user->uid,  // Asigură-te că `uid` este salvat în modelul User
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ],];
    return response()->json($response, 200);

    
}
public function getUserData(Request $request)
{
    try {
        // Verificăm dacă există token-ul în header-ul Authorization
        $user = JWTAuth::parseToken()->authenticate();  // Obținem utilizatorul pe baza token-ului

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Returnăm datele utilizatorului
        return response()->json($user); // Returnează utilizatorul în format JSON
    } catch (Exception $e) {
        return response()->json(['error' => 'Unauthorized or invalid request'], 401);
    }
}


public function login(Request $request)
{
    // Validate the incoming data
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }

    // Check if the user exists based on email
    $user = User::where('email', $request->email)->first();

    // If user doesn't exist, return error
    if (!$user) {
        return response()->json(['message' => 'Email does not exist'], 404);
    }

    // Verificăm parola
    if (!Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid password'], 401);
    }

    // Dacă autentificarea este reușită, generăm token-ul JWT
    $token = JWTAuth::fromUser($user);

    // Trimitem token-ul JWT în răspuns
    return response()->json([
        'token' => $token,  // Trimitem token-ul JWT
        'message' => 'Login successful',
        'uid' => $user->uid,  // Trimitem și UID-ul utilizatorului
    ], 200);
}
public function googleLogin(Request $request)
{
    Log::info('Google Login Data: ', $request->all());  // Loghează datele primite

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        // Dacă utilizatorul nu există, îl creăm
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'google_id' => $request->google_id,
            'password' => Hash::make(uniqid()),  // Folosim o parolă aleatorie pentru autentificarea prin Google
        ]);
    }

    // Generăm un token JWT pentru utilizator
    $token = JWTAuth::fromUser($user);

    return response()->json([
        'token' => $token,  // Trimitem token-ul JWT
        'message' => 'Google Login successful',
    ]);
}


       
}



