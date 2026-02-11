<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('assets/css/admin/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin/styles.css') }}">

    <title>@yield('title', 'Администраторррская')</title>
</head>
<body>
    @if ($errors->any())
        <div style="color:#b91c1c; margin:10px 0;">
            {{ $errors->first() }}
        </div>
    @endif

    @if (session('ok'))
        <div style="color:green; margin:10px 0;">
            {{ session('ok') }}
        </div>
    @endif

    @include('admin.layouts.header')

    <main class="page">
        @yield('content')
    </main>

    <script src="{{ asset('assets/js/admin/script.js') }}"></script>
    <script src="{{ asset('assets/js/admin/accordeon.js') }}"></script>
</body>
</html>
