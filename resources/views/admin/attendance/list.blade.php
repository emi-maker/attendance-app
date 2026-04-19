@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">

<link rel="stylesheet" href="{{ asset('css/common.css') }}">
@endsection

@section('content')

<div class="container">

    <h1 class="attendance-title">
        {{ \Carbon\Carbon::parse($date)->format('Y年n月j日') }}勤怠の一覧</h1>
    <div class="date-card">
        <div class="date-nav">
            <a href="?date={{\Carbon\Carbon::parse($date)->subDay()->toDateString() }}">← 前日</a>

            {{ \Carbon\Carbon::parse($date)->format('Y/m/d') }}

            <a href="?date={{ \Carbon\Carbon::parse($date)->addDay()->toDateString() }}">翌日 →</a>
        </div>
    </div>


    <div class="date-card">
        <table class="attendance-table">

        <tr>
            <th>名前</th>
            <th>日付</th>
            <th>出勤</th>
            <th>退勤</th>
            <th>休憩</th>
            <th>勤務時間</th>
            <th>詳細</th>
        </tr>

        @foreach ($attendances ?? [] as $attendance)

        <tr>
            <td>{{ $attendance->user->name }}</td>
            <td>{{ $attendance->work_date }}</td>
            <td>
                {{ $attendance->clock_in
                ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i')
                : ''
                }}
            </td>
            <td>
                {{ $attendance->clock_out
                ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i')
                : ''
                }}
            </td>
           <td>
            {{ floor($attendance->total_break / 3600) }}:
            {{ str_pad(floor(($attendance->total_break % 3600) / 60), 2, '0', STR_PAD_LEFT) }}
        </td>
        <td>
            {{ floor($attendance->total_work / 3600) }}:
            {{ str_pad(floor(($attendance->total_work % 3600) / 60), 2, '0', STR_PAD_LEFT) }}
        </td>
            <td>
                <a href="/admin/attendance/{{ $attendance->id }}">詳細
                </a>
            </td>
        </tr>

        @endforeach

    </table>

    @endsection