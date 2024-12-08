<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    /**
     * Get a list of classrooms of a user
     * 
     * URL => /api/classes
     * METHOD => GET
     * MIDDLEWARE => ['auth:sanctum']
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $classrooms = [];
        if ($user->role === 'teacher') {
            $classrooms = Classroom::where('teacher_id', $user->id)->get();
        } else if ($user->role === 'student') {
            //
        }

        return response()->json([
            'status' => 'success',
            'data' => $classrooms
        ]);
    }
}
