@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')

<h1>勤怠一覧</h1>
    <div class="month-nav">
        ← 2026年3月 →
    </div>
<table>

    <tr>
        <th>日付</th>
        <th>出勤</th>
        <th>退勤</th>
        <th>休憩</th>
        <th>勤務時間</th>
        <th>詳細</th>
    </tr>

    @foreach ($attendances as $attendance)

    <tr>
        <td>{{ $attendance->work_date }}</td>
        <td>
            {{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}
        </td>
        <td>
            {{ \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') }}
        </td>
        <td>   
            {{ floor($attendance->total_work / 3600) }}:
            {{ str_pad(floor(($attendance->total_work % 3600) / 60), 2, '0', STR_PAD_LEFT) }}
        </td>    
        <td>
            {{ floor($attendance->total_break / 3600) }}:
            {{ str_pad(floor(($attendance->total_break % 3600) / 60), 2, '0', STR_PAD_LEFT) }}
        </td>

        <td>
            <a href="/attendance/detail/{{ $attendance->id }}">詳細
            </a>
        </td>
    </tr>

    @endforeach

</table>

@endsection
