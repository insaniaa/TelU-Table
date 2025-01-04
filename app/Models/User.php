<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasUuids, HasApiTokens;

    protected $primaryKey = 'id_user';
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function redirectTo()
    {
        if ($this->hasRole('Super Admin')) {
            return route('admin.master_data.schedule.index');
        } elseif ($this->hasRole('Lecturer')) {
            return route('lecturer.tasks.tasks_today');
        } elseif ($this->hasRole('Student')) {
            return route('user.tasks_today');
        }
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function lecturer()
    {
        return $this->hasOne(Lecturer::class, 'id_user', 'id_user');
    }


    public function student()
    {
        return $this->hasOne(Student::class, 'id_user', 'id_user');
    }

}
