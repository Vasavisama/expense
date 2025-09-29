<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Check credentials with JWT attempt
        if (! $token = JWTAuth::attempt($credentials)) {
            return redirect()->back()->withErrors(['error' => 'Invalid credentials']);
        }

        $user = Auth::user();

        // ✅ Check if user is not activated by admin
        if (! $user->is_active) {
            Auth::logout(); // make sure no session stays
            return redirect()->back()
                ->with('error', 'Admin has not activated your account yet. Please wait until activation.');
        }

        // ✅ If active → proceed with JWT creation
        $token = JWTAuth::fromUser($user);

        // Store JWT in HTTP-only secure cookie
        $cookie = Cookie::make(
            'jwt_token',
            $token,
            60, // minutes
            '/',
            null,
            true,  // Secure
            true,  // HttpOnly
            false,
            'strict'
        );

        // Extract role from JWT payload
        $payload = JWTAuth::setToken($token)->getPayload();
        $role = $payload['role'];

        // Redirect based on role
        if ($role === 'admin') {
            return redirect('/analytics')->withCookie($cookie);
        }

        return redirect('/user-dashboard')->withCookie($cookie);
    }
}
