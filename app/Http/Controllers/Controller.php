<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Return user data if username exist, return false if doesn't exist
     *
     * @param  str $username
     * @return obj/bool
     */
    public function isValidUsername($username)
    {
        $user = User::where('username', $username)->first();
        if (!$user) {
            return false;
        } else {
            return $user;
        }
    }

    /**
     * Return type data if type exist, return false if doesn't exist
     *
     * @param  str $type
     * @return obj/bool
     */
    public function isValidType($type)
    {
        $type = Type::where('name', $type)->first();
        if (!$type) {
            return false;
        } else {
            return $type;
        }
    }
}
