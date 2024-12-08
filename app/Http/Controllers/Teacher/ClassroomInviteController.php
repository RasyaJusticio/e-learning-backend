<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Classroom\ClassroomInviteRequest;
use App\Http\Requests\Classroom\ClassroomInvitesRequest;
use App\Models\Classroom;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ClassroomInviteController extends Controller
{
    /**
     * For teachers to invite students
     * 
     * URL => /api/classes/{uuid}/invite
     * METHOD => POST
     * MIDDLEWARE => ['auth:sanctum', 'teacher-only']
     * PARAMS:
     *  - status <array> - a filter to get invites by status. Possible values are:
     *      - pending
     *      - accepted
     *      - declined
     */
    public function index(ClassroomInvitesRequest $request, Classroom $classroom)
    {
        $params = $request->validated();

        if ($request->user()->id !== $classroom->teacher_id) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Forbidden',
                'data' => null
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => $classroom->invites()
                ->when($request->query("status"), function (Builder $query, array $status) {
                    $query->whereIn('status', $status);
                }, function (Builder $query) {
                    $query->where('status', 'pending');
                })
                ->get()
                ->map(function ($invite) {
                    $invite->load('student');
                    return $invite;
                })
        ]);
    }

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

    /**
     * For teachers to delete an invite
     * 
     * URL => /api/classes/{uuid}/invite/{invite_id}
     * METHOD => DELETE
     * MIDDLEWARE => ['auth:sanctum', 'teacher-only']
     */
    public function destroy(Request $request, Classroom $classroom, Invite $invite)
    {
        $user = $request->user();

        if ($user->id !== $classroom->teacher_id) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Forbidden',
                'data' => null
            ], 403);
        }

        $invite->delete();

        return response()->json([
            'status' => 'success',
            'data' => null
        ]);
    }
}
