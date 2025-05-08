<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */


    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'duration_weeks' => $this->duration_weeks,
            'syllabus' => $this->syllabus,
            'instructor' => $this->whenLoaded('instructor'),
            'category' => $this->whenLoaded('category'),
            'category_id' => $this->category_id,
        ];
    }
}
