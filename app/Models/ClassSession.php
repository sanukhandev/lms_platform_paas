<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSession extends Model
{
    protected $fillable = [
        'course_id',
        'class_date',
        'start_time',
        'end_time',
        'location',
        'instructor_id',
        'meeting_link',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
