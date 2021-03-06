<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index($username)
    {
        $user = $this->isValidUsername($username);
        if (!$user) {
            return response()->json([
                'status' => 'not found',
                'message' => "$username not found"
            ], 404);
        };

        if ($user->more_info == null) {
            $user->more_info = '';
        }

        if ($user->introduction == null) {
            $user->introduction = '';
        }

        return response()->json([
            'status' => 'success',
            'message' => "$username detailed profile",
            'data' => $user
        ]);
    }

    public function show()
    {
        return view('create-user');
    }

    public function getUsers()
    {
        $users = User::inRandomOrder()->limit(5)->get();

        return response()->json([
            'message' => '5 Random Users of our web',
            'data' => $users
        ], 200);
    }

    public function getsocmed($username)
    {
        $user = $this->isValidUsername($username);
        if (!$user) {
            return response()->json([
                'status' => 'not found',
                'message' => "$username not found"
            ], 404);
        };

        $usersocmed = [];
        $n = 0;

        if ($user->facebook) {
            $newdata = array(
                'id' => $n += 1,
                'socmed_name' => 'facebook',
                'socmed_link' => "https://facebook.com/" . "$user->facebook"
            );

            $usersocmed[] = $newdata;
        }
        if ($user->instagram) {
            $newdata = array(
                'id' => $n += 1,
                'socmed_name' => 'instagram',
                'socmed_link' => "https://instagram.com/" . "$user->instagram"
            );

            $usersocmed[] = $newdata;
        }
        if ($user->linkedin) {
            $newdata = array(
                'id' => $n += 1,
                'socmed_name' => 'linkedin',
                'socmed_link' => "https://linkedin.com/in/" . "$user->linkedin"
            );

            $usersocmed[] = $newdata;
        }
        if ($user->github) {
            $newdata = array(
                'id' => $n += 1,
                'socmed_name' => 'github',
                'socmed_link' => "https://github.com/" . "$user->github"
            );

            $usersocmed[] = $newdata;
        }
        return response()->json([
            'status' => 'success',
            'message' => "$username's social media ",
            'data' => $usersocmed
        ]);
    }
}
