<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Http\Requests\AttendanceCorrectionRequest;
use App\Models\BreakRequest;
use App\Models\AttendanceRequest;
use App\Models\BreakTime;
use App\Http\Requests\UpdateApproveRequest;

class AttendanceRequestController extends Controller
{
    public function index()
    {
        if (!auth()->check() && !Auth::guard('admin')->check()) {
            return redirect('/login');
    }
        
        if (Auth::guard('admin')->check()) {

            $pendingRequests = AttendanceRequest::with(['user', 'attendance'])
            ->where('status', 0)
            ->get();

            $approvedRequests = AttendanceRequest::with(['user', 'attendance'])
            ->where('status', 1)
            ->get();

            return view('admin.requests.index', compact('pendingRequests', 'approvedRequests'));

        } else {

            $pendingRequests = AttendanceRequest::with(['user', 'attendance'])
            ->where('user_id', auth()->id())
            ->where('status', 0)
            ->get();

            $approvedRequests = AttendanceRequest::with(['user', 'attendance'])
            ->where('user_id', auth()->id())
            ->where('status', 1)
            ->get();

        return view('requests.index', compact('pendingRequests', 'approvedRequests'));
        }      
    }

    //表示用
    public function approve(int $id)
    {
        $attendanceRequest = AttendanceRequest::with('attendance.breaks', 'breakRequests')->find($id);

        $attendance = $attendanceRequest->attendance;

        $clockIn = $attendanceRequest->request_clock_in;

        $clockOut = $attendanceRequest->request_clock_out;

        if ($attendanceRequest->status == 0) {

        // 承認待ち → 申請データ表示
        $breaks = $attendanceRequest->breakRequests ?? [];

        } else {

        // 承認済 → 実データ表示
        $breaks = $attendance->breaks ?? [];
    }


        return view('admin.attendance.approve', compact('attendanceRequest','attendance', 'breaks','clockIn',
        'clockOut'));
    }

    // 更新用
    public function updateApprove(UpdateApproveRequest $request, int $id)
    {
        $attendanceRequest = AttendanceRequest::find($id);
        
        // ① 申請を承認状態に
        $attendanceRequest->status = 1;
        $attendanceRequest->save();


        // ② 実データ取得
        $attendance = $attendanceRequest->attendance;

        // ③ 出勤・退勤 更新（←画面の値使う）
        $attendanceRequest->request_clock_in = $request->request_clock_in;
        $attendanceRequest->request_clock_out = $request->request_clock_out;
        $attendanceRequest->note = $request->note;
        $attendanceRequest->save();

        // attendance 更新
        $attendance->clock_in = $request->request_clock_in;
        $attendance->clock_out = $request->request_clock_out;
        $attendance->note = $request->note;

        $attendance->save();

        // ④ 休憩を一旦削除
        $attendance->breaks()->forcedelete();

        // ⑤ 休憩を再登録
        if ($request->has('breaks')) {
            foreach ($request->breaks as $break) {

            if (empty($break['break_start']) || empty($break['break_end'])) {
                continue;
            }

            $date = $attendance->work_date;

            BreakTime::create([
                'attendance_id' => $attendance->id,
                'break_start' => $date . ' ' . $break['break_start'],
                'break_end' => $date . ' ' . $break['break_end'],
            ]);
        }
    }
        return redirect('/stamp_correction_request/approve/' . $attendanceRequest->id);
        
    }

    public function store(Request $request)
    {
        AttendanceRequest::create([
            'user_id' => auth()->id(),
            'attendance_id' => $request->attendance_id,
            'request_clock_in' => $request->request_clock_in,
            'request_clock_out' => $request->request_clock_out,
            'note' => $request->note,

            'status' => 0,
        ]);

        return redirect('/attendance/detail/'. $request->attendance_id);
    }

    //ユーザーの更新
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

    // 休憩リセット
    BreakRequest::where('attendance_request_id', $attendanceRequest->id)->delete();

    if ($request->has('breaks')) {
        foreach ($request->breaks as $break) {
            if (empty($break['break_start']) || empty($break['break_end'])) {
                continue;
            }

            BreakRequest::create([
                'user_id' => auth()->id(),
                'attendance_id' => $attendance->id,
                'attendance_request_id' => $attendanceRequest->id,
                'break_start' => $break['break_start'],
                'break_end' => $break['break_end'],
            ]);
        }
    }

    return redirect('/attendance/detail/' . $attendance->id);
    }
}