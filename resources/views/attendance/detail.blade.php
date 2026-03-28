@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')

<form action="/attendance/update/{{ $attendance->id }}" method="POST">
    @csrf
    <table>
        <tr>
            <th>名前</th>
            <td>{{ $attendance->user->name }}</td>
        </tr>
        <tr>
            <th>日付</th>
            <td>
                {{ \Carbon\Carbon::parse($attendance->work_date)->format('Y年n月j日') }}
            </td>
        </tr>
        <tr>
            <th>出勤・退勤</th>
            <td>
                <input type="time" name="clock_in" value="{{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}">

                <input type="time" name="clock_out" value="{{ \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') }}">
            </td>
        </tr>
    </table>
</form>    

    <button type="submit">修正申請</button>

@endsection