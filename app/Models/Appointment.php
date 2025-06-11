<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'user_id',
        'appointment_datetime',
        'client_name',
        'egn',
        'description',
        'notification_method',
    ];

    protected $casts = [
        'appointment_datetime' => 'datetime',
    ];
}
