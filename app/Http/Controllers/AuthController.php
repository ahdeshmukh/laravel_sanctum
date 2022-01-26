<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return response(['message' => 'Logged Out'], 200);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
           'email' => 'required|email',
           'password' => 'required|string'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Invalid login. Please try again.'
            ], 401);
        }

        $token = $user->createToken('laravelsanctumtoken')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ], 201);
    }
}
