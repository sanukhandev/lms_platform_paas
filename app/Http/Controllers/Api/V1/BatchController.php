<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BatchStoreRequest;
use App\Http\Requests\BatchUpdateRequest;
use App\Http\Resources\BatchResource;
use App\Models\Batch;
use App\Services\BatchService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BatchController extends Controller
{
    public function __construct(protected BatchService $service) {}

    public function index()
    {
        $batches = $this->service->list();
        return BatchResource::collection($batches);
    }

    public function show($id)
    {
        $batch = $this->service->find($id);
        $batch->load(['students', 'classSessions']);
        return new BatchResource($batch);
    }

    public function store(BatchStoreRequest $request)
    {

        $request->validated();
        $data = [
            ...$request->only([
                'course_id',
                'name',
                'start_date',
                'end_date'
            ]),
            'student_ids' => $request->input('student_ids'),
            'session_days' => $request->input('session_days'),
            'session_start_time' => $request->input('session_time.start'),
            'session_end_time' => $request->input('session_time.end'),
        ];

        $batch = $this->service->create($data);
        $this->service->generateClassSessionsFromSchedule($batch, false);

        return new BatchResource($batch);
    }

    public function update(BatchUpdateRequest $request, $id)
    {
        $batch = $this->service->find($id);

        $data = [
            ...$request->only([
                'course_id',
                'name',
                'start_date',
                'end_date'
            ]),
            'session_days' => $request->input('session_days'),
            'session_start_time' => $request->input('session_time.start'),
            'session_end_time' => $request->input('session_time.end'),
        ];
        // rengerate class sessions if session days or time has changed
        if ($request->has('session_days') || $request->has('session_time')) {
            $this->service->deleteClassSessions($batch);
            $this->service->generateClassSessionsFromSchedule($batch, true);
        }
        $updatedBatch = $this->service->update($batch, $data);
        return new BatchResource($updatedBatch);
    }

    public function destroy($id)
    {
        $batch = $this->service->find($id);
        $this->service->delete($batch);

        return response()->json(['message' => 'Batch deleted successfully'], Response::HTTP_NO_CONTENT);
    }

    // get batch by course id
    public function getByCourse($courseId)
    {
        $batches = $this->service->getBatchesByCourseId($courseId);
        return BatchResource::collection($batches);
    }

    // addStudents
    public function addStudents(Request $request, $batchId)
    {
        $batch = $this->service->find($batchId);
        $studentIds = $request->input('student_ids');

        if (empty($studentIds)) {
            return response()->json(['message' => 'No student IDs provided'], Response::HTTP_BAD_REQUEST);
        }

        $this->service->addStudentsToBatch($batch, $studentIds);

        return response()->json(['message' => 'Students added to batch successfully'], Response::HTTP_OK);
    }
}
