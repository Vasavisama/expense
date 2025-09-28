<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

        // Check if role is admin
        $role = $request->input('role', 'user'); // default = user
        if ($role === 'admin') {
            // Ensure only one admin exists
            $adminExists = User::where('role', 'admin')->exists();
            if ($adminExists) {
                return redirect()->back()
                    ->withErrors(['role' => 'An admin already exists. Only one admin is allowed.'])
                    ->withInput();
            }
        }

        // Create user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $role,
        ]);

        return redirect('/login')->with('success', 'Registration successful! Please log in.');
    }
}
