<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\BreakTime;

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
        'status' => 1,
    ]);

        return back();
    }

    public function breakStart()
    {
        $attendance = Attendance::where('user_id', auth()->id())
        ->whereDate('work_date', now()->toDateString())
        ->first();

        BreakTime::create([
        'attendance_id' => $attendance->id,
        'break_start' => now(),
    ]);

        $attendance->status = 2;
        $attendance->save();

        return redirect('/attendance');
    }


    public function breakEnd()
    {
        $attendance = Attendance::where('user_id', auth()->id())
        ->whereDate('work_date', now()->toDateString())
        ->first();

        $break = BreakTime::where('attendance_id', $attendance->id)
        ->whereNull('break_end')
        ->latest()
        ->first();

        $break->break_end = now();
        $break->save();

        $attendance->status = 1;
        $attendance->save();

        return redirect('/attendance');
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
        'status' => 3,
    ]);

    return redirect('/attendance');
    }

    public function list()
    {
        $attendances = Attendance::where('user_id', auth()->id())
        ->orderBy('work_date', 'desc')
        ->get();

        foreach ($attendances as $attendance) {
        //休憩合計
        $totalBreak = 0;

        foreach ($attendance->breakTimes as $break) {

            if ($break->break_end) {
                $start = strtotime($break->break_start);
                $end = strtotime($break->break_end);

                $totalBreak += ($end - $start);
            }
        }

        $attendance->total_break = $totalBreak;

        //勤務合計
        if ($attendance->clock_in && $attendance->clock_out) {

            $workStart = strtotime($attendance->clock_in);
            $workEnd = strtotime($attendance->clock_out);

            $workSeconds = $workEnd - $workStart;

            $attendance->total_work = $workSeconds - $attendance->total_break;

        } else {
            $attendance->total_work = 0;
        }
    }

    return view('admin.attendance.list', compact('attendances'));
    }

    //詳細（データ取ってくる）
    public function show($id)
    {
        $attendance = Attendance::find($id);
        return view('attendance.detail', compact('attendance'));
    }
    //
    public function update(Request $request, $id)
    {
        $attendance = Attendance::find($id);

        $attendance->clock_in = $request->clock_in;

        $attendance->save();

        return redirect('/attendance/list');
    }
}
