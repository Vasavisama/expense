<?php


namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cookie;


class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        $token = $request->cookie('jwt_token');


        if ($token) {
            try {
                JWTAuth::setToken($token)->invalidate();  // Blacklist the token
            } catch (\Exception $e) {
                // Handle if token is already invalid or error
            }
        }


        // Forget the cookie
        $cookie = Cookie::forget('jwt_token');


        return redirect('/login')->withCookie($cookie)->with('success', 'Logged out successfully!');
    }
}
