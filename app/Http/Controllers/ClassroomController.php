<?php

namespace App\Http\Controllers;

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
        return response()->json([
            'status' => 'success',
            'data' => $request->user()->classrooms
        ]);
    }
}
