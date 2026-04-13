@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">

<div class="detail-container">
    <div class="title-area">
        <div class="line"></div>
        <h1 class="detail-title">申請一覧</h1>
    </div>

    <div class="tab-menu">
        <button class="tab active" onclick="showTab('pending')">承認待ち</button>
        <button class="tab" onclick="showTab('approved')">承認済み</button>
    </div>

    <div id="pending">
        <table>
            <tr>
                <th>出勤</th>
                <th>退勤</th>
            </tr>
    
        @foreach ($pendingRequests as $request)
        <tr>
            <td>{{ $request->id }}</td>
            <td>{{ $request->request_clock_in }}</td>
            <td>{{ $request->request_clock_out }}</td>
        </tr>
        @endforeach
    </table>
    </div>

    <div id="approved" style="display:none;">
    
    <table>
        <tr>
            <th>出勤</th>
            <th>退勤</th>
        </tr>
    
        @foreach ($approvedRequests as $request)
        <tr>
            <td>{{ $request->id }}</td>
            <td>{{ $request->request_clock_in }}</td>
            <td>{{ $request->request_clock_out }}</td>
        </tr>
        @endforeach
    </table>
@endsection

<script>
    function showTab(tab) {
    document.getElementById('pending').style.display = 'none';
    document.getElementById('approved').style.display = 'none';

    document.getElementById(tab).style.display = 'block';
}
</script>