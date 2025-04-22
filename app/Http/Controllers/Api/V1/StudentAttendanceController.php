<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\MarkAttendanceRequest;
use App\Http\Resources\StudentAttendanceResource;
use App\Services\StudentAttendanceService;
use Illuminate\Http\Request;

class StudentAttendanceController extends Controller
{
    public function __construct(protected StudentAttendanceService $service) {}

    public function mark(MarkAttendanceRequest $request)
    {
        $attendance = $this->service->mark($request->validated());
        return new StudentAttendanceResource($attendance);
    }

    public function sessionAttendance($sessionId)
    {
        $records = $this->service->listBySession($sessionId);
        return StudentAttendanceResource::collection($records);
    }

    public function myAttendance(Request $request)
    {
        $records = $this->service->listByStudent($request->user()->id);
        return StudentAttendanceResource::collection($records);
    }
}
