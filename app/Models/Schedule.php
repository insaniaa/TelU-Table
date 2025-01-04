<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    /** @use HasFactory<\Database\Factories\ScheduleFactory> */
    use HasFactory, HasUuids;

    protected $primaryKey = 'id_schedule';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [''];

    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'id_student_class');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_code');
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class, 'nip');
    }
}
