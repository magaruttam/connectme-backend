<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Services\OtpService;


class RegisterController extends Controller
{
    public function register(Request $request,OtpService $otpService){
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

          // Send OTP
        $otpService->sendOtp($user);
        
        // Return response
        return response()->json([
            'message' => 'Registration successful. Please verify your email using the OTP.',
        ], 201);

    }

    public function verifyOtp(Request $request, OtpService $otpService)
{
    $validated = $request->validate([
        'email' => 'required|email',
        'otp' => 'required|digits:6',
    ]);

    $user = User::where('email', $validated['email'])->first();

    if (!$user) {
        return response()->json([
            'message' => 'User not found.'
        ], 404);
    }

    if (!$otpService->verifyOtp($user, $validated['otp'])) {
        return response()->json([
            'message' => 'Invalid or expired OTP.'
        ], 422);
    }

    // Mark email as verified
    $user->email_verified_at = now();
    $user->is_phone_verified = true;
    $user->save();

    return response()->json([
        'message' => 'Email verified successfully.'
    ]);
}
}
