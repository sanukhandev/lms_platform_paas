<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\CourseCategory;

class Course extends Model
{
    protected $fillable = [
        'title',
        'description',
        'instructor_id',
        'duration_weeks',
        'syllabus',
        'category_id',
    ];

    protected $casts = [
        'syllabus' => 'array', // because syllabus is an array (JSON)
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function category()
    {
        return $this->belongsTo(CourseCategory::class, 'category_id');
    }
    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
    public function students()
    {
        return $this->hasMany(User::class, Batch::class);
    }
}
