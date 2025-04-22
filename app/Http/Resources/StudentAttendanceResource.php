<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

class StudentAttendanceResource extends JsonResource
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
            'session_id' => $this->session_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
