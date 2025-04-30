<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchStudents extends Model
{
    protected $table = 'batch_students';

    protected $fillable = [
        'batch_id',
        'student_id',
        'status',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
