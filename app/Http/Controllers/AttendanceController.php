<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function index()
    {
        $todayAttendance = Attendance::where('user_id', auth()->id())
        ->whereDate('work_date', now()->toDateString())
        ->first();

    return view('attendance.index', compact('todayAttendance'));
    }

    public function start()
    {
        $todayAttendance = Attendance::where('user_id', auth()->id())
        ->whereDate('work_date', now())
        ->first();

        if ($todayAttendance) {
            return redirect('/attendance');
        }

        Attendance::create([
        'user_id' => auth()->id(),
        'work_date' => now()->toDateString(),
        'clock_in' => now(),
    ]);

        return back();
    }

    public function end()
    {
    
        $todayAttendance = Attendance::where('user_id', auth()->id())
        ->whereDate('work_date', now()->toDateString())
        ->first();

        if (!$todayAttendance) {
        redirect('/attendance');
        }

        $todayAttendance->update([
        'clock_out' => now(),
    ]);

    return redirect('/attendance');
    }
}