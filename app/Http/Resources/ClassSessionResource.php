<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ClassSessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'start_time' => $this->start_time,
            'class_date' => $this->class_date,
            'end_time' => $this->end_time,
            'meeting_link' => $this->meeting_link,
            'course' => new CourseResource($this->whenLoaded('course')),
            'instructor' => new InstructorResource($this->whenLoaded('instructor')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
