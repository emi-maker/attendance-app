@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/staff/index.css') }}">

<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')
<div class="detail-container">
    <div class="title-area">
        <div class="line"></div>
        <h1 class="detail-title">スタッフ一覧</h1>
    </div>
    
    <div class="detail-card attendance-box">
        <table class="detail-table">
        <tr>
            <th>名前</th>
            <th>メールアドレス</th>
            <th>月次勤怠</th>
        </tr>

        @foreach ($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
                <a href="{{ url('/admin/attendance/staff/' . $user->id) }}">詳細</a>
            </td>
        </tr>
        @endforeach
    </table>

</div>
@endsection