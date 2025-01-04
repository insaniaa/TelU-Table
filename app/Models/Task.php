<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory, HasUuids;

    protected $primaryKey = 'id_task';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [''];

     /**
     * Define the relationship with the Course model.
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_code', 'course_code');
    }

    /**
     * Define the relationship with the StudentClass model.
     */
    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'id_student_class', 'id_student_class');
    }

    /**
     * Define the relationship with the Lecturer model.
     */
    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class, 'nip', 'nip');
    }
}
