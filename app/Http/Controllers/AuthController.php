<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request) {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    public function register(Request $request) {
        $form = Validator::make($request->all(), [
            'full_name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required|string|max:12',
            'whatsapp_number' => 'required|string|max:12',
            'address' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        if ($form->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $form->errors(),
            ], 422);
        }

        $validated = $form->validated();

        $user = User::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'whatsapp_number' => $validated['whatsapp_number'],
            'address' => $validated['address'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully.',
            'data'    => $user
        ], 201);
    }

    public function logout(Request $request)
    {
        // Revoke the token used for the current request
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
