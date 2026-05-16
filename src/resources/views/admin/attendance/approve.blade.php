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

        <form id="attendance-form" form action="/stamp_correction_request/approve/{{ $attendanceRequest->id }}"
            method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="work_date" value="{{ $attendanceRequest->attendance->work_date }}">

            <table class="detail-table">
                <tr>
                    <th>名前</th>
                    <td>{{ $attendanceRequest->user->name }}</td>
                </tr>
                <tr>
                    <th>日付</th>
                    <td>
                        <div class="date-box">
                            <span class="year">
                                {{ optional($attendanceRequest->attendance)->work_date
                                ? \Carbon\Carbon::parse($attendanceRequest->attendance->work_date)->format('Y年')
                                : '' }}
                            </span>
                            <span class="date">
                                {{ optional($attendanceRequest->attendance)->work_date
                                ? \Carbon\Carbon::parse($attendanceRequest->attendance->work_date)->format('n月j日')
                                : '' }}
                            </span>
                        </div>
                </tr>
                <tr>
                    <th>出勤・退勤</th>
                    <td>
                        <div class="break-row">
                            <div class="break-input-group">
                                <input type="time" name="request_clock_in"
                                    value="{{ old('request_clock_in',\Carbon\Carbon::parse($clockIn)->format('H:i')) }}">
                            </div>

                            <span class="tilde">〜</span>

                            <div class="break-input-group">
                                <input type="time" name="request_clock_out"
                                    value="{{ old('request_clock_out',\Carbon\Carbon::parse($clockOut)->format('H:i')) }}">
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
                {{-- 既存の休憩表示 --}}
                @foreach ($breaks as $index => $break)
                <tr>
                    <th>休憩{{ $index + 1 }}</th>
                    <td>
                        <div class="break-row">
                            <div class="break-input-group">
                                <input type="time" name="breaks[{{ $index }}][break_start]"
                                    value="{{ old('breaks.' . $index . '.break_start',\Carbon\Carbon::parse($break->break_start)->format('H:i')) }}">
                            </div>

                            <span>〜</span>

                            <div class="break-input-group">
                                <input type="time" name="breaks[{{ $index }}][break_end]"
                                    value="{{ old('breaks.' . $index . '.break_end',\Carbon\Carbon::parse($break->break_end)->format('H:i')) }}">
                            </div>
                        </div>

                        @error('breaks.0.break_start')
                        <p class="error-message">{{ $message }}</p>
                        @enderror

                        @error('breaks.' . $index . '.break_end')
                        <p class="error-message">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>
                @endforeach


                {{-- 新しく追加する1行 --}}
                <tr>
                    <th>休憩{{ count($breaks) + 1 }}</th>
                    <td>
                        <div class="break-row">
                            <div class="break-input-group">
                                <input type="time" name="breaks[{{ count($breaks) }}][break_start]"
                                    value="{{ old('breaks.' . count($breaks) . '.break_start') }}">
                            </div>

                            <span>〜</span>

                            <div class="break-input-group">
                                <input type="time" name="breaks[{{ count($breaks) }}][break_end]"
                                    value="{{ old('breaks.' . count($breaks) . '.break_end') }}">
                            </div>
                        </div>

                        @error('breaks.' . count($breaks) . '.break_start')
                        <p class="error-message">{{ $message }}</p>
                        @enderror

                        @error('breaks.' . count($breaks) . '.break_end')
                        <p class="error-message">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>

                <th>備考</th>
                <td>
                    <textarea name="note" rows="3">{{ old('note', $attendanceRequest->note) }}</textarea>

                    @error('note')
                    <p class="error-message">{{ $message }}</p>
                    @enderror
                </td>
                </tr>
            </table>
    </div>

    @php
    $requestStatus = optional($attendanceRequest)->status;
    @endphp

    {{-- 承認待ちならボタン出す --}}
    <div class="button-area">
        @if ($requestStatus === 0)
        <button type="submit" class="submit-btn">承認</button>
        @else
        <span class="submit-btn approved-btn">承認済み</span>
        @endif
        </form>
    </div>
</div>
@endsection