<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * If the user is authenticated but not active, log them out and redirect to login with error.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && !$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account is not active yet. An administrator must activate it before you can log in.');
        }

        return $next($request);
    }
}
