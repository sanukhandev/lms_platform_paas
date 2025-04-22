<?php

namespace App\Repositories;

use App\Models\PaymentPlan;

class PaymentPlanRepository
{
    public function all()
    {
        return PaymentPlan::all();
    }

    public function store(array $data)
    {
        return PaymentPlan::create($data);
    }

    public function update(PaymentPlan $plan, array $data)
    {
        $plan->update($data);
        return $plan;
    }

    public function delete(PaymentPlan $plan)
    {
        return $plan->delete();
    }
}
