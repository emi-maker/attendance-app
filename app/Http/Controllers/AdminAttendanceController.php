<?php

namespace App\Http\Controllers;
use App\Models\Attendance;
use App\Models\AttendanceRequest;

use Illuminate\Http\Request;

class AdminAttendanceController extends Controller
{
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

        return view('admin.attendance.detail', compact(
            'attendance',
            'attendanceRequest',
            'clockIn',
            'clockOut',
            'displayBreaks',
            'date',
            'mode'
        ));
    }
}
