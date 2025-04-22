<?php

namespace App\Repositories;

use App\Models\StudentAttendance;

class StudentAttendanceRepository
{
    public function mark(array $data)
    {
        return StudentAttendance::updateOrCreate(
            ['student_id' => $data['student_id'], 'session_id' => $data['session_id']],
            ['status' => $data['status']]
        );
    }

    public function listBySession($sessionId)
    {
        return StudentAttendance::where('session_id', $sessionId)->get();
    }

    public function listByStudent($studentId)
    {
        return StudentAttendance::where('student_id', $studentId)->with('session')->get();
    }
}
