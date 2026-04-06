@php
    $lang = app()->getLocale();
    $fSettings = \App\Models\SiteSetting::allKeyed();
    $fFacebook  = $fSettings['social_facebook']   ?? 'https://www.facebook.com/insep.eg/';
    $fInstagram = $fSettings['social_instagram']  ?? 'https://www.instagram.com/insep_pro/';
    $fTwitter   = $fSettings['social_twitter']    ?? 'https://twitter.com/insep_pro';
    $fYoutube   = $fSettings['social_youtube']    ?? 'https://www.youtube.com/@inseppro';
    $fLinkedin  = $fSettings['social_linkedin']   ?? 'https://www.linkedin.com/company/insep-pro';
    $fTelegram  = $fSettings['social_telegram']   ?? 'https://t.me/insep_pro';
    $fPhone1    = $fSettings['contact_phone1']    ?? '01033330027';
    $fPhone2    = $fSettings['contact_phone2']    ?? '01030033090';
    $fPhone3    = $fSettings['contact_phone3']    ?? '0222900951';
    $fEmail     = $fSettings['contact_email']     ?? 'info@insep.net';
    $fAddrAr    = $fSettings['contact_address_ar'] ?? '١٣ الخليفة المأمون، روكسي، مصر الجديدة';
    $fAddrEn    = $fSettings['contact_address_en'] ?? '13 El-Khalifa El-Maamoun, Roxy, Heliopolis';
@endphp
<footer class="bg-gray-900 text-gray-300 relative">
    <div class="container mx-auto px-4 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
            {{-- About Column --}}
            <div>
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center overflow-hidden">
                        <img src="/insep-logo.png" alt="INSEP" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h3 class="font-black text-white text-xl" style="font-family: 'Roboto', sans-serif">INSEP</h3>
                        <p class="text-xs text-gray-400">{{ $lang === 'ar' ? 'معهد علوم الرياضة' : 'Sports Science Institute' }}</p>
                    </div>
                </div>
                <p class="text-gray-400 leading-relaxed mb-6 text-sm">
                    {{ $lang === 'ar' ? 'المعهد الرائد في مجال علوم الرياضة في الشرق الأوسط. نقدم برامج تدريبية معتمدة عالمياً في مجالات التدريب الرياضي، العلاج الطبيعي، التغذية الرياضية، والإدارة الرياضية.' : 'The leading Sports Science institute in MENA. We offer globally accredited programs in coaching, physiotherapy, nutrition and sports management.' }}
                </p>
                <div class="flex flex-wrap gap-2">
                    @if($fFacebook)
                    <a href="{{ $fFacebook }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-lg bg-gray-800 flex items-center justify-center hover:bg-red-brand transition-all duration-300 hover:scale-110">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                    </a>
                    @endif
                    @if($fInstagram)
                    <a href="{{ $fInstagram }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-lg bg-gray-800 flex items-center justify-center hover:bg-red-brand transition-all duration-300 hover:scale-110">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/></svg>
                    </a>
                    @endif
                    @if($fTwitter)
                    <a href="{{ $fTwitter }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-lg bg-gray-800 flex items-center justify-center hover:bg-red-brand transition-all duration-300 hover:scale-110">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M4 4l16 16M4 20L20 4"/></svg>
                    </a>
                    @endif
                    @if($fYoutube)
                    <a href="{{ $fYoutube }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-lg bg-gray-800 flex items-center justify-center hover:bg-red-brand transition-all duration-300 hover:scale-110">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 0 0 1.46 6.42 29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/></svg>
                    </a>
                    @endif
                    @if($fLinkedin)
                    <a href="{{ $fLinkedin }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-lg bg-gray-800 flex items-center justify-center hover:bg-red-brand transition-all duration-300 hover:scale-110">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                    </a>
                    @endif
                    @if($fTelegram)
                    <a href="{{ $fTelegram }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-lg bg-gray-800 flex items-center justify-center hover:bg-red-brand transition-all duration-300 hover:scale-110">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M22 2L11 13M22 2L15 22l-4-9-9-4 20-7z"/></svg>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <h4 class="text-white font-bold text-lg mb-6 relative">
                    {{ $lang === 'ar' ? 'روابط سريعة' : 'Quick Links' }}
                    <span class="absolute bottom-0 {{ $lang === 'ar' ? 'right-0' : 'left-0' }} w-12 h-0.5 bg-red-brand -mb-2"></span>
                </h4>
                <ul class="space-y-3 mt-4">
                    @foreach([
                        ['label' => $lang === 'ar' ? 'الرئيسية' : 'Home', 'route' => 'home'],
                        ['label' => $lang === 'ar' ? 'من نحن' : 'About', 'route' => 'about'],
                        ['label' => $lang === 'ar' ? 'البرامج التدريبية' : 'Courses', 'route' => 'courses'],
                        ['label' => $lang === 'ar' ? 'استعلام الشهادات' : 'Verify Certificate', 'route' => 'verify'],
                        ['label' => $lang === 'ar' ? 'اتصل بنا' : 'Contact', 'route' => 'contact'],
                        ['label' => $lang === 'ar' ? 'تسجيل الدخول' : 'Login', 'route' => 'login'],
                    ] as $link)
                        <li>
                            <a href="{{ route($link['route']) }}" class="text-gray-400 hover:text-red-brand transition-all duration-300 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-brand"></span>
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h4 class="text-white font-bold text-lg mb-6 relative">
                    {{ $lang === 'ar' ? 'تواصل معنا' : 'Contact' }}
                    <span class="absolute bottom-0 {{ $lang === 'ar' ? 'right-0' : 'left-0' }} w-12 h-0.5 bg-red-brand -mb-2"></span>
                </h4>
                <ul class="space-y-4 mt-4">
                    <li class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-lg bg-navy/50 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-red-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">{{ $lang === 'ar' ? $fAddrAr : $fAddrEn }}</p>
                        </div>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-navy/50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-red-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                        </div>
                        <div class="space-y-0.5">
                            @foreach(array_filter([$fPhone1, $fPhone2, $fPhone3]) as $ph)
                            <a href="tel:{{ preg_replace('/\D/', '', $ph) }}" class="block text-sm text-gray-400 hover:text-red-brand transition-colors" style="font-family: 'Roboto', sans-serif; direction: ltr">{{ $ph }}</a>
                            @endforeach
                        </div>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-navy/50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-red-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </div>
                        <span class="text-sm text-gray-400" style="font-family: 'Roboto', sans-serif">{{ $fEmail }}</span>
                    </li>
                </ul>
            </div>

            {{-- Newsletter --}}
            <div>
                <h4 class="text-white font-bold text-lg mb-6 relative">
                    {{ $lang === 'ar' ? 'القائمة البريدية' : 'Newsletter' }}
                    <span class="absolute bottom-0 {{ $lang === 'ar' ? 'right-0' : 'left-0' }} w-12 h-0.5 bg-red-brand -mb-2"></span>
                </h4>
                <p class="text-gray-400 text-sm mb-4 mt-4">{{ $lang === 'ar' ? 'اشترك في قائمتنا البريدية ليصلك كل جديد عن الدورات والبرامج التدريبية' : 'Subscribe to get updates on courses and programs' }}</p>
                <div class="flex gap-2">
                    <input type="email" placeholder="{{ $lang === 'ar' ? 'بريدك الإلكتروني' : 'Your email' }}" class="flex-1 bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-500 focus:border-red-brand">
                    <button class="bg-red-brand hover:bg-red-brand-dark text-white px-4 py-3 rounded-xl transition-colors duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Bar --}}
    <div class="border-t border-gray-800">
        <div class="container mx-auto px-4 py-5 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-gray-500 text-sm flex flex-col lg:flex-row items-center gap-2 text-center lg:text-start">
                <span>INSEP PRO {{ date('Y') }} © Development By <a href="https://www.scadaatech.com" target="_blank" rel="noopener noreferrer" class="text-red-brand hover:text-red-brand-dark transition-colors font-bold" style="font-family: 'Roboto', sans-serif">SCADA Tech</a></span>
            </div>
            <div class="flex items-center gap-4 text-sm text-gray-500 flex-shrink-0">
                <a href="#" class="hover:text-red-brand transition-colors">{{ $lang === 'ar' ? 'سياسة الخصوصية' : 'Privacy Policy' }}</a>
                <span>|</span>
                <a href="#" class="hover:text-red-brand transition-colors">{{ $lang === 'ar' ? 'الشروط والأحكام' : 'Terms & Conditions' }}</a>
            </div>
        </div>
    </div>

    {{-- Scroll to Top --}}
    <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="fixed bottom-6 right-6 z-50 w-12 h-12 bg-navy text-white rounded-xl shadow-xl flex items-center justify-center hover:bg-red-brand transition-all duration-300 hover:scale-110">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
    </button>
</footer>
