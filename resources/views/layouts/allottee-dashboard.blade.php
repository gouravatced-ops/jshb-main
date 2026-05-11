<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Allottee Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icons/all.css') }}">
    <style>
        body { background: #f3f6fb; font-family: Inter, Arial, sans-serif; }
    </style>
</head>
<body>
    <main class="container-fluid py-3">
        @yield('content')
    </main>
</body>
</html>
