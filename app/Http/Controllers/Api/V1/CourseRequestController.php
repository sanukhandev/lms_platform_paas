<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequestRequest;
use App\Http\Resources\CourseRequestResource;
use App\Models\CourseRequest;
use App\Services\CourseRequestService;
use Illuminate\Support\Facades\Auth;

class CourseRequestController extends Controller
{
    public function __construct(protected CourseRequestService $service) {}

    public function index()
    {
        return CourseRequestResource::collection($this->service->list());
    }

    public function store(StoreCourseRequestRequest $request)
    {
        $data = $request->validated();
        $data['student_id'] = Auth::id();

        $existing = CourseRequest::where('student_id', $data['student_id'])
            ->where('course_id', $data['course_id'])->first();

        if ($existing) {
            return response()->json(['message' => 'You have already requested this course.'], 409);
        }

        $request = $this->service->store($data);
        return new CourseRequestResource($request);
    }

    public function approve(CourseRequest $request)
    {
        $approved = $this->service->approve($request);
        return new CourseRequestResource($approved);
    }

    public function reject(CourseRequest $request)
    {
        $rejected = $this->service->reject($request);
        return new CourseRequestResource($rejected);
    }
}
