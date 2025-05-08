<?php

namespace App\Services;

use App\Models\CourseCategory;
use App\Repositories\CourseCategoryRepository;

class CourseCategoryService
{
    public function __construct(protected CourseCategoryRepository $repo) {}

    public function list()
    {
        return $this->repo->all();
    }

    public function create(array $data)
    {
        return $this->repo->store($data);
    }

    public function update(CourseCategory $category, array $data)
    {
        return $this->repo->update($category, $data);
    }

    public function delete(CourseCategory $category)
    {
        return $this->repo->destroy($category);
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }
}

