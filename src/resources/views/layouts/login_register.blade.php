<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'COACHTECH')</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layouts/login_register.css')}}">
    @yield('css')
</head>

<body>
    <header class="header">
        <img src="{{ asset('images/logo/coachtech.png') }}" alt="COACHTECH ロゴ">
    </header>

    <div class="login_register">
        <div class="content">
            @yield('content')
        </div>
    </div>
</body>

</html>