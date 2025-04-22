<?php

namespace App\Services;

use App\Repositories\ClassSessionRepository;
use Carbon\Carbon;

class ClassSessionService
{
    public function __construct(protected ClassSessionRepository $repo) {}

    public function generate(array $data)
    {
        $start = Carbon::parse($data['start_date']);
        $end = $start->copy()->addWeeks($data['duration_weeks']);
        $daysOfWeek = $data['days_of_week'];

        $sessions = [];

        while ($start->lte($end)) {
            if (in_array($start->format('l'), $daysOfWeek)) {
                $sessions[] = [
                    'course_id' => $data['course_id'],
                    'class_date' => $start->format('Y-m-d'),
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            $start->addDay();
        }

        return $this->repo->bulkInsert($sessions);
    }

    public function listByCourse($courseId)
    {
        return $this->repo->getByCourse($courseId);
    }
}
