<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseRequest extends Model
{
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
