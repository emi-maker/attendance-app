@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')

<div class="attendance-wrapper">

    @if (is_null($todayAttendance))

        <div class="attendance-status">勤務外</div>
        <div class="attendance-date" id="today"></div>
        <div class="attendance-time" id="clock"></div>
        <form action="/attendance/start" method="POST">
        @csrf
        <button class="attendance-btn">出勤</button>
    </form>

    @elseif ($todayAttendance->status == 1)

        <div class="attendance-status">勤務中</div>
        <div class="attendance-date" id="today"></div>
        <div class="attendance-time" id="clock"></div>
        <div class="attendance-buttons">
            <form action="/attendance/end" method="POST">
            @csrf
            <button class="attendance-btn">退勤</button>
        </form>

        <form action="/break/start" method="POST">
            @csrf
            <button class="attendance-btn break-btn">休憩入</button>
        </form>
        </div>

    @elseif ($todayAttendance->status == 2)

        <div class="attendance-status">退勤済</div>
        <div class="attendance-date" id="today"></div>
        <div class="attendance-time" id="clock"></div>
        <div class="finish-message">
        お疲れ様でした。
        </div>
    @endif

    </div>
    <script>
        function updateDate() {
        const now = new Date();
    
        const y = now.getFullYear();
        const m = now.getMonth() + 1;
        const d = now.getDate();
    
        const week = ['日','月','火','水','木','金','土'];
        const w = week[now.getDay()];
    
        document.getElementById('today').textContent =
            y + '年' + m + '月' + d + '日(' + w + ')';
    }
    
    updateDate();
    setInterval(updateDate, 60000);
    </script>
    <script>
        function updateClock() {
        const now = new Date();
    
        const h = String(now.getHours()).padStart(2,'0');
        const m = String(now.getMinutes()).padStart(2,'0');
    
        document.getElementById('clock').textContent = h + ':' + m;
    }
    
    updateClock();
    setInterval(updateClock, 60000);
    </script>
@endsection


    