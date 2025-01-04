<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory;

    protected $primaryKey = 'nim';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [''];

    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'id_student_class', 'id_student_class');
    }

    public function guardianLecturer()
    {
        return $this->belongsTo(Lecturer::class, 'guardian_lecturer', 'lecturer_code');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

}
