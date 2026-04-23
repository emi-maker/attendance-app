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

        <form action="/stamp_correction_request/approve/{{ $request->id }}" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="work_date" value="{{ $request->attendance->work_date }}">


            <table class="detail-table">
                <tr>
                    <th>名前</th>
                    <td>{{ $request->user->name }}</td>
                </tr>
                <tr>
                    <th>日付</th>
                    <td>
                        <div class="date-box">
                            <span class="year">
                                {{ \Carbon\Carbon::parse ($request->attendance->work_date)->format('Y年') }}
                            </span>
                            <span class="date">
                                {{ \Carbon\Carbon::parse($request->attendance->work_date)->format('n月j日') }}
                            </span>
                        </div>

                </tr>
                <tr>
                    <th>出勤・退勤</th>
                    <td>
                       <div class="break-row">
                        <div class="break-input-group">
                            <input type="time" name="request_clock_in"
                                value="{{ $request->request_clock_in ? \Carbon\Carbon::parse($request->request_clock_in)->format('H:i') : '' }}">
                    
                            @error('request_clock_in')
                            <p style="color: red;" class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                    
                        <span class="tilde">〜</span>
                    
                        <div class="break-input-group">
                            <input type="time" name="request_clock_out"
                                value="{{ $request->request_clock_out ? \Carbon\Carbon::parse($request->request_clock_out)->format('H:i') : '' }}">
                    
                            @error('request_clock_out')
                            <p style="color: red;" class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
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
                                <input type="time" name="breaks[{{ $index }}][break_start]"
                                    value="{{ optional($break->break_start)->format('H:i') }}">
                                @error('breaks.' . $index . '.break_start')
                                <p style="color: red;" class="error-message">{{ $message }}</p>
                                @enderror
                            </div>


                            <span class="tilde">〜</span>

                            <div class="break-input-group">
                                <input type="time" name="breaks[{{ $index }}][break_end]"
                                    value="{{ optional($break->break_end)->format('H:i') }}">
                                @error('breaks.' . $index . '.break_end')
                                <p style="color: red;" class="error-message">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach

                <tr>
                    <th>休憩{{ count($displayBreaks) + 1 }}</th>
                    <td>

                        <div class="break-row">
                            <div class="break-input-group">
                                <input type="time" name="breaks[{{ count($displayBreaks) }}][break_start]"
                                    value="{{ old('breaks.' . count($displayBreaks) . '.break_start') }}">
                                <p style="color: red;">
                                    {{ $errors->first('breaks.' . count($displayBreaks) . '.break_start') }}
                                </p>
                            </div>

                            <span class="tilde">〜</span>

                            <input type="time" name="breaks[{{ count($displayBreaks) }}][break_end]"
                                value="{{ old('breaks.' . count($displayBreaks) . '.break_end') }}">
                            <p style="color: red;">
                                {{ $errors->first('breaks.' . count($displayBreaks) . '.break_end') }}
                            </p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>備考</th>
                    <td>
                        <textarea name="note" rows="3">{{ old('note', $request->note) }}
            </textarea>
                        @error('note')
                        <p style="color: red;"> {{ $message }}</p>
                        @enderror
                    </td>
                </tr>
            </table>
    </div>

@php
$requestStatus = optional($request)->status;
@endphp

{{-- 承認待ちならボタン出す --}}
@if ($requestStatus === 0)
<div class="button-area">
    <button type="submit" class="submit-btn">修正</button>
</div>
@endif

{{-- 承認済みなら表示 --}}
@if ($requestStatus !== 0)
<p>承認済み</p>
@endif

</form>

{{-- 承認待ちメッセージ --}}
@if ($requestStatus === 0)
<p style="color:red;">
    ※承認待ちのため修正できます。
</p>
@endif

@endsection    