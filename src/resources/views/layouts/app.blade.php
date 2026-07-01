<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'COACHTECH')</title>

    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css')}}">
    @yield('css')
</head>

<body>
    <div class="app">
        <header class="header">
            <div class="header__inner">
                <div class="header__logo">
                    <a href="/">
                        <img src="{{ asset('images/logo/coachtech.png') }}" alt="COACHTECH">
                    </a>
                </div>

                <nav class="header__nav">
                    <ul class="nav-list">
                        <li class="nav-item"><a href="/attendance" class="nav-link">勤怠</a></li>
                        <li class="nav-item"><a href="/attendance/list" class="nav-link">勤怠一覧</a></li>
                        <li class="nav-item"><a href="{{ route('stamp_correction_request.list') }}" class="nav-link">申請</a></li>
                        <li class="nav-item"> <a href="{{ route('attendance.report') }}" class="nav-link">レポート</a></li>
                        @auth
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link-btn">ログアウト</button>
                            </form>
                        </li>
                        @endauth
                    </ul>
                </nav>
            </div>
        </header>

        <main class="main">
            @yield('content')
        </main>

    </div>
</body>