<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentPaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'payment_plan_id' => 'required|exists:payment_plans,id',
            'amount_paid' => 'required|numeric|min:0',
            'mode' => 'required|in:cash,bank,online',
            'status' => 'required|in:paid,pending',
            'paid_on' => 'nullable|date'
        ];
    }
}
