<?php

namespace App\Services;

use App\Repositories\StudentAttendanceRepository;

class StudentAttendanceService
{
    public function __construct(protected StudentAttendanceRepository $repo) {}

    public function mark(array $data)
    {
        return $this->repo->mark($data);
    }

    public function listBySession($sessionId)
    {
        return $this->repo->listBySession($sessionId);
    }

    public function listByStudent($studentId)
    {
        return $this->repo->listByStudent($studentId);
    }
}
