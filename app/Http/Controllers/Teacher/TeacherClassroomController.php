<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Classroom\TeacherClassroomStoreRequest;
use App\Models\Classroom;
use Illuminate\Http\Request;

class TeacherClassroomController extends Controller
{
    /**
     * For teachers to create a class room
     * 
     * URL => /api/classes
     * METHOD => POST
     * MIDDLEWARE => ['auth:sanctum', 'teacher-only']
     */
    public function store(TeacherClassroomStoreRequest $request)
    {
        $fields = $request->validated();
        $user = $request->user();

        $classroom = $user->classrooms()->create($fields);

        return response()->json([
            'status' => 'success',
            'data' => $classroom
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $classroom)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom)
    {
        //
    }
}
