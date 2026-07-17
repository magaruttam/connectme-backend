<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request){
        //Validate Request
        $validated = $request->validate([
            'email' => "required|email",
            'password' => 'required'
        ]);
        

        //find user by email
        $user = User::where('email',$validated['email'])->first();

        //check if user is found
        if (!$user){
            return response()->json([
                'message' => 'Invalid email'
            ], 401);
        }
        //check is password is correct
        if(!Hash::check($validated['password'],$user->password)){
            return response()->json([
                'message' => 'Invalid password'
            ], 401);
        }

        //Generate sanctum token
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5. Return response
        return response()->json([
            'message' => 'Login successful',

            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'email' => $user->email,
            ],

            'token' => $token
        ]);
    }
}
