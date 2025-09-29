<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Jobs\SendWelcomeEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        $users = $query->orderByRaw("role = 'admin' DESC")
                       ->orderBy('name')
                       ->paginate(10);

        return view('dashboard.users.index', compact('users'));
    }

    public function create()
    {
        return abort(404);
    }

    public function store(Request $request)
    {
        return abort(404);
    }

    public function show(User $user)
    {
        return redirect()->route('users.edit', $user);
    }

    public function edit(User $user)
    {
        return view('dashboard.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role'      => 'required|string|in:user,admin',
            'is_active' => 'sometimes|boolean',
        ]);

        $user->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'role'      => $request->role,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

  public function destroy(Request $request, User $user)
{
    if ($user->id === optional(Auth::user())->id) {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'You cannot delete your own account.'], 422);
        }
        return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
    }

    $user->delete();

    if ($request->expectsJson()) {
        return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
    }

    return redirect()->route('users.index')->with('success', 'User deleted successfully.');
}
    public function activate(Request $request, User $user)
    {
        if ($user->is_active) {
            return back()->with('info', 'User already active.');
        }

        $user->is_active   = true;
        $user->activated_at = now();
        $user->save();

        try {
            SendWelcomeEmail::dispatch($user);
        } catch (\Throwable $e) {
            Log::error("Activation email failed for user {$user->id}: " . $e->getMessage());
            return back()->with('success', 'User activated but activation email failed to send.');
        }

        return back()->with('success', 'User activated and activation email sent to ' . $user->email);
    }
}
