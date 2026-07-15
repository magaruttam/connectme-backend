<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request){
       //Validation
       $validated = $request->validate([
        'full_name' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
       ]);
       //Create User
        $user = User::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Create Sanctum token
        $token = $user->createToken('auth_token')->plainTextToken;
        // Return response
        return response()->json([
            'message' => 'User registered successfully.',
            'user' => $user,
            'token' => $token,
        ], 201);

    }
}
