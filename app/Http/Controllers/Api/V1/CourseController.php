<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Services\CourseService;

class CourseController extends Controller
{
    public function __construct(protected CourseService $service) {}

    public function index()
    {
        return CourseResource::collection($this->service->list());
    }

    public function store(StoreCourseRequest $request)
    {
        $course = $this->service->create($request->validated());
        return new CourseResource($course);
    }

    public function show(Course $course)
    {
        return new CourseResource($course);
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        $updated = $this->service->update($course, $request->validated());
        return new CourseResource($updated);
    }

    public function destroy(Course $course)
    {
        $this->service->delete($course);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
