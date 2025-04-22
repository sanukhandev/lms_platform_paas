<?php

namespace App\Services;

use App\Repositories\StudentPaymentRepository;

class StudentPaymentService
{
    public function __construct(protected StudentPaymentRepository $repo) {}

    public function create(array $data)
    {
        return $this->repo->store($data);
    }

    public function listForStudent($studentId)
    {
        return $this->repo->listByStudent($studentId);
    }
}
