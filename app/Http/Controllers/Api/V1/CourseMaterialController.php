<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadCourseMaterialRequest;
use App\Http\Resources\CourseMaterialResource;
use App\Models\CourseMaterial;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseMaterialController extends Controller
{
    public function upload(UploadCourseMaterialRequest $request)
    {
        $file = $request->file('file');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs('materials', $filename); // stored in storage/app/materials

        $material = CourseMaterial::create([
            'course_id' => $request->course_id,
            'uploaded_by' => $request->user()->id,
            'title' => $request->title,
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        return new CourseMaterialResource($material);
    }

    public function list($courseId)
    {
        $materials = CourseMaterial::where('course_id', $courseId)->get();
        return CourseMaterialResource::collection($materials);
    }

    public function download($id)
    {
        $material = CourseMaterial::findOrFail($id);
        return Storage::download($material->file_path, $material->title);
    }
}
