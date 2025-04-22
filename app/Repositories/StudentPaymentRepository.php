<?php

namespace App\Repositories;

use App\Models\StudentPayment;

class StudentPaymentRepository
{
    public function store(array $data): StudentPayment
    {
        return StudentPayment::create($data);
    }

    public function listByStudent($studentId)
    {
        return StudentPayment::with(['course', 'paymentPlan'])->where('student_id', $studentId)->get();
    }
}
