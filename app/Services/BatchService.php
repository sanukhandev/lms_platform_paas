<?php

namespace App\Services;

use App\Models\Batch;
use App\Models\ClassSession;
use App\Repositories\BatchRepository;
use App\Repositories\CourseRepository;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class BatchService
{
    public function __construct(
        protected BatchRepository $repo,
        protected CourseRepository $courseRepo
    ) {}

    // 🔹 Batch CRUD

    public function list()
    {
        return $this->repo->all();
    }

    public function create(array $data)
    {
        $studentIds = $data['student_ids'] ?? [];
        if (!empty($studentIds)) {
            $studentIds = array_map('intval', $studentIds);
            $data['student_ids'] = $studentIds;
        }
        unset($data['student_ids']);

        $batch = $this->repo->store($data);

        if (!empty($studentIds)) {
            $pivotData = collect($studentIds)->mapWithKeys(fn($id) => [
                $id => ['status' => 'active']
            ])->toArray();

            $batch->students()->attach($pivotData);
        }

        return $batch;
    }

    public function update(Batch $batch, array $data)
    {
        return $this->repo->update($batch, $data);
    }

    public function delete(Batch $batch)
    {
        return $this->repo->destroy($batch);
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

    // 🔹 ClassSession CRUD

    public function createClassSession(Batch $batch, array $data)
    {
        return $this->repo->createClassSession($batch, $data);
    }

    public function createMultipleClassSessions(Batch $batch, array $sessions)
    {
        return $this->repo->createMultipleClassSessions($batch, $sessions);
    }

    public function updateClassSession(ClassSession $classSession, array $data)
    {
        return $this->repo->updateClassSession($classSession, $data);
    }

    public function deleteClassSession(ClassSession $classSession)
    {
        return $this->repo->destroyClassSession($classSession);
    }

    public function findClassSession($id)
    {
        return $this->repo->findClassSession($id);
    }

    public function getClassSessionsByBatchId($batchId)
    {
        return $this->repo->getClassSessionsByBatchId($batchId);
    }

    public function getClassSessionByBatchAndDate(Batch $batch, string $date)
    {
        return $this->repo->getClassSessionByBatchAndDate($batch, $date);
    }

    public function getClassSessionByBatchAndDateRange(Batch $batch, string $start, string $end)
    {
        return $this->repo->getClassSessionByBatchAndDateRange($batch, $start, $end);
    }

    public function getClassSessionByBatchAndTime(Batch $batch, string $start, string $end)
    {
        return $this->repo->getClassSessionByBatchAndTime($batch, $start, $end);
    }

    public function getUpcomingClassSessions(Batch $batch)
    {
        return $this->repo->getUpcomingClassSessions($batch);
    }

    public function getTodayClassSessions(Batch $batch)
    {
        return $this->repo->getTodayClassSessions($batch);
    }

    public function getGroupedSessionsByDate(Batch $batch)
    {
        return $this->repo->getClassSessionsGroupedByDate($batch);
    }

    // 🔹 Course Info

    public function getCourses()
    {
        return $this->courseRepo->all();
    }

    public function getCourseById($id)
    {
        return $this->courseRepo->find($id);
    }

    // 🔹 Auto Generate Sessions from Weekday Schedule

    public function generateClassSessionsFromSchedule(Batch $batch, bool $isUpdate): void
    {
        $startDate = $isUpdate ? Carbon::parse($batch->start_date)->startOfDay() : Carbon::now()->startOfDay();
        $endDate = Carbon::parse($batch->end_date);
        $weekDays = $batch->session_days; // ['Monday', 'Wednesday']
        $startTime = Carbon::parse($batch->session_start_time)->format('H:i:s');
        $endTime = Carbon::parse($batch->session_end_time)->format('H:i:s');

        $dates = [];

        foreach (CarbonPeriod::create($startDate, $endDate) as $date) {
            if (in_array($date->format('l'), $weekDays)) {
                $dates[] = [
                    'date' => $date->format('Y-m-d'),
                    'start_time' => $startTime,
                    'end_time' => $endTime
                ];
            }
        }

        $this->repo->createMultipleClassSessions($batch, $dates);
    }

    // get batch by course id
    public function getBatchesByCourseId($courseId)
    {
        return $this->repo->getBatchesByCourseId($courseId);
    }

    public function deleteClassSessions(Batch $batch)
    {
        $this->repo->deleteClassSessions($batch);
    }

    // addStudentsToBatch
    public function addStudentsToBatch(Batch $batch, array $studentIds)
    {
        if (!empty($studentIds)) {
            $studentIds = array_map('intval', $studentIds);
            $batch->students()->attach($studentIds);
        }
    }

    // 🔹 Meeting Link and Actions

    public function generateMeetingLink(ClassSession $session)
    {
        $jitsiBaseUrl = 'https://meetservice.dw-digitalplatforms.in/'; // Replace with your Jitsi URL
        $roomName = 'class-session-' . $session->id;
        $meetingLink = $jitsiBaseUrl . '/' . $roomName;
        $session->update(['meeting_link' => $meetingLink]);
        return $meetingLink;
    }

    public function rescheduleClassSession(ClassSession $session, array $data)
    {
        $session->update($data);
        return $session;
    }

    public function cancelClassSession(ClassSession $session)
    {
        $session->update(['class_status' => 'cancelled']);
        $newSession = $this->repo->createClassSession($session->batch, [
            'date' => Carbon::now()->addDays(7)->format('Y-m-d'), // Example: adding 7 days
            'start_time' => $session->start_time,
            'end_time' => $session->end_time,
            'class_status' => 'scheduled'
        ]);

        return $newSession;
    }
}
