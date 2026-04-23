<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceRequest;

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
        $request = AttendanceRequest::find($id);

        $displayBreaks = $request->attendance->breaks;

        return view('admin.attendance.approve', compact('request', 'displayBreaks'));
    }

    // 更新用
    public function updateApprove(Request $request, $id)
    {
        $attendanceRequest = AttendanceRequest::find($id);

        $attendanceRequest->request_clock_in = $request->request_clock_in;
        $attendanceRequest->request_clock_out = $request->request_clock_out;

        $attendanceRequest->status = 1;

        $attendanceRequest->save();


        return redirect('/stamp_correction_request/approve/' . $id);
    }
}

