<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentPlanRequest;
use App\Http\Resources\PaymentPlanResource;
use App\Models\PaymentPlan;
use App\Services\PaymentPlanService;

class PaymentPlanController extends Controller
{
    public function __construct(protected PaymentPlanService $service) {}

    public function index()
    {
        return PaymentPlanResource::collection($this->service->list());
    }

    public function store(StorePaymentPlanRequest $request)
    {
        $plan = $this->service->create($request->validated());
        return new PaymentPlanResource($plan);
    }

    public function update(StorePaymentPlanRequest $request, PaymentPlan $paymentPlan)
    {
        $updated = $this->service->update($paymentPlan, $request->validated());
        return new PaymentPlanResource($updated);
    }

    public function destroy(PaymentPlan $paymentPlan)
    {
        $this->service->delete($paymentPlan);
        return response()->json(['message' => 'Deleted']);
    }
}
