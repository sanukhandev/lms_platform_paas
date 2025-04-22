<?php

namespace App\Repositories;

use App\Models\CourseRequest;

class CourseRequestRepository
{
    public function store(array $data): CourseRequest
    {
        return CourseRequest::create($data);
    }

    public function list()
    {
        return CourseRequest::with('course')->latest()->get();
    }

    public function approve(CourseRequest $request)
    {
        $request->update(['status' => 'approved']);
        return $request;
    }

    public function reject(CourseRequest $request)
    {
        $request->update(['status' => 'rejected']);
        return $request;
    }
}
