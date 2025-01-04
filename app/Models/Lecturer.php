<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    /** @use HasFactory<\Database\Factories\LecturerFactory> */
    use HasFactory;

    protected $primaryKey = 'nip';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [''];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'id_lecturer');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'nip', 'nip');
    }
}
