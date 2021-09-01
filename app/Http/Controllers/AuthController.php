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
            'photo' => 'required|file|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = new User;

        $user->name = $request['name'];
        $user->username = $request['username'];
        $user->email = $request['email'];
        $user->password = bcrypt($request['password']);
        $user->introduction = $request['introduction'];
        $user->more_info = $request['more_info'];
        $user->theme_id = $request['theme_id'];

        if ($user->facebook) {
            $user->facebook = "https://facebook.com/".$request['facebook'];
        } else {
            $user->facebook = $request['facebook'];
        }

        
        if ($user->instagram) {
            $user->instagram = "https://instagram.com/".$request['instagram'];
        } else {
            $user->instagram = $request['instagram'];
        }

        if ($user->linkedin) {
            $user->linkedin = "https://linkedin.com/in/".$request['linkedin'];
        } else {
            $user->linkedin = $request['linkedin'];
        }

        if ($user->github) {
            $user->github = "https://github.com/".$request['github'];
        } else {
            $user->github = $request['github'];
        }
        
        if ($uploadedPhoto = $request->file('photo')) {
            $destinationPath = 'images/profile/';
            $imageName = $request->username.'.'.time().'.'.$uploadedPhoto->extension();  
            $uploadedPhoto->move(public_path($destinationPath), $imageName);
            $user->photo = $destinationPath.$imageName;
        } else {
            $user->photo = 'images/profile/photoPlaceholder.jpg';
        }
        
        $user->save();

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response()->json($response, 201);
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
                'message' => 'Bad Credentials'
            ],401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response()->json($response);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged Out',
        ], 200);
    }

    public function destroy($username)
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json([
                'status' => 'failed',
                'message' => "username of $username not found"
            ], 404);
        }

        if ($user->photo != 'images/profile/photoPlaceholder.jpg') {
            unlink(public_path($user->photo));
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => "$username has been deleted"
        ]);
    }
}
