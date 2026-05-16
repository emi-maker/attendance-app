@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endsection

@section('content')

<div class="detail-container">
    <div class="title-area">
        <div class="line"></div>
        <h1 class="detail-title">勤怠一覧</h1>
    </div>

    <div class="date-card attendance-box">
        <div class="date-nav">

            <form method="GET" action="/attendance/list">
                <input type="hidden" name="month" value="{{ $prevMonth }}">
                <button type="submit" class="nav-btn">← 前月</button>
            </form>

            <div class="date-center">
                <div class="date-center">
                    <i class="fa-regular fa-calendar"></i>
                    {{ \Carbon\Carbon::parse($month)->format('Y/m') }}
                </div>
            </div>

            <form method="GET" action="/attendance/list">
                <input type="hidden" name="month" value="{{ $nextMonth }}">
                <button type="submit" class="nav-btn">翌月 →</button>
            </form>

        </div>
    </div>

    <div class="card attendance-box">
        <table class="attendance-table">

            <tr>
                <th>日付</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>


            @foreach ($dates as $date)
            @php
            $attendance = $attendances->first(function ($item) use ($date) {
            return \Carbon\Carbon::parse($item->work_date)->toDateString() === $date->toDateString();
            });
            @endphp

            <tr>
                <td>
                    {{ $date->format('m/d') }}
                    ({{ $date->locale('ja')->isoFormat('ddd') }})
                </td>

                <td>
                    {{ $attendance && $attendance->clock_in ?
                    \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '' }}
                </td>

                <td>
                    {{ $attendance && $attendance->clock_out ?
                    \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '' }}
                </td>
                <td>
                    {{ $attendance ? $attendance->break_formatted : '' }}
                </td>
                <td>
                    {{ $attendance ? $attendance->work_formatted : '' }}
                </td>
                <td>
                    @php
                    $request = $pendingRequests->first(function ($item) use ($attendance) {
                    return $attendance && $item->attendance_id == $attendance->id;
                    });
                    @endphp

                    @if (!empty($attendance->id))
                    <a href="/attendance/detail/{{ $attendance->id }}">詳細</a>
                    @else
                    <a href="/attendance/detail/date/{{ $date->toDateString() }}">詳細</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection