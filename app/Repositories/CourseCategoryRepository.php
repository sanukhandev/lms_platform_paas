<?php

namespace App\Repositories;

use App\Models\CourseCategory;

class CourseCategoryRepository
{
    public function all()
    {
        return CourseCategory::with(['parent', 'children'])->get();
    }

    public function store(array $data)
    {
        return CourseCategory::create($data);
    }

    public function update(CourseCategory $category, array $data)
    {
        $category->update($data);
        return $category;
    }

    public function destroy(CourseCategory $category)
    {
        return $category->delete();
    }

    public function find($id)
    {
        return CourseCategory::with(['parent', 'children', 'courses'])->findOrFail($id);
    }
}

