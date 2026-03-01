<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'COACHTECH')</title>

    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layouts/admin.css') }}">
    @yield('css')
</head>

<body>
    <div class="app">
        <header class="header">
            <div class="header__inner">
                <div class="header__logo">
                    <a href="/admin">
                        <img src="{{ asset('images/logo/coachtech.png') }}" alt="COACHTECH">
                    </a>
                </div>

                <nav class="header__nav">
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="/admin/attendance/list" class="nav-link">勤怠一覧</a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/staff/list" class="nav-link">スタッフ一覧</a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/requests" class="nav-link">申請一覧</a>
                        </li>

                        @auth
                            @if(auth()->user()->role === 'admin')
                            <li class="nav-item">
                                <form method="POST" action="{{ route('admin.auth.logout') }}">
                                    @csrf
                                    <button type="submit" class="nav-link-btn">ログアウト</button>
                                </form>
                            </li>
                            @endif
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
</html>