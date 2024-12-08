<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    /**
     * For students to view their invites
     * 
     * URL => /api/invites
     * METHOD => POST
     * MIDDLEWARE => ['auth:sanctum', 'student-only']
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $invites = $user->invites()->get()->map(function ($invite) {
            $invite->load('classroom');
            $invite->classroom->load('teacher');

            $teacher = $invite->classroom->teacher;
            unset($invite->classroom->teacher);

            return [
                'id' => $invite->id,
                'classroom' => $invite->classroom,
                'teacher' => $teacher,
                'created_at' => $invite->created_at,
            ];
        });

        return response()->json([
            'success' => 'success',
            'data' => $invites
        ]);
    }
}
