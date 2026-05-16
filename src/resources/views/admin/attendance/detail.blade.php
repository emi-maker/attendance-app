@extends('layouts.admin')

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

        <form id="attendance-form" action="/admin/attendance/update/{{ $attendance->id }}" method="POST">
            @csrf
            @method('PUT')

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
                            <div class="break-input-group">
                                <input type="time" name="request_clock_in"
                                    value="{{ old('request_clock_in', $clockIn ? \Carbon\Carbon::parse($clockIn)->format('H:i') : '') }}">

                            </div>

                            <span class="tilde">〜</span>

                            <div class="break-input-group">
                                <input type="time" name="request_clock_out"
                                    value="{{ old('request_clock_out', $clockOut ? \Carbon\Carbon::parse($clockOut)->format('H:i') : '') }}">
                            </div>
                        </div>

                        @error('request_clock_in')
                        <p class="error-message">{{ $message }}</p>
                        @enderror

                        @error('request_clock_out')
                        <p class="error-message">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>

                @foreach ($displayBreaks as $index => $break)
                <tr>
                    <th>
                        休憩{{ $index === 0 ? '' : $index + 1 }}
                    </th>
                    <td>
                        <div class="break-row">
                            <div class="break-input-group">
                                <input type="time" name="breaks[{{ $index }}][break_start]" value="{{ is_array($break) && !empty($break['break_start'])
                                        ? \Carbon\Carbon::parse($break['break_start'])->format('H:i') 
                                        : (!is_array($break) && $break->break_start 
                                            ? \Carbon\Carbon::parse($break->break_start)->format('H:i') 
                                            : '') }}">
                            </div>

                            <span class="tilde">〜</span>

                            <div class="break-input-group">
                                <input type="time" name="breaks[{{ $index }}][break_end]" value="{{ is_array($break) && !empty($break['break_end'])
                                    ? \Carbon\Carbon::parse($break['break_end'])->format('H:i') 
                                    : (!is_array($break) && $break->break_end 
                                    ? \Carbon\Carbon::parse($break->break_end)->format('H:i') 
                                            : '') }}">
                            </div>
                        </div>

                        @error('breaks.' . $index . '.break_start')
                        <p class="error-message">{{ $message }}</p>
                        @enderror

                        @error('breaks.' . $index . '.break_end')
                        <p class="error-message">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>
                @endforeach

                <tr>
                    <th>
                        休憩{{ count($displayBreaks) + 1 }}
                    </th>
                    <td>
                        <div class="break-row">
                            <div class="break-input-group">
                                <input type="time" name="breaks[{{ count($displayBreaks) }}][break_start]">
                            </div>

                            <span class="tilde">〜</span>

                            <div class="break-input-group">
                                <input type="time" name="breaks[{{ count($displayBreaks) }}][break_end]">
                            </div>

                        </div>

                        @error('breaks.' . count($displayBreaks) . '.break_start')
                        <p class="error-message">{{ $message }}</p>
                        @enderror

                        @error('breaks.' . count($displayBreaks) . '.break_end')
                        <p class="error-message">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>
                <th>備考</th>
                <td>
                    <textarea name="note" rows="3">{{ old('note', $note) }}</textarea>

                    @error('note')
                    <p class="error-message">{{ $message }}</p>
                    @enderror
                </td>
                </tr>
            </table>
    </div>

    <div class="button-area">

        @if($attendanceRequest)

        <p class="status-message">
            ※承認待ちのため修正はできません。
        </p>

        @else

        <button type="submit" class="submit-btn">修正</button>
        @endif
        </form>
    </div>
</div>
@endsection