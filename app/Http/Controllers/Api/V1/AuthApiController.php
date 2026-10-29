<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password credentials.',
            ], 401);
        }

        if ($user->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Account is suspended. Please contact administrator.',
            ], 403);
        }

        $tokenName = $validated['device_name'] ?? 'api-token';
        $token = $user->createToken($tokenName)->plainTextToken;

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'API_LOGIN',
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'description' => "User {$user->email} authenticated via REST API.",
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Authentication successful.',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'company_name' => $user->company_name,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'API token revoked successfully.',
        ], 200);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
    }
}

