<?php

namespace App\Http\Controllers;

use App\Http\Requests\Me\MeUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;

class MeController extends Controller
{
    /**
     * URL => /api/me
     * METHOD => PATCH
     */
    public function update(MeUpdateRequest $request)
    {
        $fields = $request->validated();
        $user = $request->user();

        // Prevents field from just being an empty string
        foreach ($fields as $field => $value) {
            if (trim($value) === "") {
                unset($fields[$field]);
            }
        }

        if (isset($fields['username'])) {
            if ($user->username_changed_at && $user->username_changed_at->diffInHours(now()) < 24) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Username can only be changed every 24 hours.',
                    'data' => null
                ], 403);
            }

            $fields['username_changed_at'] = now();
        }

        $user->update($fields);

        return response()->json([
            'status' => 'success',
            'data' => [
                'name' => $user->name,
                'username' => $user->username,
            ]
        ], 200);
    }
}
