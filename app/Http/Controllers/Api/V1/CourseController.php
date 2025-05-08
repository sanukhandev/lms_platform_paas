<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    public function __construct(protected CourseService $service) {}

    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => CourseResource::collection($this->service->list()),
            'message' => 'Courses retrieved successfully'
        ]);
    }

    public function store(StoreCourseRequest $request)
    {
        $course = $this->service->create($request->validated());
        return response()->json([
            'success' => true,
            'data' => new CourseResource($course),
            'message' => 'Course created successfully'
        ], 201);
    }

    public function show($id)
    {
        $course = $this->service->find($id);
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => new CourseResource($course),
            'message' => 'Course retrieved successfully'
        ]);
    }

    public function update(UpdateCourseRequest $request)
    {
        $course = $this->service->find($request->id);
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }
        $course = $this->service->update($course, $request->validated());
        return response()->json([
            'success' => true,
            'data' => new CourseResource($course),
            'message' => 'Course updated successfully'
        ]);
    }

    public function destroy(Course $course)
    {
        $this->service->delete($course);
        return response()->json([
            'success' => true,
            'message' => 'Course deleted successfully'
        ]);
    }
}
