<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendWelcomeEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validation with role included
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'nullable|in:admin,user', // role must be admin or user (optional field)
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check role
        $role = $request->input('role', 'user'); // default = user

        if ($role === 'admin') {
            // Ensure only one admin exists
            $adminExists = User::where('role', 'admin')->exists();
            if ($adminExists) {
                return redirect()->back()
                    ->withErrors(['role' => 'An admin already exists. Only one admin is allowed.'])
                    ->withInput();
            }

            // Admins should be active immediately
            $user = User::create([
                'name'        => $request->name,
                'email'       => $request->email,
                'password'    => Hash::make($request->password),
                'role'        => 'admin',
                'is_active'   => true,
                'activated_at'=> now(),
            ]);

            SendWelcomeEmail::dispatch($user);

            return redirect('/login')->with('success', 'Admin account created and activated. You can log in immediately.');
        }

        // Normal user: inactive by default
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'user',
            'is_active' => false,
        ]);

        SendWelcomeEmail::dispatch($user);

        return redirect('/login')->with('success', 'Registration successful. Your account will be activated by an administrator.');
    }
}
