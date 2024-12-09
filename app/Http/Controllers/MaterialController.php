<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Get all materials of a classroom
     * 
     * URL => /api/classes/{uuid}/materials
     * METHOD => GET
     * MIDDLEWARE => ['auth:sanctum', 'class_member-only']
     */
    public function index(Classroom $classroom)
    {
        return response()->json([
            'status' => 'success',
            'data' => $classroom->materials()->get()->map(function ($material) {
                $material->load('files');
                return $material;
            })
        ]);
    }
}
