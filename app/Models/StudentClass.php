<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model
{
    /** @use HasFactory<\Database\Factories\StudentClassFactory> */
    use HasFactory, HasUuids;

    protected $primaryKey = 'id_student_class';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [''];

    public function students()
    {
        return $this->hasMany(Student::class, 'id_student_class', 'id_student_class');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'id_student_class');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'id_student_class', 'id_student_class');
    }
}
