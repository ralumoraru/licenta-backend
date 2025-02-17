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
        Log::info('Received registration request: ', $request->all());
    
        $rules = [
            'uid' => 'required|string', 
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
        ];
    
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            Log::error('Validation failed: ', $validator->errors()->toArray());
            return response()->json($validator->errors(), 400);
        }
    
        try {
            $user = User::create([
                'uid' => $request->uid,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            Log::info('User created successfully: ', $user->toArray());
    
            // Generăm token pentru utilizatorul nou creat
            $token = JWTAuth::fromUser($user);
    
            // Construim răspunsul pentru client
            $response = [
                'user' => [
                    'id' => $user->id,
                    'uid' => $user->uid,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ],
                'token' => $token,  // Trimitem token-ul generat
            ];
    
            return response()->json($response, 200);
    
        } catch (Exception $e) {
            Log::error('Error creating user: ', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Something went wrong while creating user'], 500);
        }
    }
    

public function getUserData(Request $request)
{
    try {
        $user = JWTAuth::parseToken()->authenticate();  
        if (!$user) {
            Log::error('User not found');
            return response()->json(['error' => 'User not found'], 404);
        }

        Log::info('Authenticated User: ', $user->toArray());
        return response()->json($user);
    } catch (Exception $e) {
        Log::error('Error during token parsing: ' . $e->getMessage());
        return response()->json(['error' => 'Unauthorized or invalid request'], 401);
    }
}



public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['message' => 'Email does not exist'], 404);
    }

    if (!Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid password'], 401);
    }

    $token = JWTAuth::fromUser($user);

    // Trimitem token-ul JWT în răspuns
    return response()->json([
        'token' => $token,  
        'message' => 'Login successful',
        'uid' => $user->uid,  
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
            'password' => Hash::make(uniqid()),  
            'uid' => $request->uid ?? null, 
        ]);
    }  else {
        // Dacă utilizatorul există și nu are google_id, actualizează-l
        if (!$user->google_id && $request->google_id) {
            $user->google_id = $request->google_id;
            $user->save();
        }
    }

    // Generăm un token JWT pentru utilizator
    $token = JWTAuth::fromUser($user);

    return response()->json([
        'token' => $token,  // Trimitem token-ul JWT
        'message' => 'Google Login successful',
    ]);
}


       
}



