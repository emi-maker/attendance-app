<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRequest extends Model
{
    use HasFactory;

   protected $fillable = [
        'attendance_id',
        'user_id',
        'request_clock_in',
        'request_clock_out',
        'note',
        'request_status',
    ];
}
