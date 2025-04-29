<?php

namespace App\Services;

use App\Repositories\ClassSessionRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class ClassSessionService
{
    public function __construct(protected ClassSessionRepository $repo) {}

    public function generate(array $data)
    {
        $start = Carbon::parse($data['start_date']);
        $end = $start->copy()->addWeeks((int) $data['duration_weeks']); // CAST TO INT here
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


    // get sesion by id
    public function getSessionById($sessionId)
    {
        // First, check if session already exists locally
        $session = $this->repo->getById($sessionId);

        if (!$session) {
            throw new \Exception('Session not found');
        }


        // Otherwise, create new Huddle01 meeting dynamically
        $huddleApiKey = env('HUDDLE_API_KEY');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-api-key' => $huddleApiKey,
        ])->post('https://api.huddle01.com/api/v1/create-room', [
            'title' => 'Session-' . $sessionId,
            'roomLocked' => false,
        ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to create Huddle01 meeting: ' . $response->body());
        }

        $roomData = $response->json();

        // Update the session record with new meeting link
        $session->update([
            'meeting_link' => $roomData['roomUrl'],
        ]);

        return [
            'meeting_link' => $roomData['roomUrl'],
        ];
    }



    public function startMeeting($sessionId)
    {
        $session = \App\Models\ClassSession::findOrFail($sessionId);
        $meetingLink = "LMS-Class-{$session->id}-" . time();
        $session->update(['meeting_link' => $meetingLink]);

        return $session;
    }
}
