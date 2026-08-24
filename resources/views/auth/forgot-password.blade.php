@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
<!DOCTYPE html>
<html lang="{{ $lang }}" dir="{{ $isAr ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INSEP PRO - {{ $isAr ? 'نسيت كلمة المرور' : 'Forgot Password' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-tajawal bg-gray-50" style="font-family: '{{ $isAr ? 'Tajawal' : 'Roboto' }}', sans-serif">

<div class="min-h-screen flex items-center justify-center p-8 bg-white">
    <div class="w-full max-w-md">

        {{-- Top bar: back + lang toggle --}}
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('login') }}" class="flex items-center gap-2 text-gray-500 hover:text-navy transition-colors">
                <svg class="w-[18px] h-[18px] {{ $isAr ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/></svg>
                <span>{{ $isAr ? 'العودة لتسجيل الدخول' : 'Back to Sign In' }}</span>
            </a>
            <a href="{{ route('locale.switch', $isAr ? 'en' : 'ar') }}"
                class="flex items-center gap-1.5 px-3 py-1.5 border border-gray-200 hover:border-navy rounded-xl text-xs font-bold text-gray-600 hover:text-navy transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                {{ $isAr ? 'EN' : 'عر' }}
            </a>
        </div>

        {{-- Logo --}}
        <div class="flex items-center gap-3 mb-8">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg overflow-hidden">
                <img src="{{ asset('insep-logo.png') }}" alt="INSEP" class="w-full h-full object-contain">
            </div>
            <div>
                <h1 class="font-black text-navy text-xl" style="font-family: 'Roboto', sans-serif">INSEP PRO</h1>
                <p class="text-xs text-gray-500">{{ $isAr ? 'منصة علوم الرياضة' : 'Sports Science Platform' }}</p>
            </div>
        </div>

        {{-- Title --}}
        <h2 class="text-3xl font-black text-navy mb-2">{{ $isAr ? 'نسيت كلمة المرور؟' : 'Forgot Password?' }}</h2>
        <p class="text-gray-500 mb-8">{{ $isAr ? 'أدخل بريدك الإلكتروني وسنرسل لك رابطاً لإعادة تعيين كلمة المرور.' : 'Enter your email and we will send you a link to reset your password.' }}</p>

        {{-- Flash --}}
        @if ($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <p class="text-red-700 text-sm font-medium">{{ $errors->first() }}</p>
        </div>
        @endif
        @if (session('success'))
        <div class="mb-5 bg-green-50 border border-green-200 rounded-xl p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
        </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf
            <div>
                <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'البريد الإلكتروني' : 'Email Address' }}</label>
                <div class="relative">
                    <svg class="w-[18px] h-[18px] absolute {{ $isAr ? 'right' : 'left' }}-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="example@email.com"
                        class="w-full border-2 border-gray-200 rounded-xl {{ $isAr ? 'pr-12 pl-4' : 'pl-12 pr-4' }} py-3.5 focus:border-navy transition-colors text-gray-700" dir="ltr" required autofocus>
                </div>
            </div>
            <button type="submit" class="w-full bg-navy hover:bg-navy-dark text-white py-4 rounded-xl font-bold text-lg transition-all duration-300 hover:shadow-xl hover:shadow-navy/20">
                {{ $isAr ? 'إرسال رابط إعادة التعيين' : 'Send Reset Link' }}
            </button>
        </form>

        <div class="mt-6 text-center">
            <span class="text-gray-500">{{ $isAr ? 'تذكرت كلمة المرور؟' : 'Remembered your password?' }}</span>
            <a href="{{ route('login') }}" class="text-red-brand font-bold hover:underline {{ $isAr ? 'mr-1' : 'ml-1' }}">{{ $isAr ? 'تسجيل الدخول' : 'Sign In' }}</a>
        </div>

    </div>
</div>

</body>
</html>
