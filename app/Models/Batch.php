<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'name',
        'start_date',
        'end_date',
        'session_days',
        'session_start_time',
        'session_end_time'
    ];

    protected $casts = [
        'session_days' => 'array',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function classSessions()
    {
        return $this->hasMany(ClassSession::class);
    }


    public function students()
    {
        return $this->belongsToMany(User::class, 'batch_students', 'batch_id', 'student_id')
            ->withPivot('status')
            ->withTimestamps();
    }
}
