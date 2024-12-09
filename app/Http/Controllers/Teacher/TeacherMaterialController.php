<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Material\TeacherMaterialStoreRequest;
use App\Models\Classroom;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TeacherMaterialController extends Controller
{
    /**
     * Create a material for a class
     * 
     * URL => /api/classes/{uuid}/materials
     * METHOD => POST
     * MIDDLEWARE => ['auth:sanctum', 'class_owner-only']
     */
    public function store(TeacherMaterialStoreRequest $request, Classroom $classroom)
    {
        $fields = $request->validated();
        $user = $request->user();

        if (isset($fields['files'])) {
            $files = $fields['files'];
            unset($fields['files']);
        }

        DB::beginTransaction();

        $material = $classroom->materials()->create($fields);

        if (isset($files)) {
            foreach ($files as $file) {
                $path = Storage::disk('public')->putFileAs(
                    'materials',
                    $file,
                    $file->hashName()
                );

                if (!$path) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to upload file'
                    ], 500);
                }

                $fileType = 'attachment';

                if (in_array($file->getClientOriginalExtension(), ['mp4', 'mkv', 'avi'])) {
                    $fileType = 'video';
                }

                $material->files()->create([
                    'file_url' => $path,
                    'type' => $fileType
                ]);
            }
        }

        DB::commit();

        $material->load('files');

        return response()->json([
            'status' => 'success',
            'data' => $material
        ]);
    }
}
