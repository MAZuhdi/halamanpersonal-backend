<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->id == 1) {
            return $next($request);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Only admin can access this route'
            ], 401);
        }
    }
}
