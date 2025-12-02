<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Mi Tienda')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ rand(1000, 9999) }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @yield('styles')
</head>
<body class="admin-body">
    @yield('content')

    <script>
        const token = document.querySelector('meta[name="csrf-token"]').content;
    </script>
    @yield('scripts')
</body>
</html>
