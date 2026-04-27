<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceRequest;
use App\Models\User;

use Illuminate\Http\Request;

class AdminAttendanceController extends Controller
{
    public function index()
    {
    $attendances = Attendance::with('user')->get();

    return view('admin.attendance.list', compact('attendances'));
    }

    public function show($id)
    {
        $attendance = Attendance::with('breaks', 'user')
            ->findOrFail($id);

        $attendanceRequest = AttendanceRequest::with('breakRequests')
            ->where('attendance_id', $attendance->id)
            ->latest()
            ->first();

        $displayBreaks = [];

        if ($attendanceRequest && $attendanceRequest->breakRequests && $attendanceRequest->breakRequests->count()) {
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

        $clockOut = $attendanceRequest && $attendanceRequest->request_clock_out
            ? $attendanceRequest->request_clock_out
            : $attendance->clock_out;

        $clockIn = $attendanceRequest && $attendanceRequest->request_clock_in
            ? $attendanceRequest->request_clock_in
            : $attendance->clock_in;

        $date = $attendance->work_date;

        $mode = 'edit';

        $note = $attendanceRequest ? $attendanceRequest->note : $attendance->note;
        
        return view('admin.attendance.detail', compact(
            'attendance',
            'attendanceRequest',
            'clockIn',
            'clockOut',
            'displayBreaks',
            'date',
            'mode',
            'note'
        ));
    }

    public function approve($id)
    {   
        $attendanceRequest = AttendanceRequest::findOrFail($id);

        $attendanceRequest->update([
            'status' => 1,
    ]);

        return redirect('/admin/attendance/' . $attendanceRequest->attendance_id);
    }

    public function staffAttendance($id)
    {
        $user = User::findOrFail($id);
        $month = request('month', now()->format('Y-m'));

        $startOfMonth = \Carbon\Carbon::parse($month)->startOfMonth();
        $endOfMonth = \Carbon\Carbon::parse($month)->endOfMonth();

        $dates = collect();

        for ($date = $startOfMonth->copy(); $date <= $endOfMonth; $date->addDay()) {
        $dates->push($date->copy());
        }

        $attendances = Attendance::where('user_id', $id)
        ->whereBetween('work_date', [$startOfMonth, $endOfMonth])
        ->get();

        return view('admin.attendance.staff', compact('user', 'month','dates' , 'attendances'));
    }

    public function detail($userId, $date)
    {
        $user = User::findOrFail($userId);

        $attendance = Attendance::where('user_id', $userId)
        ->where('work_date', $date)
        ->first();

        return view('admin.attendance.detail', compact('user', 'attendance', 'date'));
    }
}

