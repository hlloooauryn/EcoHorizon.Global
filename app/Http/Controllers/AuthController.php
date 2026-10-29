<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        // Pass demo accounts to help test and evaluate
        $demoUsers = User::whereIn('email', [
            'admin@ecohorizon.test',
            'operator@ecohorizon.test',
            'customer@globalcargo.test',
            'buyer@bavariaindustries.de',
        ])->get();

        return view('auth.login', compact('demoUsers'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->status !== 'active') {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Your account is suspended. Please contact the administrator.',
                ]);
            }

            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'LOGIN',
                'auditable_type' => User::class,
                'auditable_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'description' => "User {$user->email} logged into system.",
                'created_at' => now(),
            ]);

            return $this->redirectBasedOnRole($user);
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'LOGOUT',
                'auditable_type' => User::class,
                'auditable_id' => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'description' => "User " . Auth::user()->email . " signed out.",
                'created_at' => now(),
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been safely signed out.');
    }

    protected function redirectBasedOnRole(User $user)
    {
        return match ($user->role) {
            'admin' => redirect()->intended(route('admin.dashboard')),
            'operator' => redirect()->intended(route('operator.dashboard')),
            'customer' => redirect()->intended(route('customer.dashboard')),
            default => redirect()->intended(route('home')),
        };
    }
}

