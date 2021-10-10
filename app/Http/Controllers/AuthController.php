<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;


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

        $firstNameChar = strtoupper($user->name[0]);
        $user->photo = "https://dummyimage.com/150/15748f/ffffff&text=$firstNameChar";

        $user->save();

        $token = $user->createToken('myapptoken')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $user->id,
            'email' => 'required|string|unique:users,email,' . $user->id,
        ]);

        if (is_null($user)) {
            return response()->json([
                'status' => 'error',
                'message' => "User not found"
            ], 404);
        }

        if ($request['password'] != null) {
            $user->password = bcrypt($request['password']);
        }
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

        $firstNameChar = strtoupper($user->name[0]);

        if ($request['photo']) {
            $user->photo = $request['photo'];
        }

        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated',
            'data' => $user
        ]);
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
            ], 401);
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
            // if ($user->photo != 'images/profile/photoPlaceholder.jpg') {
            //     unlink(public_path($user->photo));
            // }

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
            ], 401);
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

        // if ($user->photo != 'images/profile/photoPlaceholder.jpg') {
        //     unlink(public_path($user->photo));
        // }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => "$username has been deleted"
        ]);
    }

    public function resetRequest(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json([
                'status' => 'success',
                'message' => "email sent | $status"
            ])
            : response()->json([
                'status' => 'failed',
                'message' => "unable to send email | $status"
            ]);
    }

    public function reset($token, Request $request)
    {
        $request->token = $token;

        $request->request->add(['token' => $token]);
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),

            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );
        return $status === Password::PASSWORD_RESET
            ? response()->json([
                'status' => 'success',
                'message' => "password updated | $status"
            ])
            : response()->json([
                'status' => 'failed',
                'message' => "password cannot be updated | $status"
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
