<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
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
            'password' => 'required|string',
        ]);

        if ($form->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $form->errors(),
            ], 422);
        }

        $user = User::create($form);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully.',
            'data'    => $user
        ], 201);
    }
}
