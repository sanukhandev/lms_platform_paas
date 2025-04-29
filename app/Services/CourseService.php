<?php

namespace App\Services;

use App\Models\Course;
use App\Repositories\CourseRepository;

class CourseService
{
    public function __construct(protected CourseRepository $repo) {}

    public function list()
    {
        return $this->repo->all();
    }

    public function create(array $data)
    {
        return $this->repo->store($data);
    }

    public function update(Course $course, array $data)
    {
        return $this->repo->update($course, $data);
    }

    public function delete(Course $course)
    {
        return $this->repo->destroy($course);
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }
}
