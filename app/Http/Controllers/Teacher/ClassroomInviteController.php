<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Classroom\ClassroomInviteRequest;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;

class ClassroomInviteController extends Controller
{
    /**
     * For teachers to invite students
     * 
     * URL => /api/classes/{uuid}/invite
     * METHOD => POST
     * MIDDLEWARE => ['auth:sanctum', 'teacher-only']
     */
    public function invite(ClassroomInviteRequest $request, Classroom $classroom)
    {
        $fields = $request->validated();
        $user = $request->user();

        if ($user->id !== $classroom->teacher_id) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Forbidden',
                'data' => null
            ], 403);
        }

        $createdInvites = [];
        foreach ($fields['students'] as $emails) {
            $studentId = User::query()
                ->where(['email' => $emails, 'role' => 'student'])
                ->pluck('id')
                ->first();

            if ($studentId) {
                // To prevent inviting an already invited student or a student already in the classroom
                if (
                    $classroom->invites()->where([
                        'student_id' => $studentId,
                        'status' => 'pending'
                    ])->exists()
                    || $classroom->students()->where([
                        'id' => $studentId
                    ])->exists()
                ) {
                    continue;
                }

                $createdInvites[] = $classroom->invites()->create([
                    'student_id' => $studentId
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => count($createdInvites) > 0 ? $createdInvites : null
        ]);
    }
}
