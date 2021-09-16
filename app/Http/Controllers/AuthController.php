<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
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
        ]);

        $user = new User;

        $user->name = $request['name'];
        $user->username = $request['username'];
        $user->email = $request['email'];
        $user->password = bcrypt($request['password']);
        
        $user->save();

        $token = $user->createToken('myapptoken')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|string|email',
        ]);

        $user = $request->user();

        if (is_null($user)) {
            return response()->json([
                'status' => 'error',
                'message' => "User not found"
            ], 404);
        }
        
        if (Hash::check($request['password'], $user->password)) {
            $user->name = $request['name'];
            $user->username = $request['username'];
            $user->email = $request['email'];
            $user->introduction = $request['introduction'];
            $user->theme_id = $request['theme_id'];
            $user->headline = $request['headline'];
            $user->more_info = $request['more_info'];
            $user->facebook = $request['facebook'];
            $user->instagram = $request['instagram'];
            $user->linkedin = $request['linkedin'];
            $user->github = $request['github'];
            
            if ($uploadedPhoto = $request->file('photo')) {
                $destinationPath = 'images/profile/';
                $imageName = $request->username.'.'.time().'.'.$uploadedPhoto->extension();  
                $uploadedPhoto->move(public_path($destinationPath), $imageName);
                $user->photo = $imageName;
            }

            $user->save();

            return response()->json([
                'status' => 'succes',
                'message' => 'Profile updated',
                'data' => $user
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Password not match'
            ]);
        }

        
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
            return response()->json([
                'status' => 'failed',
                'message' => 'Bad credentials'
            ],401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response()->json($response);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out',
        ], 200);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();
        $username = $user->username;

        if (Hash::check($request['password'], $user->password)) {
            if ($user->photo != 'images/profile/photoPlaceholder.jpg') {
                unlink(public_path($user->photo));
            }

            auth()->user()->tokens()->delete();

            $user->delete();

            return response()->json([
                'status' => 'success',
                'message' => "$username has been deleted"
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Bad credentials'
            ],401);
        }
    }

    public function destroybyUsername($username)
    {
        $user = User::where('username', $username);

        if (!$user) {
            return response()->json([
                'status' => 'failed',
                'message' => "$username not found"
            ], 404);
        }

        if ($user->photo != 'images/profile/photoPlaceholder.jpg') {
            unlink(public_path($user->photo));
        }

        $user->tokens()->delete();
        
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => "$username has been deleted"
        ]);
    }

    public function tokenValidity(Request $request)
    {
        $id = substr($request['token'], 0, 1);

        $token = substr($request['token'], 2);
        $hashedToken = hash('sha256', $token);

        $valid = DB::table('personal_access_tokens')
                ->where('token', "=", $hashedToken)
                ->get();

        if ($hashedToken == $valid->token) {
            return response()->json('valid');
        } else {
            return response()->json('invalid', 404);
        }
    }
}
