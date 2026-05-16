<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\BreakTime;
use App\Models\AttendanceRequest;
use App\Models\BreakRequest;
use App\Http\Requests\AttendanceCorrectionRequest;
use App\Http\Requests\AdminAttendanceRequest;


class AttendanceController extends Controller
{
    public function index()
    {
        $todayAttendance = $this->getTodayAttendance();

        return view('attendance.index', compact('todayAttendance'));
    }

    public function start()
    {
       $todayAttendance = $this->getTodayAttendance();

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
        $attendance = $this->getTodayAttendance();

        if (!$attendance) {
            return redirect('/attendance');
    }
        
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
        $attendance = $this->getTodayAttendance();

        if (!$attendance) {
            return redirect('/attendance');
    }

        $break = BreakTime::where('attendance_id', $attendance->id)
        ->whereNull('break_end')
        ->latest()
        ->first();

        if ($break) {
        $break->break_end = now();
        $break->save();
        }

        $attendance->status = 1;
        $attendance->save();

        return redirect('/attendance');
    }
        

    public function end()
    {
    
        $todayAttendance = $this->getTodayAttendance();

        if (!$todayAttendance) {
            return redirect('/attendance');
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
        
        $attendances = Attendance::with(['user', 'breaks'])
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
        
        $pendingRequests = AttendanceRequest::where('user_id', auth()->id())
        ->where('status', 0)
        ->get();

        return view('attendance.list', compact('attendances', 'month','dates','pendingRequests',
        'prevMonth',
        'nextMonth'));
    }   

    private function calculateWorkTime(Attendance $attendance)
    {
    $totalBreak = 0;

    foreach ($attendance->breaks ?? [] as $break) {
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

    public function detailByDate(string $date)
    {
    $attendance = Attendance::firstOrCreate(
        [
            'user_id' => auth()->id(),
            'work_date' => $date,
        ]
    );

    return redirect('/attendance/detail/' . $attendance->id);
}

    //詳細（データ取ってくる）
    public function show(int $id)
    {   
       $attendance = Attendance::with('breaks')
          ->findOrFail($id);

        $attendanceRequest = AttendanceRequest::with('breakRequests')
            ->where('attendance_id', $attendance->id)
            ->orderBy('id', 'desc')
            ->first();
 

        $displayBreaks = [];

        if (
            $attendanceRequest &&
            $attendanceRequest->status == 0 && $attendanceRequest->breakRequests && $attendanceRequest->breakRequests->count()
            ) {

            foreach ($attendanceRequest->breakRequests as $break) {

                $displayBreaks[] = [
            'break_start' => $break->break_start,
            'break_end' => $break->break_end,
            ];
        }

    } else {

        foreach ($attendance->breaks as $break) {

           $displayBreaks[] = [
                'break_start' => $break->break_start,
                'break_end' => $break->break_end,
            ];
        }
    }

        if ($attendanceRequest && $attendanceRequest->request_clock_in) {
            $clockIn = $attendanceRequest->request_clock_in;
        } else {
        $clockIn = $attendance->clock_in;
    }

        if ($attendanceRequest && $attendanceRequest->request_clock_out) {
            $clockOut = $attendanceRequest->request_clock_out;
        } else {
            $clockOut = $attendance->clock_out;
        }

        $note = $attendanceRequest
        ? $attendanceRequest->note
        : $attendance->note;  

        $date = $attendance->work_date;


        return view('attendance.detail', compact(
            'attendance',
            'attendanceRequest',
            'clockIn',
            'clockOut',
            'displayBreaks',
            'date',
            'note'
        ));
    } 


    public function update(AttendanceCorrectionRequest $request, int $id)
    {
        $attendance = Attendance::where('user_id', auth()->id())
        ->where('id', $id)
        ->first();

        if (!$attendance) {
        return redirect('/attendance');
        
    }

        $attendanceRequest = AttendanceRequest::firstOrNew([
        'attendance_id' => $attendance->id,
        'user_id' => auth()->id(),
    ]);

    $attendanceRequest->request_clock_in = $request->request_clock_in;
    $attendanceRequest->request_clock_out = $request->request_clock_out;
    $attendanceRequest->note = $request->note;
    $attendanceRequest->status = 0;

    $attendanceRequest->save();

        BreakRequest::where('attendance_request_id', $attendanceRequest->id)->delete();


        foreach ($request->breaks as $break) {

        if (!empty($break['break_start']) && !empty($break['break_end'])) {

            BreakRequest::create([
                'user_id' => auth()->id(),
                'attendance_id' => optional($attendance)->id,
                'attendance_request_id' => $attendanceRequest->id,
                'break_start' => $break['break_start'],
                'break_end' => $break['break_end'],
            ]);
        }
    }

        return redirect('/attendance/detail/date/' . $attendance->work_date);
    }

    public function adminUpdate(AdminAttendanceRequest $request, int $id)
    {      
    $attendance = Attendance::find($id);

    // 出勤・退勤更新
    $attendance->clock_in = $request->request_clock_in;
    $attendance->clock_out = $request->request_clock_out;
    $attendance->note = $request->note;
    $attendance->save();

    // 休憩一旦削除
    $attendance->breaks()->delete();
    
    // 休憩再登録
    foreach ($request->breaks as $break) {

        if (
            empty($break['break_start']) ||
            empty($break['break_end'])
        ) {
            continue;
        }

        BreakTime::create([
            'attendance_id' => $attendance->id,
            'break_start' => $attendance->work_date . ' ' . $break['break_start'],
            'break_end' => $attendance->work_date . ' ' . $break['break_end'],
        ]);
    }

    return redirect('/admin/attendance/staff/'  . $attendance->user_id);
}

    public function store(Request $request)
    {
        Attendance::create([
            'user_id' => auth()->id(),
            'work_date' => $request->work_date,
            'clock_in' => $request->request_clock_in,
            'clock_out' => null,
        ]);

        return redirect('/attendance/detail/'. $request->id);
    }

    private function getTodayAttendance()
    {
        return Attendance::where('user_id', auth()->id())
            ->whereDate('work_date', now()->toDateString())
            ->first();
    }

    public function exportCsv(Request $request, int $id)
    {
    
        $month = $request->query('month');
    
        [$year, $monthNumber] = explode('-', $month);

        $attendances = Attendance::where('user_id', $id)
        ->whereYear('work_date', $year)
        ->whereMonth('work_date', $monthNumber)
        ->orderBy('work_date', 'asc')
        ->get();

        return response()->streamDownload(function () use ($attendances) {

        $handle = fopen('php://output', 'w');

        stream_filter_prepend($handle, 'convert.iconv.UTF-8/CP932');

        // ヘッダー行
        fputcsv($handle, [
            '日付',
            '出勤',
            '退勤',
            '休憩',
            '備考',
        ]);

        // データ行
        foreach ($attendances as $attendance) {

            $totalBreakMinutes = 0;

            foreach ($attendance->breaks as $break) {

            $breakStart = \Carbon\Carbon::parse($break->break_start);
            $breakEnd = \Carbon\Carbon::parse($break->break_end);

            $totalBreakMinutes += $breakEnd->diffInMinutes($breakStart);
        }

        $breakHours = floor($totalBreakMinutes / 60);
        $breakMinutes = $totalBreakMinutes % 60;

        $breakTime = sprintf('%02d:%02d', $breakHours, $breakMinutes);


        fputcsv($handle, [
            $attendance->work_date,
            $attendance->clock_in
            ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i')
            : '',

            $attendance->clock_out
            ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i')
            : '',
            $breakTime,
            $attendance->note,
            ]);
        }

        fclose($handle);

    }, 'attendance.csv');

    }
}

