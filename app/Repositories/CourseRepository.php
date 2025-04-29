<?php

namespace App\Repositories;

use App\Models\Course;

class CourseRepository
{
    public function all()
    {
        return Course::with('instructor')->get();
    }

    public function store(array $data)
    {
        return Course::create($data);
    }

    public function update(Course $course, array $data)
    {
        $course->update($data);
        return $course;
    }

    public function destroy(Course $course)
    {
        return $course->delete();
    }

    public function find($id)
    {
        return Course::with('instructor')->findOrFail($id);
    }
}
