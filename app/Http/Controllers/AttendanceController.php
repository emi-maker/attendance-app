<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function start()
    {
    Attendance::create([
        'user_id' => auth()->id(),
        'work_date' => now()->toDateString(),
        'clock_in' => now(),
    ]);

    return back();
    }
}