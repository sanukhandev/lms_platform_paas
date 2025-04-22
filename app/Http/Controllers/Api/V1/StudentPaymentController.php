<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentPaymentRequest;
use App\Http\Resources\StudentPaymentResource;
use App\Models\StudentPayment;
use App\Services\StudentPaymentService;
use Illuminate\Http\Request;

class StudentPaymentController extends Controller
{
    public function __construct(protected StudentPaymentService $service) {}

    public function store(StoreStudentPaymentRequest $request)
    {
        $payment = $this->service->create($request->validated());
        return new StudentPaymentResource($payment);
    }

    public function index(Request $request)
    {
        $studentId = $request->user()->id;
        $payments = $this->service->listForStudent($studentId);
        return StudentPaymentResource::collection($payments);
    }
}
