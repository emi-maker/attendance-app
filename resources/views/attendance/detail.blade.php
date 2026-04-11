@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')

<div class="detail-container">
    <div class="title-area">
        <div class="line"></div>
        <h1 class="detail-title">勤怠詳細</h1>
    </div>

    <div class="detail-card attendance-box">

        @if ($attendance)
        <form id="attendance-form" action="/attendance/update/{{ $date }}" method="POST">
            @method('PUT')
            @else
            <form id="attendance-form" action="/attendance/store" method="POST">
                @endif

                @csrf
                <input type="hidden" name="work_date" value="{{ $date }}">

                <table class="detail-table">
                    <tr>
                        <th>名前</th>
                        <td>{{ $attendance ? $attendance->user->name : auth()->user()->name }}</td>
                    </tr>
                    <tr>
                        <th>日付</th>
                        <td>
                            @php
                            $targetDate = $attendance ? $attendance->work_date : $date;
                            @endphp
                            <div class="date-box">
                                <span class="year">
                                    {{ \Carbon\Carbon::parse($targetDate)->format('Y年') }}
                                </span>
                                <span class="date">
                                    {{ \Carbon\Carbon::parse($targetDate)->format('n月j日') }}
                                </span>
                            </div>

                    </tr>
                    <tr>
                        <th>出勤・退勤</th>
                        <td>
                            <div class="break-row">

                                <input type="time" name="request_clock_in" value="{{ 
                                        $request && $request->clock_in 
                                        ? \Carbon\Carbon::parse($request->clock_in)->format('H:i') 
                                        : ($attendance && $attendance->clock_in 
                                            ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') 
                                            : '') 
                                    }}">

                                <span class="tilde">〜</span>

                                <input type="time" name="request_clock_out" value="{{ 
                                    $request && $request->clock_out 
                                    ? \Carbon\Carbon::parse($request->clock_out)->format('H:i') 
                                    : ($attendance && $attendance->clock_out 
                                        ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') 
                                        : '') 
                                }}">
                            </div>
                        </td>
                    </tr>

                    @php
                    $breaks = ($request && $request->breakRequests->count())
                    ? $request->breakRequests
                    : $attendance->breaks;
                    @endphp

                    @foreach ($attendance->breaks ?? [] as $index => $break)
                    <th>
                        休憩{{ $index === 0 ? '' : $index + 1 }}
                    </th>
                    <td>
                        <div class="break-row">
                            <input type="time" value="{{ \Carbon\Carbon::parse($break->break_start)->format('H:i') }}">

                            <span class="tilde">〜</span>

                            <input type="time" value="{{ \Carbon\Carbon::parse($break->break_end)->format('H:i') }}">
                        </div>
                    </td>
                    </tr>
                    @endforeach

                    <tr>
                        <th>休憩{{ count($breaks ?? []) + 1 }}</th>
                        <td>

                            <div class="break-row">
                                <input type="time" name="break_start[]">

                                <span class="tilde">〜</span>

                                <input type="time" name="break_end[]">
                            </div>
                        </td>
                    </tr>
    </div>
    </td>
    </tr>
    <tr>
        <th>備考</th>
        <td>
            <textarea name="note" rows="3" {{ optional($attendance)===1 ? 'disabled' : '' }}>
                {{ optional($attendance)->note ?? '' }}
            </textarea>
        </td>
    </tr>
    </table>
</div>

@php
$requestStatus = optional(optional($attendance)->request)->request_status;
@endphp

@if ($requestStatus !== 1)
<div class="button-area">
    <button type="submit" class="submit-btn">修正</button>
</div>
@endif

</form>

@if ($requestStatus === 1)
<p style="color:red;">
    ※承認待ちのため修正はできません。
</p>
@endif

@endsection