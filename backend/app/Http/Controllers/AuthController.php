<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required', // this can be user_id or email
            'password' => 'required'
        ]);

        // Try to find the user based on whether login is numeric (user_id) or email
        if (is_numeric($request->login)) {
            // Login using user_id
            $user = User::where('id', $request->login)->where('role_id', '<', 5)->first();
        } else {
            // Login using email (applicant only)
            $user = User::where('email', $request->login)->where('role_id', 5)->first();
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            "access_token" => $token,
            "user" => [
                "id" => $user->id,
                "email" => $user->email,
                "role" => [
                    "id" => $user->role_id ?? null,
                    "name" => $user->role->name ?? null
                ]
            ]
        ]);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}