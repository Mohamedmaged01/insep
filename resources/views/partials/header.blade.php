@php $lang = app()->getLocale(); @endphp
<header class="sticky top-0 z-50 shadow-lg">
    {{-- Top Bar --}}
    <div class="bg-navy text-white py-2.5">
        <div class="container mx-auto px-4 flex justify-between items-center text-sm">
            <div class="flex items-center gap-5">
                <a href="#" class="flex items-center gap-2 hover:text-red-brand transition-colors duration-300">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
                    <span style="font-family: 'Roboto', sans-serif; direction: ltr">+20 10 33330027</span>
                </a>
                <a href="#" class="hidden sm:flex items-center gap-2 hover:text-red-brand transition-colors duration-300">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <span style="font-family: 'Roboto', sans-serif">info@insep.net</span>
                </a>
            </div>
            <div class="flex items-center gap-3">
                <a href="https://www.facebook.com/insep.eg/" target="_blank" rel="noopener noreferrer" class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center hover:bg-red-brand transition-all duration-300 hover:scale-110">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                </a>
                <a href="https://www.instagram.com/insep_pro/" target="_blank" rel="noopener noreferrer" class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center hover:bg-red-brand transition-all duration-300 hover:scale-110">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                </a>
            </div>
        </div>
    </div>

    {{-- Main Navbar --}}
    <nav class="bg-white relative" x-data="{ mobileMenuOpen: false, searchOpen: false, servicesOpen: false }">
        <div class="container mx-auto px-4 flex justify-between items-center h-18">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300 group-hover:scale-105 transform overflow-hidden">
                    <img src="/insep-logo.png" alt="INSEP" class="w-full h-full object-contain">
                </div>
                <div class="text-right">
                    <h1 class="font-black text-navy text-xl leading-tight tracking-wide" style="font-family: 'Roboto', sans-serif">INSEP</h1>
                    <p class="text-[11px] text-gray-500 font-medium">{{ $lang === 'ar' ? 'معهد علوم الرياضة' : 'Sports Science Institute' }}</p>
                </div>
            </a>

            {{-- Desktop Menu --}}
            <div class="hidden lg:flex items-center gap-1">
                @php
                    $navItems = [
                        ['key' => 'home', 'label' => $lang === 'ar' ? 'الرئيسية' : 'Home', 'route' => 'home'],
                        ['key' => 'about', 'label' => $lang === 'ar' ? 'من نحن' : 'About', 'route' => 'about'],
                        ['key' => 'courses', 'label' => $lang === 'ar' ? 'البرامج التدريبية' : 'Courses', 'route' => 'courses'],
                        ['key' => 'contact', 'label' => $lang === 'ar' ? 'اتصل بنا' : 'Contact', 'route' => 'contact'],
                    ];
                @endphp
                @foreach($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="px-4 py-2 font-semibold transition-all duration-300 rounded-lg {{ request()->routeIs($item['route']) ? 'text-red-brand bg-red-brand/5' : 'text-navy hover:text-red-brand hover:bg-gray-50' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
                {{-- Services Dropdown --}}
                <div class="relative" @mouseenter="servicesOpen = true" @mouseleave="servicesOpen = false">
                    <button class="px-4 py-2 text-navy hover:text-red-brand font-semibold transition-colors duration-300 flex items-center gap-1.5 rounded-lg hover:bg-gray-50">
                        {{ $lang === 'ar' ? 'خدمات المنصة' : 'Services' }}
                        <svg class="w-3.5 h-3.5 transition-transform duration-300" :class="servicesOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="servicesOpen" x-cloak x-transition class="absolute top-full right-0 bg-white shadow-2xl rounded-xl py-2 w-56 border border-gray-100 z-50">
                        <a href="{{ route('verify') }}" class="flex items-center gap-3 w-full text-right px-4 py-3 hover:bg-navy/5 text-navy transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-navy/10 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-navy" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
                            </div>
                            <span class="font-medium">{{ $lang === 'ar' ? 'استعلام الشهادات' : 'Certificates Lookup' }}</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('login') }}" class="bg-red-brand text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-red-brand-dark transition-all duration-300 hover:shadow-lg hover:shadow-red-brand/20 hidden sm:block">
                    {{ $lang === 'ar' ? 'تسجيل جديد' : 'Sign Up' }}
                </a>
                <a href="{{ route('login') }}" class="bg-navy text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-navy-dark transition-all duration-300 hover:shadow-lg hover:shadow-navy/20">
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
                ['label' => $lang === 'ar' ? 'استعلام الشهادات' : 'Verify Certificate', 'route' => 'verify'],
                ['label' => $lang === 'ar' ? 'اتصل بنا' : 'Contact', 'route' => 'contact'],
            ] as $item)
                <a href="{{ route($item['route']) }}" class="block w-full text-right px-4 py-3 rounded-xl font-semibold transition-all {{ request()->routeIs($item['route']) ? 'bg-navy text-white' : 'text-navy hover:bg-gray-50' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>
    </nav>
</header>
