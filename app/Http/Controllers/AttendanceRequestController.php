<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceRequest;
use App\Models\BreakTime;

class AttendanceRequestController extends Controller
{

    public function index()
    {  
      if (session('role') === 'admin') {
        return $this->adminIndex();  
    }

    $pendingRequests = AttendanceRequest::with('user', 'attendance')
        ->where('user_id', auth()->id())
        ->where('status', 0)
        ->get();

    $approvedRequests = AttendanceRequest::with('user', 'attendance')
        ->where('user_id', auth()->id())
        ->where('status', 1)
        ->get();
    
    return view('requests.index', compact('pendingRequests', 'approvedRequests'));
}

    public function adminIndex()
    {
        $pendingRequests = AttendanceRequest::with('user', 'attendance')
        ->where('status', 0)
        ->get();

        $approvedRequests = AttendanceRequest::with('user', 'attendance')
        ->where('status', 1)
        ->get();

        return view('admin.requests.index', compact('pendingRequests', 'approvedRequests'));
    }

    //表示用
    public function approve($id)
    {
        $attendanceRequest = AttendanceRequest::find($id);
        
        $attendanceRequest = AttendanceRequest::with('attendance.breaks')->find($id);

        $displayBreaks = $attendanceRequest->attendance->breaks;

        return view('admin.attendance.approve', compact('attendanceRequest', 'displayBreaks'));
    }

    // 更新用
    public function updateApprove(Request $request, $id)
    {
        $attendanceRequest = AttendanceRequest::find($id);

        // ① 申請を承認状態に
        $attendanceRequest->status = 1;
        $attendanceRequest->save();

        // ② 実データ取得
        $attendance = $attendanceRequest->attendance;

        // ③ 出勤・退勤 更新（←画面の値使う）
        $attendance->clock_in = $request->request_clock_in;
        $attendance->clock_out = $request->request_clock_out;
        $attendance->save();

        // ④ 休憩を一旦削除
        $attendance->breaks()->forcedelete();

        // ⑤ 休憩を再登録
        foreach ($request->breaks as $break) {

            if (empty($break['break_start']) || empty($break['break_end'])) {
            continue;
        }

        $attendanceRequest = AttendanceRequest::with('attendance.breaks')->find($id);

        $date = $attendance->work_date;

        $start = $date . ' ' . $break['break_start'];
        $end = $date . ' ' . $break['break_end'];

        BreakTime::create([
            'attendance_id' => $attendance->id,
            'break_start' => $start,
            'break_end' => $end,
    ]);
}


        return redirect('/stamp_correction_request/approve/' . $id);
    }
}

