@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')
<div class="detail-container">

    <h1 class="detail-title">勤怠詳細</h1>
    <div class="content-wrapper">
        <div class="detail-card">
            <form id="attendance-form" action="/attendance/update/{{ $attendance->id }}" method="POST">
                @csrf
            </form>    

                <table class="detail-table">
                    <tr>
                        <th>名前</th>
                        <td>{{ $attendance->user->name }}</td>
                    </tr>
                    <tr>
                        <th>日付</th>
                        <td>
                            <div class="date-box">
                                <span class="year">
                                    {{ \Carbon\Carbon::parse($attendance->work_date)->format('Y年') }}
                                </span>
                                <span class="date">
                                    {{ \Carbon\Carbon::parse($attendance->work_date)->format('n月j日') }}
                                </span>
                            </div>

                    </tr>
                    <tr>
                        <th>出勤・退勤</th>
                        <td>
                            <div class="break-row">
                                <input type="time" name="request_clock_in"
                                    value="{{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}">
                                <span class="tilde">〜</span>

                                <input type="time" name="request_clock_out"
                                    value="{{ \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') }}">
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th>休憩</th>
                        <td>
                            <div class="break-container">
                                <div class="break-row">
                                    <input type="time" name="break_start[]">
                                    <span class="tilde">〜</span>
                                    <input type="time" name="break_end[]">
                                </div>

                                <div class="break-row">
                                    <input type="time" name="break_start[]">
                                    <span class="tilde">〜</span>
                                    <input type="time" name="break_end[]">
                                </div>
                        </td>
                    </tr>

                    <tr>
                        <th>備考</th>
                        <td>
                            <textarea name="remark" rows="3">
                            {{ $attendance->remark ?? '' }}
                        </textarea>
                        </td>
                    </tr>
                </table>
        </div>

                <div class="button-area">
                    <button class="submit-btn">修正</button>
                </div>
</div>
@endsection