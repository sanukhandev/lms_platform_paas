<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'date',
        'start_time',
        'end_time',
        'meeting_link', // Optional: For online classes
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
