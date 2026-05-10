@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
<!DOCTYPE html>
<html lang="{{ $lang }}" dir="{{ $isAr ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INSEP PRO - إعداد حساب المالك</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-tajawal bg-gray-50 min-h-screen flex items-center justify-center p-4"
      style="font-family: '{{ $isAr ? 'Tajawal' : 'Roboto' }}', sans-serif">

<div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">

    {{-- Logo --}}
    <div class="flex items-center gap-3 mb-8">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg overflow-hidden">
            <img src="{{ asset('insep-logo.png') }}" alt="INSEP" class="w-full h-full object-contain">
        </div>
        <div>
            <h1 class="font-black text-navy text-xl" style="font-family: 'Roboto', sans-serif">INSEP PRO</h1>
            <p class="text-xs text-gray-500">إعداد حساب المالك</p>
        </div>
    </div>

    {{-- Title --}}
    <h2 class="text-2xl font-black text-navy mb-1">إعداد حساب السوبر أدمن</h2>
    <p class="text-gray-500 text-sm mb-6">أدخل بريدك الإلكتروني وكلمة المرور الجديدة. يجب أن يكون البريد مطابقاً لبريد المالك المحدد في إعدادات النظام.</p>

    {{-- Alerts --}}
    @if (session('error'))
    <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <p class="text-red-700 text-sm font-medium">{{ session('error') }}</p>
    </div>
    @endif

    @if ($errors->any())
    <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <p class="text-red-700 text-sm font-medium">{{ $errors->first() }}</p>
    </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="/setup" x-data="{ showPass: false, showConfirm: false }">
        @csrf

        {{-- Email --}}
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">البريد الإلكتروني</label>
            <div class="relative">
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-navy focus:ring-2 focus:ring-navy/10 outline-none text-sm transition-all"
                    placeholder="أدخل بريدك الإلكتروني">
                <svg class="absolute {{ $isAr ? 'left-3' : 'right-3' }} top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
        </div>

        {{-- New Password --}}
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">كلمة المرور الجديدة</label>
            <div class="relative">
                <input :type="showPass ? 'text' : 'password'" name="password" required minlength="6"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-navy focus:ring-2 focus:ring-navy/10 outline-none text-sm transition-all"
                    placeholder="6 أحرف على الأقل">
                <button type="button" @click="showPass = !showPass"
                    class="absolute {{ $isAr ? 'left-3' : 'right-3' }} top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg x-show="showPass" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                </button>
            </div>
        </div>

        {{-- Confirm Password --}}
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">تأكيد كلمة المرور</label>
            <div class="relative">
                <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required minlength="6"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-navy focus:ring-2 focus:ring-navy/10 outline-none text-sm transition-all"
                    placeholder="أعد إدخال كلمة المرور">
                <button type="button" @click="showConfirm = !showConfirm"
                    class="absolute {{ $isAr ? 'left-3' : 'right-3' }} top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <svg x-show="!showConfirm" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg x-show="showConfirm" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                </button>
            </div>
        </div>

        <button type="submit"
            class="w-full bg-navy text-white font-bold py-3.5 rounded-xl hover:bg-navy/90 transition-all text-sm">
            تحديث كلمة المرور
        </button>
    </form>

    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-navy transition-colors">
            العودة لتسجيل الدخول
        </a>
    </div>

    {{-- Security notice --}}
    <div class="mt-6 bg-amber-50 border border-amber-200 rounded-xl p-3 flex items-start gap-2">
        <svg class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <p class="text-amber-700 text-xs">هذه الصفحة محمية — يجب أن يتطابق البريد الإلكتروني مع بريد المالك المحدد في إعدادات الخادم.</p>
    </div>
</div>

</body>
</html>
