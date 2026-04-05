@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
<!DOCTYPE html>
<html lang="{{ $lang }}" dir="{{ $isAr ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INSEP PRO - {{ $isAr ? 'تسجيل الدخول' : 'Login' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-tajawal bg-gray-50" style="font-family: '{{ $isAr ? 'Tajawal' : 'Roboto' }}', sans-serif">

<div x-data="loginPage()" class="min-h-screen flex">

    {{-- Form Side --}}
    <div class="flex-1 flex items-center justify-center p-8 bg-white">
        <div class="w-full max-w-md">

            {{-- Top bar: back + lang toggle --}}
            <div class="flex items-center justify-between mb-8">
                <a href="{{ url('/') }}" class="flex items-center gap-2 text-gray-500 hover:text-navy transition-colors">
                    <svg class="w-[18px] h-[18px] {{ $isAr ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/></svg>
                    <span>{{ $isAr ? 'العودة للرئيسية' : 'Back to Home' }}</span>
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
            <h2 class="text-3xl font-black text-navy mb-2"
                x-text="isRegister ? '{{ $isAr ? 'إنشاء حساب جديد' : 'Create Account' }}' : '{{ $isAr ? 'تسجيل الدخول' : 'Sign In' }}'"></h2>
            <p class="text-gray-500 mb-8"
                x-text="isRegister ? '{{ $isAr ? 'أنشئ حسابك للوصول إلى جميع الدورات والخدمات' : 'Create your account to access all courses and services' }}' : '{{ $isAr ? 'أدخل بياناتك للوصول إلى حسابك' : 'Enter your credentials to access your account' }}'"></p>

            {{-- Error Message --}}
            @if ($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3 animate-slideDown">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <p class="text-red-700 text-sm font-medium">{{ $errors->first() }}</p>
            </div>
            @endif
            @if (session('error'))
            <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3 animate-slideDown">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <p class="text-red-700 text-sm font-medium">{{ session('error') }}</p>
            </div>
            @endif
            @if (session('success'))
            <div class="mb-5 bg-green-50 border border-green-200 rounded-xl p-4 flex items-start gap-3 animate-slideDown">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
            </div>
            @endif

            {{-- Role Selection (Login only) --}}
            <div x-show="!isRegister" class="mb-6">
                <label class="text-sm font-bold text-navy mb-3 block">{{ $isAr ? 'نوع الحساب' : 'Account Type' }}</label>
                <div class="grid grid-cols-3 gap-3">
                    <template x-for="role in roles" :key="role.key">
                        <button type="button" @click="selectRole(role.key)"
                            :class="selectedRole === role.key ? 'border-navy bg-navy/5 text-navy' : 'border-gray-200 text-gray-400 hover:border-gray-300'"
                            class="p-3 rounded-xl border-2 transition-all duration-300 flex flex-col items-center gap-2">
                            <svg class="w-[22px] h-[22px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <template x-if="role.key === 'student'"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 3a4 4 0 100 8 4 4 0 000-8z"/></template>
                                <template x-if="role.key === 'instructor'"><g><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></g></template>
                                <template x-if="role.key === 'admin'"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></template>
                            </svg>
                            <span class="text-sm font-semibold" x-text="role.label"></span>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Login Form --}}
            <form x-show="!isRegister" method="POST" action="{{ url('/login') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'البريد الإلكتروني' : 'Email Address' }}</label>
                    <div class="relative">
                        <svg class="w-[18px] h-[18px] absolute {{ $isAr ? 'right' : 'left' }}-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <input type="email" name="email" x-model="email" placeholder="example@email.com"
                            class="w-full border-2 border-gray-200 rounded-xl {{ $isAr ? 'pr-12 pl-4' : 'pl-12 pr-4' }} py-3.5 focus:border-navy transition-colors text-gray-700" dir="ltr" required>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'كلمة المرور' : 'Password' }}</label>
                    <div class="relative">
                        <svg class="w-[18px] h-[18px] absolute {{ $isAr ? 'right' : 'left' }}-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                        <input :type="showPassword ? 'text' : 'password'" name="password" x-model="password" placeholder="••••••••"
                            class="w-full border-2 border-gray-200 rounded-xl {{ $isAr ? 'pr-12 pl-12' : 'pl-12 pr-12' }} py-3.5 focus:border-navy transition-colors text-gray-700" dir="ltr" required>
                        <button type="button" @click="showPassword = !showPassword" class="absolute {{ $isAr ? 'left' : 'right' }}-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-navy">
                            <svg x-show="!showPassword" class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg x-show="showPassword" x-cloak class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-navy accent-[#1B2A4A]">
                        <span class="text-sm text-gray-600">{{ $isAr ? 'تذكرني' : 'Remember me' }}</span>
                    </label>
                    <a href="#" class="text-sm text-red-brand hover:underline font-medium">{{ $isAr ? 'نسيت كلمة المرور؟' : 'Forgot password?' }}</a>
                </div>
                <button type="submit" class="w-full bg-navy hover:bg-navy-dark text-white py-4 rounded-xl font-bold text-lg transition-all duration-300 hover:shadow-xl hover:shadow-navy/20">
                    {{ $isAr ? 'تسجيل الدخول' : 'Sign In' }}
                </button>
            </form>

            {{-- Register Form --}}
            <form x-show="isRegister" x-cloak method="POST" action="{{ url('/register') }}" class="space-y-5">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الاسم بالعربية' : 'Arabic Name' }}</label>
                        <div class="relative">
                            <svg class="w-[18px] h-[18px] absolute {{ $isAr ? 'right' : 'left' }}-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 3a4 4 0 100 8 4 4 0 000-8z"/></svg>
                            <input type="text" name="name_ar" placeholder="{{ $isAr ? 'الاسم الكامل' : 'Full name in Arabic' }}"
                                class="w-full border-2 border-gray-200 rounded-xl {{ $isAr ? 'pr-12 pl-4' : 'pl-12 pr-4' }} py-3.5 focus:border-navy transition-colors text-gray-700" dir="rtl" required>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الاسم بالإنجليزية' : 'English Name' }}</label>
                        <div class="relative">
                            <svg class="w-[18px] h-[18px] absolute {{ $isAr ? 'right' : 'left' }}-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 3a4 4 0 100 8 4 4 0 000-8z"/></svg>
                            <input type="text" name="name_en" placeholder="Full name"
                                class="w-full border-2 border-gray-200 rounded-xl {{ $isAr ? 'pr-12 pl-4' : 'pl-12 pr-4' }} py-3.5 focus:border-navy transition-colors text-gray-700" dir="ltr" required>
                        </div>
                    </div>
                </div>
                <div x-data="countryPicker()" class="relative">
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'رقم الجوال' : 'Phone Number' }}</label>
                    <input type="hidden" name="phone" :value="selected.code + phoneNumber">
                    <div class="flex gap-2">
                        <div class="relative flex-shrink-0">
                            <button type="button" @click="open = !open" @keydown.escape="open = false"
                                class="h-full flex items-center gap-2 border-2 border-gray-200 rounded-xl px-3 py-3.5 bg-white hover:border-navy transition-colors text-sm font-semibold text-navy min-w-[90px]" dir="ltr">
                                <span x-text="selected.flag" class="text-lg leading-none"></span>
                                <span x-text="selected.code" style="font-family:'Roboto',sans-serif"></span>
                                <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-cloak @click.outside="open = false"
                                class="absolute top-full mt-1 {{ $isAr ? 'right-0' : 'left-0' }} z-50 bg-white border border-gray-200 rounded-xl shadow-2xl w-64 max-h-64 overflow-y-auto">
                                <div class="p-2">
                                    <input type="text" x-model="search" placeholder="{{ $isAr ? 'ابحث عن دولة...' : 'Search country...' }}" @click.stop
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-navy transition-colors mb-1">
                                </div>
                                <template x-for="c in filtered" :key="c.code + c.name">
                                    <button type="button" @click="selected = c; open = false; search = ''"
                                        class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-navy/5 transition-colors text-sm text-{{ $isAr ? 'right' : 'left' }}"
                                        :class="selected.code === c.code && selected.name === c.name ? 'bg-navy/5 font-bold' : ''">
                                        <span x-text="c.flag" class="text-lg flex-shrink-0"></span>
                                        <span x-text="c.name" class="flex-1 text-gray-700"></span>
                                        <span x-text="c.code" class="text-gray-400 flex-shrink-0" style="font-family:'Roboto',sans-serif; direction:ltr"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                        <input type="tel" x-model="phoneNumber" placeholder="1XXXXXXXXX"
                            class="flex-1 border-2 border-gray-200 rounded-xl px-4 py-3.5 focus:border-navy transition-colors text-gray-700" dir="ltr">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'البريد الإلكتروني' : 'Email Address' }}</label>
                    <div class="relative">
                        <svg class="w-[18px] h-[18px] absolute {{ $isAr ? 'right' : 'left' }}-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <input type="email" name="email" placeholder="example@email.com"
                            class="w-full border-2 border-gray-200 rounded-xl {{ $isAr ? 'pr-12 pl-4' : 'pl-12 pr-4' }} py-3.5 focus:border-navy transition-colors text-gray-700" dir="ltr" required>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'كلمة المرور' : 'Password' }}</label>
                    <div class="relative">
                        <svg class="w-[18px] h-[18px] absolute {{ $isAr ? 'right' : 'left' }}-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                        <input :type="showPassword ? 'text' : 'password'" name="password" placeholder="••••••••"
                            class="w-full border-2 border-gray-200 rounded-xl {{ $isAr ? 'pr-12 pl-12' : 'pl-12 pr-12' }} py-3.5 focus:border-navy transition-colors text-gray-700" dir="ltr" required>
                        <button type="button" @click="showPassword = !showPassword" class="absolute {{ $isAr ? 'left' : 'right' }}-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-navy">
                            <svg x-show="!showPassword" class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg x-show="showPassword" x-cloak class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                </div>
                <button type="submit" class="w-full bg-navy hover:bg-navy-dark text-white py-4 rounded-xl font-bold text-lg transition-all duration-300 hover:shadow-xl hover:shadow-navy/20">
                    {{ $isAr ? 'إنشاء الحساب' : 'Create Account' }}
                </button>
            </form>

            {{-- Toggle Login/Register --}}
            <div class="mt-6 text-center">
                <span class="text-gray-500"
                    x-text="isRegister ? '{{ $isAr ? 'لديك حساب بالفعل؟' : 'Already have an account?' }}' : '{{ $isAr ? 'ليس لديك حساب؟' : 'Don\'t have an account?' }}'"></span>
                <button @click="isRegister = !isRegister" class="text-red-brand font-bold hover:underline {{ $isAr ? 'mr-1' : 'ml-1' }}"
                    x-text="isRegister ? '{{ $isAr ? 'تسجيل الدخول' : 'Sign In' }}' : '{{ $isAr ? 'سجل الآن' : 'Register' }}'"></button>
            </div>

            {{-- Demo Credentials --}}
            <div x-show="!isRegister" class="mt-6 p-5 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-200">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <p class="text-sm text-blue-800 font-bold">{{ $isAr ? 'بيانات الدخول التجريبية' : 'Demo Credentials' }}</p>
                </div>
                <div class="space-y-2">
                    <button type="button" @click="fillCredentials('admin')"
                        class="w-full flex items-center justify-between bg-white/80 hover:bg-white rounded-xl px-4 py-2.5 text-sm transition-all border border-blue-100 hover:border-blue-300 group">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-red-brand/10 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-red-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            </div>
                            <div class="{{ $isAr ? 'text-right' : 'text-left' }}">
                                <span class="font-bold text-navy block text-xs">{{ $isAr ? 'المدير' : 'Admin' }}</span>
                                <span class="text-gray-400 text-[11px]" style="font-family: 'Roboto', sans-serif">admin@insep.net</span>
                            </div>
                        </div>
                        <span class="text-blue-600 text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity">{{ $isAr ? 'تعبئة ←' : '→ Fill' }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Visual Side --}}
    <div class="hidden lg:flex flex-1 relative bg-gradient-to-br from-navy via-navy-light to-navy-dark items-center justify-center overflow-hidden">
        <div class="absolute inset-0 hero-pattern opacity-30"></div>
        <div class="absolute top-20 left-20 w-60 h-60 bg-red-brand/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-20 w-80 h-80 bg-white/5 rounded-full blur-3xl"></div>

        <div class="relative z-10 text-center p-12 max-w-lg">
            <div class="w-24 h-24 bg-white/10 backdrop-blur-sm rounded-3xl flex items-center justify-center mx-auto mb-8 border border-white/20 animate-float overflow-hidden">
                <img src="{{ asset('insep-logo.png') }}" alt="INSEP" class="w-20 h-20 object-contain">
            </div>
            <h2 class="text-4xl font-black text-white mb-4">INSEP PRO</h2>
            <p class="text-white/70 text-lg leading-relaxed mb-8">
                {{ $isAr ? 'المنصة التعليمية والإدارية المتكاملة لعلوم الرياضة في الشرق الأوسط' : 'The integrated educational and administrative platform for sports science in the Middle East' }}
            </p>
            <div class="grid grid-cols-3 gap-4">
                @foreach([
                    ['num' => '+20K', 'label' => $isAr ? 'متدرب' : 'Trainees'],
                    ['num' => '+5K',  'label' => $isAr ? 'دورة'   : 'Courses'],
                    ['num' => '+150', 'label' => $isAr ? 'مدرب'   : 'Trainers'],
                ] as $stat)
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/10">
                    <div class="text-2xl font-black text-white" style="font-family: 'Roboto', sans-serif">{{ $stat['num'] }}</div>
                    <div class="text-white/60 text-sm">{{ $stat['label'] }}</div>
                </div>
                @endforeach
            </div>

            <div class="mt-10 inline-flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-xl px-5 py-3 border border-white/15">
                <svg class="w-[18px] h-[18px] text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <div class="{{ $isAr ? 'text-right' : 'text-left' }}">
                    <p class="text-white text-sm font-bold">{{ $isAr ? 'نظام آمن ومشفّر' : 'Secure & Encrypted' }}</p>
                    <p class="text-white/50 text-xs">{{ $isAr ? 'حماية CSRF & XSS & SQL Injection' : 'CSRF & XSS & SQL Injection Protection' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function loginPage() {
    return {
        isRegister: {{ isset($startRegister) && $startRegister ? 'true' : 'false' }},
        showPassword: false,
        selectedRole: 'admin',
        email: '',
        password: '',
        roles: [
            { key: 'student',    label: '{{ $isAr ? 'طالب'   : 'Student' }}' },
            { key: 'instructor', label: '{{ $isAr ? 'مدرب'   : 'Trainer' }}' },
            { key: 'admin',      label: '{{ $isAr ? 'مدير'   : 'Admin' }}' },
        ],
        accounts: {
            admin: { email: 'admin@insep.net', password: '654321@' },
        },
        selectRole(key) { this.selectedRole = key; },
        fillCredentials(role) {
            const account = this.accounts[role];
            this.email = account.email;
            this.password = account.password;
            this.selectedRole = role;
        }
    };
}

function countryPicker() {
    const countries = [
        { flag: '🇪🇬', name: 'Egypt',        code: '+20' },
        { flag: '🇸🇦', name: 'Saudi Arabia', code: '+966' },
        { flag: '🇦🇪', name: 'UAE',          code: '+971' },
        { flag: '🇰🇼', name: 'Kuwait',       code: '+965' },
        { flag: '🇶🇦', name: 'Qatar',        code: '+974' },
        { flag: '🇧🇭', name: 'Bahrain',      code: '+973' },
        { flag: '🇴🇲', name: 'Oman',         code: '+968' },
        { flag: '🇯🇴', name: 'Jordan',       code: '+962' },
        { flag: '🇱🇧', name: 'Lebanon',      code: '+961' },
        { flag: '🇮🇶', name: 'Iraq',         code: '+964' },
        { flag: '🇸🇾', name: 'Syria',        code: '+963' },
        { flag: '🇵🇸', name: 'Palestine',    code: '+970' },
        { flag: '🇱🇾', name: 'Libya',        code: '+218' },
        { flag: '🇹🇳', name: 'Tunisia',      code: '+216' },
        { flag: '🇲🇦', name: 'Morocco',      code: '+212' },
        { flag: '🇩🇿', name: 'Algeria',      code: '+213' },
        { flag: '🇸🇩', name: 'Sudan',        code: '+249' },
        { flag: '🇾🇪', name: 'Yemen',        code: '+967' },
        { flag: '🇹🇷', name: 'Turkey',       code: '+90' },
        { flag: '🇬🇧', name: 'United Kingdom', code: '+44' },
        { flag: '🇺🇸', name: 'United States', code: '+1' },
        { flag: '🇫🇷', name: 'France',       code: '+33' },
        { flag: '🇩🇪', name: 'Germany',      code: '+49' },
        { flag: '🇮🇹', name: 'Italy',        code: '+39' },
        { flag: '🇪🇸', name: 'Spain',        code: '+34' },
        { flag: '🇵🇰', name: 'Pakistan',     code: '+92' },
        { flag: '🇮🇳', name: 'India',        code: '+91' },
        { flag: '🇳🇬', name: 'Nigeria',      code: '+234' },
        { flag: '🇿🇦', name: 'South Africa', code: '+27' },
        { flag: '🇨🇦', name: 'Canada',       code: '+1' },
        { flag: '🇦🇺', name: 'Australia',    code: '+61' },
    ];
    return {
        open: false, search: '', phoneNumber: '',
        selected: countries[0], countries,
        get filtered() {
            if (!this.search) return this.countries;
            const q = this.search.toLowerCase();
            return this.countries.filter(c => c.name.toLowerCase().includes(q) || c.code.includes(q));
        }
    };
}
</script>

</body>
</html>
