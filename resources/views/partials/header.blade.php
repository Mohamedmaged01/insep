@php
    $lang = app()->getLocale();
    $hSettings = \App\Models\SiteSetting::allKeyed();
    $hPhone1    = $hSettings['contact_phone1']    ?? '01033330027';
    $hPhone2    = $hSettings['contact_phone2']    ?? '01030033090';
    $hPhone3    = $hSettings['contact_phone3']    ?? '0222900951';
    $hEmail     = $hSettings['contact_email']     ?? 'info@insep.net';
    $hFacebook  = $hSettings['social_facebook']   ?? 'https://www.facebook.com/insep.eg/';
    $hInstagram = $hSettings['social_instagram']  ?? 'https://www.instagram.com/insep_pro/';
    $hTwitter   = $hSettings['social_twitter']    ?? 'https://twitter.com/insep_pro';
    $hYoutube   = $hSettings['social_youtube']    ?? 'https://www.youtube.com/@inseppro';
    $hLinkedin  = $hSettings['social_linkedin']   ?? 'https://www.linkedin.com/company/insep-pro';
    $hTelegram  = $hSettings['social_telegram']   ?? 'https://t.me/insep_pro';
@endphp
<header class="sticky top-0 z-50 shadow-lg">
    {{-- Top Bar --}}
    <div class="bg-navy text-white py-2.5">
        <div class="container mx-auto px-4 flex justify-between items-center text-sm">
            <div class="flex items-center gap-4">
                @if($hPhone1)
                <a href="tel:{{ preg_replace('/\D/', '', $hPhone1) }}" class="flex items-center gap-1.5 hover:text-red-brand transition-colors duration-300">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
                    <span style="font-family: 'Roboto', sans-serif; direction: ltr" class="text-xs">{{ $hPhone1 }}</span>
                </a>
                @endif
                @if($hPhone2)
                <a href="tel:{{ preg_replace('/\D/', '', $hPhone2) }}" class="hidden md:flex items-center gap-1.5 hover:text-red-brand transition-colors duration-300">
                    <span style="font-family: 'Roboto', sans-serif; direction: ltr" class="text-xs">{{ $hPhone2 }}</span>
                </a>
                @endif
                @if($hPhone3)
                <a href="tel:{{ preg_replace('/\D/', '', $hPhone3) }}" class="hidden lg:flex items-center gap-1.5 hover:text-red-brand transition-colors duration-300">
                    <span style="font-family: 'Roboto', sans-serif; direction: ltr" class="text-xs">{{ $hPhone3 }}</span>
                </a>
                @endif
                @if($hEmail)
                <a href="mailto:{{ $hEmail }}" class="hidden sm:flex items-center gap-1.5 hover:text-red-brand transition-colors duration-300">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <span style="font-family: 'Roboto', sans-serif" class="text-xs">{{ $hEmail }}</span>
                </a>
                @endif
            </div>
            <div class="flex items-center gap-2">
                @if($hFacebook)
                <a href="{{ $hFacebook }}" target="_blank" rel="noopener noreferrer" class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center hover:bg-red-brand transition-all duration-300 hover:scale-110">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                </a>
                @endif
                @if($hInstagram)
                <a href="{{ $hInstagram }}" target="_blank" rel="noopener noreferrer" class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center hover:bg-red-brand transition-all duration-300 hover:scale-110">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                </a>
                @endif
                @if($hTwitter)
                <a href="{{ $hTwitter }}" target="_blank" rel="noopener noreferrer" class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center hover:bg-red-brand transition-all duration-300 hover:scale-110">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M4 4l16 16M4 20L20 4"/></svg>
                </a>
                @endif
                @if($hYoutube)
                <a href="{{ $hYoutube }}" target="_blank" rel="noopener noreferrer" class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center hover:bg-red-brand transition-all duration-300 hover:scale-110">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 0 0 1.46 6.42 29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/></svg>
                </a>
                @endif
                @if($hLinkedin)
                <a href="{{ $hLinkedin }}" target="_blank" rel="noopener noreferrer" class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center hover:bg-red-brand transition-all duration-300 hover:scale-110">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                </a>
                @endif
                @if($hTelegram)
                <a href="{{ $hTelegram }}" target="_blank" rel="noopener noreferrer" class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center hover:bg-red-brand transition-all duration-300 hover:scale-110">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M22 2L11 13M22 2L15 22l-4-9-9-4 20-7z"/></svg>
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Main Navbar --}}
    <nav class="bg-white relative" x-data="{ mobileMenuOpen: false, searchOpen: false, servicesOpen: false }">
        <div class="container mx-auto px-6 flex justify-between items-center h-22 py-3">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-4 group">
                <div class="w-16 h-16 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300 group-hover:scale-105 transform overflow-hidden">
                    <img src="/insep-logo.png" alt="INSEP" class="w-full h-full object-contain">
                </div>
                <div class="text-right">
                    <h1 class="font-black text-navy text-2xl leading-tight tracking-wide" style="font-family: 'Roboto', sans-serif">INSEP</h1>
                    <p class="text-xs text-gray-500 font-medium">{{ $lang === 'ar' ? 'INSEP لعلوم الرياضة' : 'Sports Science Institute' }}</p>
                </div>
            </a>

            {{-- Desktop Menu --}}
            <div class="hidden lg:flex items-center gap-2">
                @php
                    $navItems = [
                        ['key' => 'home', 'label' => $lang === 'ar' ? 'الرئيسية' : 'Home', 'route' => 'home'],
                        ['key' => 'about', 'label' => $lang === 'ar' ? 'من نحن' : 'About', 'route' => 'about'],
                        ['key' => 'courses', 'label' => $lang === 'ar' ? 'البرامج التدريبية' : 'Courses', 'route' => 'courses'],
                    ];
                @endphp
                @foreach($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="px-5 py-2.5 text-base font-semibold transition-all duration-300 rounded-lg {{ request()->routeIs($item['route']) ? 'text-red-brand bg-red-brand/5' : 'text-navy hover:text-red-brand hover:bg-gray-50' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
                {{-- Services Dropdown --}}
                <div class="relative" @mouseenter="servicesOpen = true" @mouseleave="servicesOpen = false">
                    <button class="px-5 py-2.5 text-base text-navy hover:text-red-brand font-semibold transition-colors duration-300 flex items-center gap-1.5 rounded-lg hover:bg-gray-50">
                        {{ $lang === 'ar' ? 'خدمات إلكترونية' : 'Services' }}
                        <svg class="w-3.5 h-3.5 transition-transform duration-300" :class="servicesOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="servicesOpen" x-cloak x-transition class="absolute top-full right-0 bg-white shadow-2xl rounded-xl py-2 w-60 border border-gray-100 z-50">
                        <a href="{{ route('platform-policy') }}" class="flex items-center gap-3 w-full text-right px-4 py-3 hover:bg-navy/5 text-navy transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-navy/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-navy" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <span class="font-medium">{{ $lang === 'ar' ? 'سياسة المنصة' : 'Platform Policy' }}</span>
                        </a>
                        <a href="{{ route('user-guide') }}" class="flex items-center gap-3 w-full text-right px-4 py-3 hover:bg-navy/5 text-navy transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-navy/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-navy" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <span class="font-medium">{{ $lang === 'ar' ? 'دليل استخدام المنصة' : 'User Guide' }}</span>
                        </a>
                        <a href="{{ route('support') }}" class="flex items-center gap-3 w-full text-right px-4 py-3 hover:bg-navy/5 text-navy transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-navy/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-navy" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <span class="font-medium">{{ $lang === 'ar' ? 'الشكاوي والدعم' : 'Support' }}</span>
                        </a>
                    </div>
                </div>
                {{-- Certificate Lookup - direct nav item --}}
                <a href="{{ route('verify') }}"
                   class="px-5 py-2.5 text-base font-semibold transition-all duration-300 rounded-lg {{ request()->routeIs('verify') ? 'text-red-brand bg-red-brand/5' : 'text-navy hover:text-red-brand hover:bg-gray-50' }}">
                    {{ $lang === 'ar' ? 'استعلام عن الشهادة' : 'Verify Certificate' }}
                </a>
                {{-- Contact - last nav item --}}
                <a href="{{ route('contact') }}"
                   class="px-5 py-2.5 text-base font-semibold transition-all duration-300 rounded-lg {{ request()->routeIs('contact') ? 'text-red-brand bg-red-brand/5' : 'text-navy hover:text-red-brand hover:bg-gray-50' }}">
                    {{ $lang === 'ar' ? 'اتصل بنا' : 'Contact' }}
                </a>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2">
                {{-- Language Toggle --}}
                <a href="{{ route('locale.switch', $lang === 'ar' ? 'en' : 'ar') }}"
                    class="flex items-center gap-1.5 px-3 py-2.5 border border-gray-200 hover:border-navy rounded-xl text-xs font-bold text-gray-600 hover:text-navy transition-all duration-300">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                    {{ $lang === 'ar' ? 'EN' : 'عر' }}
                </a>
                <a href="{{ route('register') }}" class="bg-red-brand text-white px-6 py-3 rounded-xl font-bold hover:bg-red-brand-dark transition-all duration-300 hover:shadow-lg hover:shadow-red-brand/20 hidden sm:block">
                    {{ $lang === 'ar' ? 'تسجيل جديد' : 'Sign Up' }}
                </a>
                <a href="{{ route('login') }}" class="bg-navy text-white px-6 py-3 rounded-xl font-bold hover:bg-navy-dark transition-all duration-300 hover:shadow-lg hover:shadow-navy/20">
                    {{ $lang === 'ar' ? 'دخول' : 'Login' }}
                </a>
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2.5 hover:bg-gray-100 rounded-xl transition">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6 text-navy" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6 text-navy" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileMenuOpen" x-cloak x-transition class="lg:hidden bg-white border-t border-gray-100 px-4 py-4 space-y-1 shadow-xl">
            @foreach([
                ['label' => $lang === 'ar' ? 'الرئيسية' : 'Home', 'route' => 'home'],
                ['label' => $lang === 'ar' ? 'من نحن' : 'About', 'route' => 'about'],
                ['label' => $lang === 'ar' ? 'البرامج التدريبية' : 'Courses', 'route' => 'courses'],
                ['label' => $lang === 'ar' ? 'استعلام عن الشهادة' : 'Verify Certificate', 'route' => 'verify'],
                ['label' => $lang === 'ar' ? 'اتصل بنا' : 'Contact', 'route' => 'contact'],
            ] as $item)
                <a href="{{ route($item['route']) }}" class="block w-full text-right px-4 py-3 rounded-xl font-semibold transition-all {{ request()->routeIs($item['route']) ? 'bg-navy text-white' : 'text-navy hover:bg-gray-50' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>
    </nav>
</header>
