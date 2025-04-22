<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'course_id' => $this->course_id,
            'payment_plan_id' => $this->payment_plan_id,
            'amount_paid' => $this->amount_paid,
            'mode' => $this->mode,
            'status' => $this->status,
            'paid_on' => $this->paid_on,
            'created_at' => $this->created_at,
        ];
    }
}
