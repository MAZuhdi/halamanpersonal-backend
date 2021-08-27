<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|string|unique:users|email',
            'password' => 'required|string',
            'theme_id' => 'required',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'photo' => $request['photo'],
            'introduction' => $request['introduction'],
            'more_info' => $request['more_info'],
            'facebook' => $request['facebook'],
            'instagram' => $request['instagram'],
            'linkedin' => $request['linkedin'],
            'github' => $request['github'],
            'theme_id' => $request['theme_id'],
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        //check email
        $user = User::where('email', $validated['email'])->first();
        if (!$user || !Hash::check($validated['password'], $user['password'])) {
            return response([
                'message' => 'Bad Credentials'
            ],401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response($response, 201);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return response([
            'message' => 'Logged Out',
        ], 200);
    }
}
