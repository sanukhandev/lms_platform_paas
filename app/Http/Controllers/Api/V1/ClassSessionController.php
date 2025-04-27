<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateClassScheduleRequest;
use App\Models\Course;
use App\Services\ClassSessionService;
use Illuminate\Http\Request;
use App\Http\Resources\ClassSessionResource;

class ClassSessionController extends Controller
{
    public function __construct(protected ClassSessionService $service) {}

    public function generate(GenerateClassScheduleRequest $request)
    {
        $this->service->generate($request->validated());
        return response()->json(['message' => 'Class schedule generated']);
    }

    public function index($id)
    {
        return ClassSessionResource::collection(
            $this->service->listByCourse($id)
        );
    }

    public function startMeeting(Request $request, $sessionId)
    {
        $session = $this->service->startMeeting($sessionId);
        return response()->json([
            'message' => 'Meeting started',
            'link' => $session->meeting_link,
        ]);
    }
}
