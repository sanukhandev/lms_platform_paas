<?php

namespace App\Services;

use App\Repositories\ClassSessionRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class ClassSessionService
{
    private const MEETING_HOST = 'https://meetservice.dw-digitalplatforms.in';
    private const MEETING_PATH = '/LMS-Class-';

    public function __construct(protected ClassSessionRepository $repo) {}

    public function generate(array $data)
    {
        $start = Carbon::parse($data['start_date']);
        $end = $start->copy()->addWeeks((int) $data['duration_weeks']);
        $daysOfWeek = $data['days_of_week'] ?? [];

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

    public function getSessionById($sessionId)
    {
        $session = $this->repo->getById($sessionId);
        if ($session) {
            return $session;
        }
    }

    public function startMeeting($sessionId)
    {
        $session = \App\Models\ClassSession::findOrFail($sessionId);
        $meetingLink = self::MEETING_HOST . self::MEETING_PATH . "{$session->id}-" . time();
        $session->update(['meeting_link' => $meetingLink]);

        return $session;
    }
}
