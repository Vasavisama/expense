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


        if (! $token = JWTAuth::attempt($credentials)) {
            return redirect()->back()->withErrors(['error' => 'Invalid credentials']);
        }


        // Store JWT in cookie: HTTP-only, secure, same-site strict
        $cookie = Cookie::make('jwt_token', $token, 60, '/', null, true, true, false, 'strict');


        // Redirect based on role from JWT payload
        $payload = JWTAuth::setToken($token)->getPayload();
        $role = $payload['role'];


        if ($role === 'admin') {
            return redirect('/admin-dashboard')->withCookie($cookie);
        }


        return redirect('/user-dashboard')->withCookie($cookie);
    }
}
