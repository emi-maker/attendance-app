@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
@endsection

@section('content')


<div class="container">

    <h1 class="attendance-title">
        {{ \Carbon\Carbon::parse($date)->format('Y年n月j日') }}勤怠一覧</h1>
    </h1>
    <div class="date-card">
        <div class="date-nav">
            <a href="?date={{\Carbon\Carbon::parse($date)->subDay()->toDateString() }}">← 前月</a>

            <div class="date-center">
                2023/06
            </div>
            <a href="?date={{ \Carbon\Carbon::parse($date)->addDay()->toDateString() }}">翌月 →</a>
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

            <!-- 仮データ -->
            <tr>
                <td>06/01</td>
                <td>09:00</td>
                <td>18:00</td>
                <td>01:00</td>
                <td>08:00</td>
            </tr>

        </table>
    </div>

</div>

@endsection