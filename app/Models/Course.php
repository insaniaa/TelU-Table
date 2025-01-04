<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;

    protected $primaryKey = 'course_code';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [''];

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'course_code');
    }

    public function tasks()
{
    return $this->hasMany(Task::class, 'course_code', 'course_code');
}
}
