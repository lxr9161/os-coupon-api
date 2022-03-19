<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class BackendLogin
{
    public function handle($request, Closure $next)
    {
    	if (Auth::guard('backend')->check()) {
            return $next($request);
        } else {
            return response()->json([
            	'status' => 'fail',
            	'info' => 'Unauthorized'
            ], 401);
        }
    }
}