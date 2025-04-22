<?php

namespace App\Services;

use App\Models\PaymentPlan;
use App\Repositories\PaymentPlanRepository;

class PaymentPlanService
{
    public function __construct(protected PaymentPlanRepository $repo) {}

    public function list()
    {
        return $this->repo->all();
    }

    public function create(array $data)
    {
        return $this->repo->store($data);
    }

    public function update(PaymentPlan $plan, array $data)
    {
        return $this->repo->update($plan, $data);
    }

    public function delete(PaymentPlan $plan)
    {
        return $this->repo->delete($plan);
    }
}
