@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attemdamce.css') }}">
@endsection

@section('content')

<div class="attendance-wrapper">

    <div class="attendance-status">
        勤務外
    </div>

    <div class="attendance-date">
        2026年3月21日（土）
    </div>

    <div class="attendance-time">
        10:30
    </div>
    <form action="attendance/start" method="POST">
        @csrf

        <button class="attendance-btn">
            出勤
        </button>
    </form>

</div>

@endsection