<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>attendance</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />

    @yield('css')

</head>

<body class="@yield('body-class')">
    <header class="header">
        <div class="header__inner">
            <a class="header__logo" href="/">
                <img src="{{ asset('images/logo.png') }}" alt="ロゴ">
            </a>

            @if(Auth::guard('admin')->check() || auth()->check())

            @if(!request()->is('email/verify'))

            <nav class="header-nav">

                {{-- 管理者 --}}
                @if(Auth::guard('admin')->check())
                <a href="/admin/attendance/list">勤怠一覧</a>
                <a href="/admin/staff/list">スタッフ一覧</a>
                <a href="/stamp_correction_request/list">申請一覧</a>

                <form action="/admin/logout" method="POST">
                    @csrf
                    <button>ログアウト</button>
                </form>

                {{-- 一般ユーザー --}}
                @else
                <a href="/attendance">勤怠</a>
                <a href="/attendance/list">勤怠一覧</a>
                <a href="/stamp_correction_request/list">申請</a>

                <form action="/logout" method="POST">
                    @csrf
                    <button>ログアウト</button>
                </form>
                @endif

            </nav>
            @endif

            @endif

        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>