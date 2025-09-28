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

        // Find user by email
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        // Validate credentials and user existence
        if (!$user || !\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            return redirect()->back()->withErrors(['error' => 'Invalid credentials']);
        }

        // Check if user is active
        if (!$user->is_active) {
            return redirect()->back()->withErrors(['error' => 'Your account is inactive. Please contact an administrator.']);
        }

        // Manually create JWT token
        $token = JWTAuth::fromUser($user);

        // Store JWT in cookie
        $cookie = Cookie::make('jwt_token', $token, 60, '/', null, true, true, false, 'strict');

        // Redirect based on role
        if ($user->role === 'admin') {
            return redirect('/analytics')->withCookie($cookie);
        }

        return redirect('/user-dashboard')->withCookie($cookie);
    }
}
