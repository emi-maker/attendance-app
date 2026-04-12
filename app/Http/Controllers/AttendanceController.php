<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\BreakTime;
use App\Models\AttendanceRequest;
use App\Models\BreakRequest;

class AttendanceController extends Controller
{
    public function index()
    {
        if (auth()->user()->is_admin) {
        return redirect('/admin/attendance/list');
    }
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

    public function adminlist(Request $request)
    {
        
        $date = $request->input('date', now()->toDateString());
        
        $attendances = Attendance::with('user')
        ->whereDate('work_date', $date)
        ->get();

        foreach ($attendances as $attendance) {
            $this->calculateWorkTime($attendance);
        }

            return view('admin.attendance.list', compact('attendances', 'date'));
    }
    

    public function userlist(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));

        //日付作る
        $start = \Carbon\Carbon::parse($month)->startOfMonth();
        $end = \Carbon\Carbon::parse($month)->endOfMonth();

        $dates = [];

        while ($start <= $end) {
            $dates[] = $start->copy();
            $start->addDay();
    }
        
        //前月・翌月
        $prevMonth = \Carbon\Carbon::parse($month)->subMonth()->format('Y-m');
        $nextMonth = \Carbon\Carbon::parse($month)->addMonth()->format('Y-m');

        $attendances = Attendance::with('breaks')
        ->where('user_id', auth()->id())
        ->whereYear('work_date', substr($month, 0, 4))
        ->whereMonth('work_date', substr($month, 5, 2))
        ->orderBy('work_date', 'asc')
        ->get();
        
        foreach ($attendances as $attendance) { 
            
            // 勤務時間（分）
            $workMinutes = 0;

            if ($attendance->clock_in &&            $attendance->clock_out) {
            $start = \Carbon\Carbon::parse($attendance->clock_in);
            $end = \Carbon\Carbon::parse($attendance->clock_out);

            $workMinutes = $end->diffInMinutes($start);
        }

            // 休憩時間（分）
            $breakMinutes = 0;

            foreach ($attendance->breaks as $break) {
                $bStart = \Carbon\Carbon::parse($break->break_start);
                $bEnd = \Carbon\Carbon::parse($break->break_end);
                $breakMinutes += $bEnd->diffInMinutes($bStart);
        }
            
            // 合計
            $attendance->total_break = $breakMinutes;
            $attendance->total_work = $workMinutes - $breakMinutes;
            
            // 休憩フォーマット
            $attendance->break_formatted =
            floor($attendance->total_break / 60) . ':' .
            str_pad($attendance->total_break % 60, 2, '0', STR_PAD_LEFT);

            // 勤務フォーマット
            $attendance->work_formatted =
            floor($attendance->total_work / 60) . ':' .
            str_pad($attendance->total_work % 60, 2, '0', STR_PAD_LEFT);

        }

        return view('attendance.list', compact('attendances', 'month','dates'));
    }   

    private function calculateWorkTime($attendance)
    {
    $totalBreak = 0;

    foreach ($attendance->breakTimes ?? []as $break) {
        if ($break->break_end) {
            $start = strtotime($break->break_start);
            $end = strtotime($break->break_end);
            $totalBreak += ($end - $start);
        }
    }

    $attendance->total_break = $totalBreak;

    if ($attendance->clock_in && $attendance->clock_out) {
        $workStart = strtotime($attendance->clock_in);
        $workEnd = strtotime($attendance->clock_out);

        $workSeconds = $workEnd - $workStart;
        $attendance->total_work = $workSeconds - $attendance->total_break;
    } else {
        $attendance->total_work = 0;
    }

}

    //詳細（データ取ってくる）
    public function show($date)
    {   
        $attendance = Attendance::with('breaks')
        ->firstOrCreate(
            [
                'user_id' => auth()->id(),
                'work_date' => $date,
            ]
        );

       $request = AttendanceRequest::with('breakRequests')
        ->where('attendance_id', $attendance->id)
        ->latest()
        ->first();


        return view('attendance.detail', compact('attendance', 'request','date'));    
    }

    public function update(Request $request, $date)
    {
        $attendance = Attendance::where('work_date', $date)
        ->where('user_id', auth()->id())
        ->first();

        $attendanceRequest = AttendanceRequest::create([
            'user_id' => auth()->id(),
            'attendance_id' => optional($attendance)->id,
            'request_clock_in' => $request->request_clock_in,
            'request_clock_out' => $request->request_clock_out,
            'note' => $request->note,
            'request_status' => 1,
    ]);

        foreach ($request->breaks as $break) {
            BreakRequest::create([
                'user_id' => auth()->id(),
                'attendance_id' => optional($attendance)->id,
                'attendance_request_id' => $attendanceRequest->id,
                'break_start' => $break['break_start'],
                'break_end' => $break['break_end'],
    ]);
}

        BreakRequest::create([
            'user_id' => auth()->id(),
            'attendance_id' => optional($attendance)->id,
            'attendance_request_id' => $attendanceRequest->id,
            'break_start' => $request->break_start[0] ?? null,
            'break_end' => $request->break_end[0] ?? null,
        'status' => 0,
    ]);

        return redirect('/attendance/detail/' . $date)
    ->with('message', '※修正申請を送信しました');
    }


    public function store(Request $request)
    {
        Attendance::create([
            'user_id' => auth()->id(),
            'work_date' => $request->work_date,
            'clock_in' => $request->request_clock_in,
            'clock_out' => $request->request_clock_out,
        ]);

        return redirect('/attendance/detail/'. $request->work_date);
    }
}
