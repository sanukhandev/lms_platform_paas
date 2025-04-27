<?php

namespace App\Repositories;

use App\Models\ClassSession;

class ClassSessionRepository
{
    public function bulkInsert(array $sessions)
    {
        return ClassSession::insert($sessions);
    }

    public function getByCourse($courseId)
    {

        return ClassSession::with('course.instructor')->where('course_id', $courseId)->orderBy('class_date')->get();
    }
}
