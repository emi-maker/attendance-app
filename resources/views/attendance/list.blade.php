@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
@endsection

@section('content')


<div class="container">

    <h1 class="attendance-title">
        {{ \Carbon\Carbon::parse($month)->format('Y年n月j日') }}勤怠一覧</h1>
    </h1>
    <div class="date-card">
        <div class="date-nav">
            <a href="?month={{\Carbon\Carbon::parse($month)->subMonth()->format('Y-m') }}">← 前月</a>

            <div class="date-center">
                {{ \Carbon\Carbon::parse($month)->format('Y/m') }}
            </div>
            <a href="?month={{ \Carbon\Carbon::parse($month)->addMonth()->format('Y-m') }}">翌月 →</a>
        </div>
    </div>

    <div class="card">
        <table class="attendance-table">

            <tr>
                <th>日付</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>勤務時間</th>
            </tr>

           @foreach ($attendances as $attendance)
        <tr>
            <td>{{ \Carbon\Carbon::parse($attendance->work_date)->format('m/d') }}</td>
            <td>{{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '' }}</td>
            <td>{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '' }}</td>
            <td>{{ $attendance->break_time ?? '' }}</td>
            <td>{{ $attendance->work_time ?? '' }}</td>
        </tr>
        @endforeach

        </table>
    </div>

</div>

@endsection