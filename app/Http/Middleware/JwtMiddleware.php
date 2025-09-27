<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->cookie('jwt_token');

        if (! $token) {
            return redirect('/login')->withErrors(['error' => 'Unauthorized']);
        }

        try {
            $user = JWTAuth::setToken($token)->authenticate();
        } catch (JWTException $e) {
            return redirect('/login')->withErrors(['error' => 'Token invalid']);
        }

        return $next($request);
    }
}
