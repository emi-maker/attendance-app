<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'work_date',
    'clock_in',
    'clock_out',
    'status',
    ];


    public function breaks()
        {
        return $this->hasMany(BreakTime::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function request()
    {
        return $this->hasOne(AttendanceRequest::class, 'attendance_id')->latest();
    }

    //休憩
    public function getTotalBreakAttribute()
    {
    $breakMinutes = 0;

    foreach ($this->breaks as $break) {
        if ($break->break_end) {
            $start = \Carbon\Carbon::parse($break->break_start);
            $end = \Carbon\Carbon::parse($break->break_end);
            $breakMinutes += $end->diffInMinutes($start);
        }
    }

    return $breakMinutes;
}
    //勤務
    public function getTotalWorkAttribute()
    {
        if (!$this->clock_in || !$this->clock_out) {
            return 0;
    }

    $start = \Carbon\Carbon::parse($this->clock_in);
    $end = \Carbon\Carbon::parse($this->clock_out);

    return $end->diffInMinutes($start) - $this->total_break;
    }

    //フォーマット
    public function getBreakFormattedAttribute()
    {
        if ($this->total_break === 0) {
        return '';
    }

    return floor($this->total_break / 60) . ':' .
        str_pad($this->total_break % 60, 2, '0', STR_PAD_LEFT);
}

    public function getWorkFormattedAttribute()
    {
            if (!$this->clock_in || !$this->clock_out) {
        return '';
    }

    return floor($this->total_work / 60) . ':' .
        str_pad($this->total_work % 60, 2, '0', STR_PAD_LEFT);
    }

}
