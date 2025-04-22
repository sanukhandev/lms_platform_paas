<?php

namespace App\Services;

use App\Models\CourseRequest;
use App\Repositories\CourseRequestRepository;

class CourseRequestService
{
    public function __construct(protected CourseRequestRepository $repo) {}

    public function store(array $data)
    {
        return $this->repo->store($data);
    }

    public function list()
    {
        return $this->repo->list();
    }

    public function approve(CourseRequest $request)
    {
        return $this->repo->approve($request);
    }

    public function reject(CourseRequest $request)
    {
        return $this->repo->reject($request);
    }
}
