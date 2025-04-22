<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseMaterial extends Model
{
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
