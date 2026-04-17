@extends('layouts.admin')

@section('content')

<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">

<div class="detail-container">
    <div class="title-area">
        <div class="line"></div>
        <h1 class="detail-title">申請一覧</h1>
    </div>

    <div class="tab-menu">
        <button class="tab active" onclick="showTab('pending', this)">承認待ち</button>
        <button class="tab" onclick="showTab('approved', this)">承認済み</button>
    </div>

    <div class="detail-card attendance-box">
        <div id="pending">
            <table class="request-table">
                <tr>
                    <th>状態</th>
                    <th>名前</th>
                    <th>対象日時</th>
                    <th>申請理由</th>
                    <th>申請日時</th>
                    <th>詳細</th>
                </tr>

                @foreach ($pendingRequests as $request)
                <tr>
                    <td>承認待ち</td>

                    <td>{{ $request->user->name ?? '' }}</td>

                    <td>
                        {{ \Carbon\Carbon::parse($request->attendance->work_date)->format('Y-m-d') }}
                    </td>

                    <td>{{ $request->note }}</td>

                    <td>
                        {{ \Carbon\Carbon::parse($request->created_at)->format('Y-m-d') }}
                    </td>

                    <td>
                        <a href="/attendance/detail/{{ optional($request->attendance)->id }}">
                            詳細
                        </a>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>

        <div id="approved" style="display:none;">
            <table>
                <tr>
                    <th>状態</th>
                    <th>名前</th>
                    <th>対象日時</th>
                    <th>申請理由</th>
                    <th>申請日時</th>
                    <th>詳細</th>
                </tr>

                @foreach ($approvedRequests as $request)
                <tr>
                    <td>{{ $request->id }}</td>
                    <td>{{ $request->request_clock_in }}</td>
                    <td>{{ $request->request_clock_out }}</td>
                </tr>
                @endforeach
            </table>
        </div>


        <script>
            function showTab(tab, el) {    
          // 表示切り替え
            document.getElementById('pending').style.display = 'none';
            document.getElementById('approved').style.display = 'none';
            document.getElementById(tab).style.display = 'block';
        
            // active切り替え
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(t => t.classList.remove('active'));
        
            el.classList.add('active');
            } 
        </script>
        @endsection