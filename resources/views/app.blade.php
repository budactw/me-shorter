<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>址曰 - 短網址產生器</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-semibold text-center mb-6 mt-6">址曰 - 短網址產生器</h1>
    @yield('content')
    @yield('scripts')
</div>
</body>
</html>
