@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
<!DOCTYPE html>
<html lang="{{ $lang }}" dir="{{ $isAr ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INSEP PRO - {{ $isAr ? 'إعادة تعيين كلمة المرور' : 'Reset Password' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-tajawal bg-gray-50" style="font-family: '{{ $isAr ? 'Tajawal' : 'Roboto' }}', sans-serif">

<div x-data="{ show: false, show2: false }" class="min-h-screen flex items-center justify-center p-8 bg-white">
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
        <h2 class="text-3xl font-black text-navy mb-2">{{ $isAr ? 'إعادة تعيين كلمة المرور' : 'Reset Password' }}</h2>
        <p class="text-gray-500 mb-8">{{ $isAr ? 'اختر كلمة مرور جديدة لحسابك.' : 'Choose a new password for your account.' }}</p>

        {{-- Flash --}}
        @if ($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <p class="text-red-700 text-sm font-medium">{{ $errors->first() }}</p>
        </div>
        @endif
        @if (session('error'))
        <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <p class="text-red-700 text-sm font-medium">{{ session('error') }}</p>
        </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'البريد الإلكتروني' : 'Email Address' }}</label>
                <div class="relative">
                    <svg class="w-[18px] h-[18px] absolute {{ $isAr ? 'right' : 'left' }}-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <input type="email" name="email" value="{{ old('email', $email) }}" placeholder="example@email.com"
                        class="w-full border-2 border-gray-200 rounded-xl {{ $isAr ? 'pr-12 pl-4' : 'pl-12 pr-4' }} py-3.5 focus:border-navy transition-colors text-gray-700 {{ $email ? 'bg-gray-50' : '' }}" dir="ltr" required {{ $email ? 'readonly' : '' }}>
                </div>
            </div>

            <div>
                <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'كلمة المرور الجديدة' : 'New Password' }}</label>
                <div class="relative">
                    <svg class="w-[18px] h-[18px] absolute {{ $isAr ? 'right' : 'left' }}-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    <input :type="show ? 'text' : 'password'" name="password" placeholder="••••••••" minlength="6"
                        class="w-full border-2 border-gray-200 rounded-xl {{ $isAr ? 'pr-12 pl-12' : 'pl-12 pr-12' }} py-3.5 focus:border-navy transition-colors text-gray-700" dir="ltr" required autofocus>
                    <button type="button" @click="show = !show" tabindex="-1" class="absolute {{ $isAr ? 'left' : 'right' }}-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-navy">
                        <svg x-show="!show" class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg x-show="show" x-cloak class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
            </div>

            <div>
                <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'تأكيد كلمة المرور' : 'Confirm Password' }}</label>
                <div class="relative">
                    <svg class="w-[18px] h-[18px] absolute {{ $isAr ? 'right' : 'left' }}-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    <input :type="show2 ? 'text' : 'password'" name="password_confirmation" placeholder="••••••••" minlength="6"
                        class="w-full border-2 border-gray-200 rounded-xl {{ $isAr ? 'pr-12 pl-12' : 'pl-12 pr-12' }} py-3.5 focus:border-navy transition-colors text-gray-700" dir="ltr" required>
                    <button type="button" @click="show2 = !show2" tabindex="-1" class="absolute {{ $isAr ? 'left' : 'right' }}-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-navy">
                        <svg x-show="!show2" class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg x-show="show2" x-cloak class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full bg-navy hover:bg-navy-dark text-white py-4 rounded-xl font-bold text-lg transition-all duration-300 hover:shadow-xl hover:shadow-navy/20">
                {{ $isAr ? 'تغيير كلمة المرور' : 'Reset Password' }}
            </button>
        </form>

    </div>
</div>

</body>
</html>
