<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
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
        ]);

        return response()->json([
            'status' => 'success',
            'data' => null
        ], 200);
    }
}
