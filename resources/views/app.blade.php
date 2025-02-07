<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>址曰 - 短網址產生器</title>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
<div class="container mx-auto px-4 py-6 text-center">
    <h1 class="text-2xl font-semibold text-center mb-6 mt-6">址曰 - 短網址產生器</h1>
    <a class="github-button"
       href="https://github.com/budactw/me-shorter"
       data-size="large"
       data-show-count="true"
       aria-label="Star username/repository on GitHub">
        Star
    </a>
    @yield('content')
    @yield('scripts')
    <!-- 在頁面中使用 -->
</div>
</body>
</html>
