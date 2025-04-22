<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    public function session()
    {
        return $this->belongsTo(\App\Models\ClassSession::class, 'session_id');
    }
}
