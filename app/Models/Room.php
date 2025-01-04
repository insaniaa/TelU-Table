<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    /** @use HasFactory<\Database\Factories\RoomFactory> */
    use HasFactory, HasUuids;

    protected $primaryKey = 'id_room';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [''];
}
