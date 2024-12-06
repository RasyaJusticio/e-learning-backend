<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * URL => /api/auth/register
     * METHOD => POST
     */
    public function register(RegisterRequest $request)
    {
        $fields = $request->validated();

        // Creates a unique name from their email
        $namePart = strstr($fields['email'], '@', true);
        $uniqueId = Str::random(6);

        User::create([
            'name' => ucfirst($namePart) . $uniqueId,
            'username' => $namePart . $uniqueId,
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'role' => $fields['role'],
            'username_changed_at' => now()->subHours(24),
        ]);

        return response()->json([
            'status' => 'success',
            'data' => null
        ], 200);
    }

    /**
     * URL => /api/auth/login
     * METHOD => POST
     */
    public function login(LoginRequest $request)
    {
        $fields = $request->validated();

        $user = User::query()
            ->where(['email' => $fields['email']])
            ->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'The provided credentials are incorrect.',
                'data' => null
            ], 401);
        }

        $token = $user->createToken('AUTH_TOKEN');
        $cookie = cookie('token', $token->plainTextToken, 60 * 48, null, null, true, true);

        return response()->json([
            'status' => 'success',
            'data' => [
                'username' => $user->username,
                'name' => $user->name
            ]
        ])->cookie($cookie);
    }

    /**
     * URL => /api/auth/logout
     * METHOD => POST
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        $cookie = cookie('token', null, -1, null, null, true, true);

        return response()->json([
            'status' => 'success',
            'data' => null
        ])->cookie($cookie);
    }

    /**
     * URL => /api/auth/logout/all
     * METHOD => POST
     */
    public function logoutFromAll(Request $request)
    {
        $request->user()->tokens()->delete();

        $cookie = cookie('token', null, -1, null, null, true, true);

        return response()->json([
            'status' => 'success',
            'data' => null
        ])->cookie($cookie);
    }
}
