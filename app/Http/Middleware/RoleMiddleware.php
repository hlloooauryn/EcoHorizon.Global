<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!in_array($user->role, $roles, true)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized action for role: ' . $user->role], 403);
            }
            abort(403, 'Unauthorized access. Your role (' . ucfirst($user->role) . ') does not have permission to view this resource.');
        }

        return $next($request);
    }
}

