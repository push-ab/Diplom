<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link rel="stylesheet" href="{{ asset('assets/css/client/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/client/styles.css') }}">

    <title>@yield('title', 'Кинотеатр')</title>
</head>
<body>
    @include('client.layouts.header')

    <main class="page">
        @yield('content')
    </main>
    <script src="{{ asset('assets/js/client/script.js') }}"></script>
</body>
</html>
