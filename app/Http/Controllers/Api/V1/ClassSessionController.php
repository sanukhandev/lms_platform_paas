<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClassSessionResource;
use App\Models\Batch;
use App\Models\ClassSession;
use App\Services\BatchService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ClassSessionController extends Controller
{
    public function __construct(protected BatchService $service) {}

    // 🔹 GET /batches/{batchId}/sessions
    public function index($batchId)
    {
        $sessions = $this->service->getClassSessionsByBatchId($batchId);
        return ClassSessionResource::collection($sessions);
    }

    // 🔹 GET /sessions/{id}
    public function show($id)
    {
        $session = $this->service->findClassSession($id);
        return new ClassSessionResource($session);
    }

    // 🔹 POST /batches/{batchId}/sessions
    public function store(Request $request, $batchId)
    {
        $batch = $this->service->find($batchId);

        $data = $request->validate([
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        $session = $this->service->createClassSession($batch, $data);
        return new ClassSessionResource($session);
    }

    // 🔹 PUT /sessions/{id}
    public function update(Request $request, $id)
    {
        $session = $this->service->findClassSession($id);

        $data = $request->validate([
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        $updated = $this->service->updateClassSession($session, $data);
        return new ClassSessionResource($updated);
    }

    // 🔹 DELETE /sessions/{id}
    public function destroy($id)
    {
        $session = $this->service->findClassSession($id);
        $this->service->deleteClassSession($session);

        return response()->json(['message' => 'Class session deleted successfully.']);
    }

    // 🔹 GET /batches/{batchId}/sessions/today
    public function today($batchId)
    {
        $batch = $this->service->find($batchId);
        $sessions = $this->service->getTodayClassSessions($batch);

        return ClassSessionResource::collection($sessions);
    }

    // 🔹 GET /batches/{batchId}/sessions/upcoming
    public function upcoming($batchId)
    {
        $batch = $this->service->find($batchId);
        $sessions = $this->service->getUpcomingClassSessions($batch);

        return ClassSessionResource::collection($sessions);
    }

    // 🔹 GET /batches/{batchId}/sessions/grouped
    public function groupedByDate($batchId)
    {
        $batch = $this->service->find($batchId);
        $sessions = $this->service->getGroupedSessionsByDate($batch);

        return response()->json($sessions);
    }

    // 🔹 GET /batches/{batchId}/sessions/by-date/{date}
    public function byDate($batchId, $date)
    {
        $batch = $this->service->find($batchId);
        $sessions = $this->service->getClassSessionByBatchAndDate($batch, $date);

        return ClassSessionResource::collection($sessions);
    }

    // 🔹 GET /batches/{batchId}/sessions/range?start=Y-m-d&end=Y-m-d
    public function byDateRange(Request $request, $batchId)
    {
        $request->validate([
            'start' => ['required', 'date'],
            'end' => ['required', 'date', 'after_or_equal:start'],
        ]);

        $batch = $this->service->find($batchId);
        $sessions = $this->service->getClassSessionByBatchAndDateRange(
            $batch,
            $request->start,
            $request->end
        );

        return ClassSessionResource::collection($sessions);
    }

    // 🔹 GET /batches/{batchId}/sessions/by-time?start=HH:mm&end=HH:mm
    public function byTime(Request $request, $batchId)
    {
        $request->validate([
            'start' => ['required', 'date_format:H:i'],
            'end' => ['required', 'date_format:H:i', 'after:start'],
        ]);

        $batch = $this->service->find($batchId);
        $sessions = $this->service->getClassSessionByBatchAndTime(
            $batch,
            $request->start,
            $request->end
        );

        return ClassSessionResource::collection($sessions);
    }


    // 🔹 POST /sessions/{id}/generate-meeting-lin
    public function generateMeetingLink($id)
    {
        $session = $this->service->findClassSession($id);
        $meetingLink = $this->service->generateMeetingLink($session);

        return response()->json(['meeting_link' => $meetingLink]);
    }

    // 🔹 POST /sessions/{id}/reschedule
    public function reschedule(Request $request, $id)
    {
        $session = $this->service->findClassSession($id);

        $data = $request->validate([
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        $updated = $this->service->rescheduleClassSession($session, $data);
        return new ClassSessionResource($updated);
    }

    // 🔹 POST /sessions/{id}/cancel
    public function cancel($id)
    {
        $session = $this->service->findClassSession($id);
        $this->service->cancelClassSession($session);

        return response()->json(['message' => 'Class session cancelled successfully.']);
    }
    
}
