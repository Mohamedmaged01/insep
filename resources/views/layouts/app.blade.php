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

    {{-- Floating WhatsApp Button --}}
    <style>
        .wa-float {
            position: fixed;
            bottom: 28px;
            left: 28px;
            z-index: 9999;
        }
        .wa-float a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            background: #25D366;
            border-radius: 50%;
            box-shadow: 0 6px 24px rgba(37,211,102,.45);
            transition: transform .3s ease, box-shadow .3s ease;
            text-decoration: none;
        }
        .wa-float a:hover {
            transform: scale(1.12);
            box-shadow: 0 10px 32px rgba(37,211,102,.6);
        }
        .wa-float a svg {
            width: 32px;
            height: 32px;
            fill: #fff;
        }
        /* Pulse ring */
        .wa-float::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: rgba(37,211,102,.35);
            animation: wa-pulse 2s ease-out infinite;
            pointer-events: none;
        }
        @keyframes wa-pulse {
            0%   { transform: scale(1);   opacity: .8; }
            70%  { transform: scale(1.55); opacity: 0; }
            100% { transform: scale(1.55); opacity: 0; }
        }
        /* Tooltip */
        .wa-float .wa-tip {
            position: absolute;
            bottom: 50%;
            left: calc(100% + 14px);
            transform: translateY(50%);
            background: #111;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
            padding: 6px 12px;
            border-radius: 8px;
            opacity: 0;
            pointer-events: none;
            transition: opacity .25s ease;
        }
        .wa-float:hover .wa-tip { opacity: 1; }
        .wa-float .wa-tip::after {
            content: '';
            position: absolute;
            top: 50%;
            right: 100%;
            transform: translateY(-50%);
            border: 6px solid transparent;
            border-right-color: #111;
        }
    </style>

    <div class="wa-float">
        {{-- ✏️ Replace the phone number below (include country code, no + or spaces) --}}
        <a href="https://wa.me/201000000000" target="_blank" rel="noopener" aria-label="تواصل عبر واتساب">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M20.52 3.48A11.93 11.93 0 0 0 12 0C5.37 0 0 5.37 0 12a11.93 11.93 0 0 0 1.64 6.08L0 24l6.08-1.6A12 12 0 0 0 12 24c6.63 0 12-5.37 12-12a11.93 11.93 0 0 0-3.48-8.52ZM12 21.94a9.94 9.94 0 0 1-5.06-1.38l-.36-.22-3.73.98.99-3.63-.24-.38A9.94 9.94 0 0 1 2.06 12C2.06 6.48 6.48 2.06 12 2.06S21.94 6.48 21.94 12 17.52 21.94 12 21.94Zm5.44-7.45c-.3-.15-1.77-.87-2.04-.97s-.47-.15-.67.15-.77.97-.94 1.17-.35.22-.65.07a8.14 8.14 0 0 1-2.39-1.48 9 9 0 0 1-1.65-2.06c-.17-.3 0-.46.13-.61s.3-.35.45-.52a2 2 0 0 0 .3-.5.55.55 0 0 0-.02-.52c-.07-.15-.67-1.62-.92-2.22s-.49-.5-.67-.51h-.57a1.1 1.1 0 0 0-.8.37 3.36 3.36 0 0 0-1.05 2.5 5.84 5.84 0 0 0 1.22 3.1c.15.2 2.1 3.2 5.08 4.49a17.2 17.2 0 0 0 1.7.63 4.08 4.08 0 0 0 1.87.12c.57-.09 1.77-.72 2.02-1.42s.25-1.3.17-1.42-.27-.2-.57-.35Z"/>
            </svg>
        </a>
        <span class="wa-tip">تواصل عبر واتساب</span>
    </div>
</body>

</html>
