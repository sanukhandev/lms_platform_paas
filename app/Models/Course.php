<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title',
        'description',
        'instructor_id',
        'duration_weeks',
        'syllabus',
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    protected $casts = [
        'syllabus' => 'array', // because syllabus is an array (JSON)
    ];
}
