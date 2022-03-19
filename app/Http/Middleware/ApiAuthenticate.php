<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ApiAuthenticate
{
    public function handle($request, Closure $next)
    {
    	if (Auth::guard('api')->check()) {
            return $next($request);
        } else {
            return response()->json([
            	'status' => 'fail',
            	'info' => 'Unauthorized'
            ], 401);
        }
    }
}