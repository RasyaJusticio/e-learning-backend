<?php

namespace App\Http\Controllers;

use App\Http\Requests\InviteRespondRequest;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    /**
     * For students to view their invites
     * 
     * URL => /api/invites
     * METHOD => GET
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

    /**
     * For students to respond to an invitation
     * 
     * URL => /api/invites/{invite_id}/respond
     * METHOD => POST
     * MIDDLEWARE => ['auth:sanctum', 'student-only']
     */
    public function respond(InviteRespondRequest $request, Invite $invite)
    {
        $user = $request->user();

        if ($invite->student_id !== $user->id) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Forbidden',
                'data' => null
            ], 403);
        }

        if ($invite->status !== 'pending') {
            return response()->json([
                'status' => 'fail',
                'message' => 'The invitation has already been responded to',
                'data' => null
            ], 400);
        }

        $response = $request->input('response');
        if ($response === 'accept') {
            $invite->accept();

            $invite->load('classroom');

            $invite->classroom->students()->attach($user->id);
        } elseif ($response === 'decline') {
            $invite->decline();
        }

        return response()->json([
            'status' => 'success',
            'data' => $invite
        ]);
    }
}
