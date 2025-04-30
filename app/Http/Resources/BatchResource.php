<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ClassSessionResource;
use App\Http\Resources\UserResource;

class BatchResource extends JsonResource
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
            'course_id' => $this->course_id,
            'name' => $this->name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'session_days' => $this->session_days,
            'session_start_time' => $this->session_start_time,
            'session_end_time' => $this->session_end_time,
            'students' => $this->students->map(function ($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'email' => $student->email,
                    'status' => $student->pivot->status, // Get status from pivot table
                    'created_at' => $student->pivot->created_at,
                ];
            }),
            'class_sessions' => ClassSessionResource::collection($this->whenLoaded('classSessions')),
        ];
    }
}
