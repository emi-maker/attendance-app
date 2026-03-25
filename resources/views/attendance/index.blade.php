@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')

<div class="attendance-wrapper">
    {{--今日まだ出勤してないなら --}}
    @if (is_null($todayAttendance))
    <div class="attendance-status">
        勤務外
    </div>
    @else
    <div class="attendance-status">勤務中</div>
    @endif

    <div class="attendance-date" id="today"></div>
    
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
        
        // 最初に表示
        updateDate();
        
        // 1分ごとに更新
        setInterval(updateDate, 60000);
    </script>

    <div class="attendance-time"id="clock">
    </div>
    <script>
        function updateClock() {
        const now = new Date();
    
        const h = String(now.getHours()).padStart(2,'0');
        const m = String(now.getMinutes()).padStart(2,'0');
    
        document.getElementById('clock').textContent = h + ':' + m;
    }
    
    // 最初に1回表示
    updateClock();
    
    // 1分ごとに更新
    setInterval(updateClock, 60000);
    </script>


    @if (is_null($todayAttendance))

    <form action="/attendance/start" method="POST">
        @csrf
        <button class="attendance-btn">出勤</button>
    </form>

    @else
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
    @endif

</div>

@endsection