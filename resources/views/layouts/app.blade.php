<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'INSEP PRO - معهد علوم الرياضة')</title>
    <meta name="description" content="@yield('meta_description', 'INSEP PRO - المنصة التعليمية والإدارية المتكاملة لعلوم الرياضة في الشرق الأوسط')">
    <link
        href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&family=Roboto:wght@400;500;700;900&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col" style="font-family: 'Tajawal', sans-serif">
    @php $lang = app()->getLocale(); @endphp

    @if (!isset($hideLayout) || !$hideLayout)
        @include('partials.header')
    @endif

    <main class="flex-1">
        @yield('content')
    </main>

    @if (!isset($hideLayout) || !$hideLayout)
        @include('partials.footer')
    @endif

    @stack('scripts')
</body>

</html>
