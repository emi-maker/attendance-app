@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
@endsection

@section('content')
<div class="container">

    <div class="content-wrapper">
        <div class="title-area">
            <div class="line"></div>
        <h1 class="detail-title">勤怠一覧</h1>
    </div>
</div>

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
                <th>合計</th>
                <th>詳細</th>
            </tr>

            @foreach ($dates as $date)
            @php
            $attendance = $attendances->firstWhere('work_date', $date->format('Y-m-d'));
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
                    @if ($attendance)
                    <a href="/attendance/detail/{{ $attendance->id }}">詳細</a>
                    @endif
                </td>
            </tr>
            @endforeach

        </table>
    </div>

</div>

@endsection