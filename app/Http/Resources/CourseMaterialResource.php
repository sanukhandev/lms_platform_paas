<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseMaterialResource extends JsonResource
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
            'title' => $this->title,
            'mime_type' => $this->mime_type,
            'size' => $this->size,
            'download_url' => route('materials.download', $this->id),
            'uploaded_by' => $this->uploaded_by,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
