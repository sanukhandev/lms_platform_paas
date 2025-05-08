<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseCategory extends Model
{
    // cresate course category table with name and desceiption also tree based parent anc child 

    protected $fillable = [
        'name',
        'description',
        'parent_id',
    ];

    public function parent()
    {
        return $this->belongsTo(CourseCategory::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(CourseCategory::class, 'parent_id');
    }
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
