@extends('layouts.app')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
@section('title', 'INSEP PRO - ' . ($isAr ? 'معهد علوم الرياضة | المنصة التعليمية والإدارية' : 'Sports Science Institute | Educational & Administrative Platform'))

@section('content')
{{-- Hero Section --}}
<section class="relative min-h-[600px] lg:min-h-[700px] overflow-hidden" x-data="{ currentSlide: 0 }" x-init="setInterval(() => currentSlide = (currentSlide + 1) % 4, 5000)">
    @php
        $slideImages = [
            'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=1500&q=85',
            'https://images.unsplash.com/photo-1552674605-db6ffd4facb5?auto=format&fit=crop&w=1500&q=85',
            'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=1500&q=85',
            'https://images.unsplash.com/photo-1549476464-37392f717541?auto=format&fit=crop&w=1500&q=85',
        ];
        $slides = $isAr ? [
            ['title' => 'ابدأ رحلتك الاحترافية في علوم الرياضة مع INSEP PRO', 'subtitle' => 'حوّل شغفك بالرياضة إلى مهنة معتمدة', 'desc' => 'انضم إلى مجتمعنا التعليمي المتميز واحصل على شهادات معتمدة دولياً تفتح لك آفاقاً جديدة في سوق العمل الرياضي.'],
            ['title' => 'تعلم، تطور، وكن مدربًا محترفًا مع INSEP PRO', 'subtitle' => 'برامج علمية… تطبيق عملي… مستقبل أقوى', 'desc' => 'مناهجنا الدراسية مصممة لدمج النظرية بالتطبيق، مما يضمن لك اكتساب المهارات العملية اللازمة للنجاح.'],
            ['title' => 'خطوتك الأولى نحو الاحتراف الرياضي تبدأ من هنا', 'subtitle' => 'علوم الرياضة بأسلوب حديث ومحتوى تطبيقي', 'desc' => 'استفد من أحدث التقنيات والأساليب التعليمية في مجال علوم الرياضة على يد نخبة من الخبراء والمتخصصين.'],
            ['title' => 'تعليم رياضي بمعايير أكاديمية وفرص حقيقية', 'subtitle' => 'استثمر في نفسك… واصنع مستقبلك الرياضي', 'desc' => 'نحن نلتزم بتقديم تعليم عالي الجودة يلبي المعايير العالمية ويؤهلك للمنافسة في سوق العمل المحلي والدولي.'],
        ] : [
            ['title' => 'Start Your Pro Journey in Sports Science with INSEP PRO', 'subtitle' => 'Turn your passion for sports into an accredited career', 'desc' => 'Join our outstanding educational community and earn internationally accredited certificates that open new horizons in the sports job market.'],
            ['title' => 'Learn, Develop & Become a Pro Trainer with INSEP PRO', 'subtitle' => 'Scientific programs... practical application... stronger future', 'desc' => 'Our curricula are designed to combine theory with practice, ensuring you acquire the practical skills needed for success.'],
            ['title' => 'Your First Step Towards Sports Professionalism Starts Here', 'subtitle' => 'Sports science with a modern approach and applied content', 'desc' => 'Benefit from the latest educational techniques and methods in sports science under the guidance of top experts and specialists.'],
            ['title' => 'Sports Education with Academic Standards & Real Opportunities', 'subtitle' => 'Invest in yourself... and build your sports future', 'desc' => 'We are committed to providing high-quality education that meets global standards and qualifies you to compete in local and international markets.'],
        ];
    @endphp

    @foreach($slides as $i => $slide)
    <div class="absolute inset-0 transition-opacity duration-1000" :class="currentSlide === {{ $i }} ? 'opacity-100' : 'opacity-0'">
        <div class="absolute inset-0">
            <img src="{{ $slideImages[$i] }}" alt="Slide" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-l from-navy/95 via-navy/80 to-navy/40"></div>
            <div class="absolute inset-0 hero-pattern opacity-20"></div>
        </div>
    </div>
    @endforeach

    <div class="relative z-10 container mx-auto px-4 h-full flex items-center min-h-[600px] lg:min-h-[700px]">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white px-4 py-2 rounded-full mb-6 border border-white/20">
                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                <span class="text-sm font-medium">{{ $isAr ? 'المعهد الأول في علوم الرياضة بالشرق الأوسط' : 'The #1 Sports Science Institute in the Middle East' }}</span>
            </div>
            @foreach($slides as $i => $slide)
            <template x-if="currentSlide === {{ $i }}">
                <div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-4 leading-tight">{{ $slide['title'] }}</h1>
                    <h2 class="text-xl md:text-2xl text-red-brand-light font-bold mb-4">{{ $slide['subtitle'] }}</h2>
                    <p class="text-lg text-white/80 mb-8 max-w-xl leading-relaxed">{{ $slide['desc'] }}</p>
                </div>
            </template>
            @endforeach
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('courses') }}" class="bg-red-brand hover:bg-red-brand-dark text-white px-8 py-4 rounded-xl font-bold text-lg transition-all duration-300 flex items-center gap-2 hover:shadow-xl hover:shadow-red-brand/30 hover:scale-105 transform">
                    {{ $isAr ? 'تصفح الدورات' : 'Browse Courses' }}
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
                </a>
                <a href="{{ route('login') }}" class="bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white px-8 py-4 rounded-xl font-bold text-lg transition-all duration-300 border border-white/30 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                    {{ $isAr ? 'سجل الآن' : 'Register Now' }}
                </a>
            </div>
        </div>
    </div>

    {{-- Slide indicators --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-3 z-20">
        @for($i = 0; $i < 4; $i++)
        <button @click="currentSlide = {{ $i }}" :class="currentSlide === {{ $i }} ? 'bg-red-brand w-10' : 'bg-white/40 w-2.5 hover:bg-white/60'" class="h-2.5 rounded-full transition-all duration-500"></button>
        @endfor
    </div>
</section>

{{-- Identity Bar --}}
<section class="bg-white py-16 relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-l from-navy via-red-brand to-navy"></div>
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([
                ['title_ar' => 'رؤيتنا',   'title_en' => 'Our Vision',   'desc_ar' => 'أن نكون المعهد الرائد والمرجع الأول في علوم الرياضة على مستوى الشرق الأوسط والعالم العربي', 'desc_en' => 'To be the leading and primary reference in sports science across the Middle East and Arab world.', 'color' => 'from-navy to-navy-light', 'icon' => 'eye'],
                ['title_ar' => 'رسالتنا',  'title_en' => 'Our Mission',  'desc_ar' => 'تقديم برامج تدريبية احترافية معتمدة دولياً تساهم في تطوير الكوادر الرياضية وفق أحدث المعايير العلمية', 'desc_en' => 'Delivering internationally accredited professional training programs that develop sports personnel according to the latest scientific standards.', 'color' => 'from-red-brand to-red-brand-dark', 'icon' => 'target'],
                ['title_ar' => 'أهدافنا',  'title_en' => 'Our Goals',    'desc_ar' => 'بناء جيل متميز من المتخصصين في علوم الرياضة ونشر ثقافة التدريب العلمي والمهني في المجتمع', 'desc_en' => 'Building a distinguished generation of sports science specialists and spreading a culture of scientific and professional training in society.', 'color' => 'from-navy-dark to-navy', 'icon' => 'lightbulb'],
            ] as $item)
            <div class="group text-center p-8 rounded-2xl hover:shadow-2xl transition-all duration-500 border border-gray-100 hover:border-transparent card-hover bg-white">
                <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-gradient-to-br {{ $item['color'] }} flex items-center justify-center shadow-lg group-hover:scale-110 transform transition-transform duration-500 group-hover:rotate-3">
                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        @if($item['icon'] === 'eye') <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                        @elseif($item['icon'] === 'target') <circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>
                        @else <path d="M9 18V5l12-2v13M9 9l12-2"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/>
                        @endif
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-navy mb-4">{{ $isAr ? $item['title_ar'] : $item['title_en'] }}</h3>
                <p class="text-gray-600 leading-relaxed">{{ $isAr ? $item['desc_ar'] : $item['desc_en'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Featured Courses --}}
<section class="bg-gray-50 py-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="inline-block bg-red-brand/10 text-red-brand px-4 py-1.5 rounded-full text-sm font-bold mb-4">{{ $isAr ? 'برامجنا التدريبية' : 'Our Training Programs' }}</span>
            <h2 class="text-3xl md:text-4xl font-black text-navy mb-4">{{ $isAr ? 'الدورات المميزة' : 'Featured Courses' }}</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">{{ $isAr ? 'اكتشف مجموعة متنوعة من البرامج التدريبية المعتمدة المصممة خصيصاً لتطوير مهاراتك في مجال علوم الرياضة' : 'Discover a diverse range of accredited training programs designed specifically to develop your skills in sports science' }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($courses as $i => $course)
            <div class="bg-white rounded-2xl overflow-hidden card-hover border border-gray-100 opacity-0 animate-fadeInUp" style="animation-delay: {{ $i * 0.1 }}s; animation-fill-mode: forwards">
                <div class="relative h-48 overflow-hidden">
                    <img src="{{ $course->image ? (str_starts_with($course->image, 'http') ? $course->image : asset('storage/' . ltrim($course->image, '/'))) : 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?auto=format&fit=crop&w=800&q=80' }}" alt="{{ $course->title }}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110" onerror="this.src='https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?auto=format&fit=crop&w=800&q=80'">
                    <div class="absolute top-4 right-4 bg-navy/80 backdrop-blur-sm text-white px-3 py-1 rounded-lg text-xs font-bold shadow-sm">{{ $course->category }}</div>
                    <div class="absolute top-4 left-4 bg-red-brand/90 text-white px-3 py-1 rounded-lg text-xs font-bold shadow-sm">{{ $course->level }}</div>
                </div>
                <div class="p-6">
                    <a href="{{ route('course.detail', $course->id) }}">
                        <h3 class="text-lg font-bold text-navy mb-2 line-clamp-2 hover:text-red-brand transition-colors">{{ $course->title }}</h3>
                    </a>
                    <p class="text-xs text-gray-400 mb-3 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-red-brand flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        {{ $isAr ? 'شهادة احترافية — فيديوهات + قراءات + اختبارات' : 'Professional Certificate — Videos + Readings + Exams' }}
                    </p>
                    <div class="flex items-center gap-4 text-sm text-gray-500 mb-4">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            {{ $course->duration ?? '-' }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                            {{ $course->student_count ?? 0 }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <span class="text-xl font-black text-red-brand" style="font-family: 'Roboto', sans-serif">{{ number_format($course->price ?? 0) }} <span class="text-sm font-medium text-gray-500">{{ $course->currency ?? 'USD' }}</span></span>
                        <div class="flex gap-2">
                            <a href="{{ route('course.detail', $course->id) }}" class="border border-navy text-navy hover:bg-navy hover:text-white px-3 py-2 rounded-xl text-xs font-bold transition-all">{{ $isAr ? 'تفاصيل' : 'Details' }}</a>
                            <a href="{{ route('register') }}" class="bg-navy hover:bg-navy-dark text-white px-3 py-2 rounded-xl text-xs font-bold transition-all">{{ $isAr ? 'سجل' : 'Enroll' }}</a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            @php
                $fallbackCourses = $isAr ? [
                    ['title' => 'دبلوم التدريب الرياضي المتقدم', 'cat' => 'التدريب الرياضي', 'price' => 2500, 'duration' => '120 ساعة', 'level' => 'متقدم', 'image' => 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?auto=format&fit=crop&q=80'],
                    ['title' => 'أساسيات العلاج الطبيعي الرياضي', 'cat' => 'العلاج الطبيعي', 'price' => 1800, 'duration' => '80 ساعة', 'level' => 'مبتدئ', 'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&q=80'],
                    ['title' => 'التغذية الرياضية للمحترفين', 'cat' => 'التغذية الرياضية', 'price' => 1500, 'duration' => '60 ساعة', 'level' => 'متوسط', 'image' => 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?auto=format&fit=crop&q=80'],
                ] : [
                    ['title' => 'Advanced Sports Coaching Diploma', 'cat' => 'Sports Coaching', 'price' => 2500, 'duration' => '120 Hours', 'level' => 'Advanced', 'image' => 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?auto=format&fit=crop&q=80'],
                    ['title' => 'Fundamentals of Sports Physiotherapy', 'cat' => 'Physiotherapy', 'price' => 1800, 'duration' => '80 Hours', 'level' => 'Beginner', 'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&q=80'],
                    ['title' => 'Professional Sports Nutrition', 'cat' => 'Sports Nutrition', 'price' => 1500, 'duration' => '60 Hours', 'level' => 'Intermediate', 'image' => 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?auto=format&fit=crop&q=80'],
                ];
            @endphp
            @foreach($fallbackCourses as $i => $fc)
            <div class="bg-white rounded-2xl overflow-hidden card-hover border border-gray-100 opacity-0 animate-fadeInUp" style="animation-delay: {{ $i * 0.1 }}s; animation-fill-mode: forwards">
                <div class="relative h-48 overflow-hidden">
                    <img src="{{ $fc['image'] }}" alt="{{ $fc['title'] }}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                    <div class="absolute top-4 right-4 bg-navy/80 backdrop-blur-sm text-white px-3 py-1 rounded-lg text-xs font-bold">{{ $fc['cat'] }}</div>
                    <div class="absolute top-4 left-4 bg-red-brand/90 text-white px-3 py-1 rounded-lg text-xs font-bold">{{ $fc['level'] }}</div>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-bold text-navy mb-3 line-clamp-2">{{ $fc['title'] }}</h3>
                    <div class="flex items-center gap-4 text-sm text-gray-500 mb-4">
                        <span>{{ $fc['duration'] }}</span>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <span class="text-xl font-black text-red-brand" style="font-family: 'Roboto', sans-serif">{{ number_format($fc['price']) }} <span class="text-sm font-medium text-gray-500">USD</span></span>
                        <a href="{{ route('register') }}" class="bg-navy hover:bg-navy-dark text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-all">{{ $isAr ? 'سجل الآن' : 'Enroll Now' }}</a>
                    </div>
                </div>
            </div>
            @endforeach
            @endforelse
        </div>

        <div class="text-center mt-10">
            <a href="{{ route('courses') }}" class="bg-white border-2 border-navy text-navy hover:bg-navy hover:text-white px-8 py-3.5 rounded-xl font-bold transition-all duration-300 inline-flex items-center gap-2">
                {{ $isAr ? 'عرض الكل' : 'View All' }}
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
            </a>
        </div>
    </div>
</section>


{{-- Why Choose Us --}}
<section class="bg-white py-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-14">
            <span class="inline-block bg-navy/10 text-navy px-4 py-1.5 rounded-full text-sm font-bold mb-4">{{ $isAr ? 'مميزاتنا' : 'Why Us' }}</span>
            <h2 class="text-3xl md:text-4xl font-black text-navy mb-4">{{ $isAr ? 'لماذا تختار معهد INSEP؟' : 'Why Choose INSEP Institute?' }}</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">{{ $isAr ? 'نقدم لك تجربة تعليمية فريدة تجمع بين الخبرة الأكاديمية والتطبيق العملي' : 'We offer you a unique learning experience that combines academic expertise with practical application' }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                ['title_ar' => 'شهادات معتمدة دولياً', 'title_en' => 'Internationally Accredited', 'desc_ar' => 'جميع برامجنا معتمدة من جهات دولية معترف بها في مجال علوم الرياضة', 'desc_en' => 'All our programs are accredited by internationally recognized bodies in sports science.', 'color' => 'bg-blue-50 text-blue-600'],
                ['title_ar' => 'نخبة من المدربين',      'title_en' => 'Elite Trainers',              'desc_ar' => 'مدربون متخصصون حاصلون على أعلى الشهادات العلمية والمهنية الدولية', 'desc_en' => 'Specialized trainers holding the highest international scientific and professional qualifications.', 'color' => 'bg-green-50 text-green-600'],
                ['title_ar' => 'محتوى علمي حديث',       'title_en' => 'Up-to-date Content',          'desc_ar' => 'حقائب تدريبية محدثة باستمرار وفق أحدث الأبحاث والمعايير العلمية', 'desc_en' => 'Training materials continuously updated according to the latest research and scientific standards.', 'color' => 'bg-purple-50 text-purple-600'],
                ['title_ar' => 'دعم فني متواصل',         'title_en' => '24/7 Technical Support',      'desc_ar' => 'فريق دعم فني متخصص متاح على مدار الساعة لمساعدتك في رحلتك التعليمية', 'desc_en' => 'A specialized technical support team available around the clock to assist you throughout your learning journey.', 'color' => 'bg-orange-50 text-orange-600'],
                ['title_ar' => 'جداول مرنة',             'title_en' => 'Flexible Schedules',          'desc_ar' => 'برامج تدريبية بجداول مرنة تناسب جميع الأوقات سواء حضورياً أو عن بُعد', 'desc_en' => 'Training programs with flexible schedules suitable for all times, whether in-person or remote.', 'color' => 'bg-pink-50 text-pink-600'],
                ['title_ar' => 'تحقق إلكتروني',          'title_en' => 'Digital Verification',        'desc_ar' => 'نظام تحقق إلكتروني متطور للشهادات عبر QR Code ورقم تسلسلي فريد', 'desc_en' => 'An advanced digital certificate verification system via QR Code and unique serial number.', 'color' => 'bg-teal-50 text-teal-600'],
            ] as $feature)
            <div class="group p-7 rounded-2xl border border-gray-100 hover:border-transparent hover:shadow-xl transition-all duration-500 card-hover bg-white">
                <div class="w-14 h-14 rounded-xl {{ $feature['color'] }} flex items-center justify-center mb-5 group-hover:scale-110 transform transition-transform duration-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>
                </div>
                <h3 class="text-xl font-bold text-navy mb-3">{{ $isAr ? $feature['title_ar'] : $feature['title_en'] }}</h3>
                <p class="text-gray-600 leading-relaxed text-sm">{{ $isAr ? $feature['desc_ar'] : $feature['desc_en'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="relative py-20 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-red-brand to-red-brand-dark">
        <div class="absolute inset-0 hero-pattern opacity-20"></div>
    </div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <h2 class="text-3xl md:text-4xl font-black text-white mb-4">{{ $isAr ? 'ابدأ رحلتك المهنية اليوم' : 'Start Your Professional Journey Today' }}</h2>
        <p class="text-white/80 text-lg mb-8 max-w-2xl mx-auto">{{ $isAr ? 'انضم إلى آلاف المتدربين الذين طوروا مسيرتهم المهنية معنا في مجال علوم الرياضة' : 'Join thousands of trainees who have advanced their professional careers with us in the field of sports science' }}</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('register') }}" class="bg-white text-red-brand hover:bg-gray-100 px-8 py-4 rounded-xl font-bold text-lg transition-all duration-300 hover:shadow-xl hover:scale-105 transform">{{ $isAr ? 'سجل الآن' : 'Register Now' }}</a>
            <a href="{{ route('contact') }}" class="bg-white/10 backdrop-blur-sm text-white hover:bg-white/20 px-8 py-4 rounded-xl font-bold text-lg transition-all duration-300 border border-white/30">{{ $isAr ? 'تواصل معنا' : 'Contact Us' }}</a>
        </div>
    </div>
</section>

{{-- Statistics Bar --}}
<section class="bg-navy py-10" x-data="statsCounter()" x-intersect.once="startCounting()">
    <div class="container mx-auto px-4">
        <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:1.5rem; text-align:center">
            @php
                $statsData = [
                    ['value' => (int)($stats['students']     ?? 20000), 'label_ar' => 'طالب مسجل',    'label_en' => 'Registered Students',      'suffix' => '+'],
                    ['value' => (int)($stats['trainers']     ?? 50),    'label_ar' => 'مدرب معتمد',   'label_en' => 'Certified Trainers',        'suffix' => '+'],
                    ['value' => (int)($stats['courses']      ?? 5000),  'label_ar' => 'دورة تدريبية', 'label_en' => 'Training Programs',         'suffix' => '+'],
                    ['value' => (int)($stats['certificates'] ?? 2000),  'label_ar' => 'شهادة معتمدة', 'label_en' => 'Accredited Certificates',   'suffix' => '+'],
                ];
            @endphp
            @foreach($statsData as $idx => $stat)
            <div class="text-white" x-data="{ displayed: 0 }">
                <div class="text-4xl font-black text-red-brand mb-1" style="font-family: 'Roboto', sans-serif">
                    <span x-text="formatNum(counts[{{ $idx }}])">0</span>{{ $stat['suffix'] }}
                </div>
                <div class="text-sm font-medium text-white/80">{{ $isAr ? $stat['label_ar'] : $stat['label_en'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<script>
function statsCounter() {
    return {
        targets: [{{ implode(', ', array_column($statsData, 'value')) }}],
        counts:  [0, 0, 0, 0],
        started: false,
        startCounting() {
            if (this.started) return;
            this.started = true;
            const duration = 2000;
            const steps = 60;
            const interval = duration / steps;
            let step = 0;
            const timer = setInterval(() => {
                step++;
                const progress = step / steps;
                const ease = 1 - Math.pow(1 - progress, 3);
                this.counts = this.targets.map(t => Math.round(t * ease));
                if (step >= steps) {
                    this.counts = [...this.targets];
                    clearInterval(timer);
                }
            }, interval);
        },
        formatNum(n) {
            if (n >= 1000) return (n / 1000).toFixed(n % 1000 === 0 ? 0 : 1) + 'K';
            return n.toString();
        }
    }
}
</script>

{{-- News Section --}}
<section class="bg-gray-50 py-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="inline-block bg-red-brand/10 text-red-brand px-4 py-1.5 rounded-full text-sm font-bold mb-4">{{ $isAr ? 'آخر الأخبار' : 'Latest News' }}</span>
            <h2 class="text-3xl md:text-4xl font-black text-navy mb-4">{{ $isAr ? 'أحدث الأخبار والمقالات' : 'Latest News & Articles' }}</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
                $placeholderImages = [
                    'https://images.unsplash.com/photo-1517649763962-0c623066013b?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?auto=format&fit=crop&w=800&q=80',
                ];
                $newsItems = $news->count() > 0 ? $news : collect($isAr ? [
                    (object)['id' => 1, 'title' => 'إطلاق برنامج الدبلوم الجديد في التحليل الرياضي', 'created_at' => now(), 'description' => 'يسر معهد INSEP الإعلان عن إطلاق برنامج الدبلوم الجديد في التحليل الرياضي باستخدام التقنيات الحديثة', 'tag' => 'أخبار', 'image' => null],
                    (object)['id' => 2, 'title' => 'شراكة استراتيجية مع الاتحاد الدولي للرياضة', 'created_at' => now(), 'description' => 'وقّع المعهد اتفاقية شراكة مع الاتحاد الدولي لتبادل الخبرات وتطوير البرامج التدريبية المعتمدة', 'tag' => 'شراكات', 'image' => null],
                    (object)['id' => 3, 'title' => 'تخريج الدفعة الخامسة من برنامج التدريب الرياضي', 'created_at' => now(), 'description' => 'احتفل المعهد بتخريج أكثر من 200 متدرب من برنامج التدريب الرياضي المتقدم في حفل كبير', 'tag' => 'فعاليات', 'image' => null],
                ] : [
                    (object)['id' => 1, 'title' => 'Launch of New Sports Analysis Diploma', 'created_at' => now(), 'description' => 'INSEP Institute is pleased to announce the new Sports Analysis Diploma using modern techniques.', 'tag' => 'News', 'image' => null],
                    (object)['id' => 2, 'title' => 'Strategic Partnership with International Sports Fed', 'created_at' => now(), 'description' => 'The institute signed a partnership to exchange expertise and develop accredited programs.', 'tag' => 'Partnerships', 'image' => null],
                    (object)['id' => 3, 'title' => 'Graduation of the 5th Batch in Sports Coaching', 'created_at' => now(), 'description' => 'The institute celebrated graduating over 200 trainees from the advanced coaching program.', 'tag' => 'Events', 'image' => null],
                ]);
            @endphp
            @foreach($newsItems as $i => $item)
            <a href="{{ $item instanceof \App\Models\News ? route('news.show', $item->id) : '#' }}"
               target="_blank"
               class="bg-white rounded-2xl overflow-hidden card-hover border border-gray-100 opacity-0 animate-fadeInUp block group" style="animation-delay: {{ $i * 0.15 }}s; animation-fill-mode: forwards">
                <div class="h-52 relative overflow-hidden">
                    <img src="{{ $item->image ?? $placeholderImages[$i % 3] }}" alt="{{ $item->title }}"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
                    <div class="absolute top-4 right-4 bg-red-brand text-white px-3 py-1 rounded-lg text-xs font-bold">{{ $item->tag ?? ($isAr ? 'أخبار' : 'News') }}</div>
                    <div class="absolute bottom-4 right-4 text-white/80 text-sm flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        {{ $item->date ?? ($item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') : '') }}
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-bold text-navy mb-3 group-hover:text-red-brand transition-colors line-clamp-2">{{ $item->title }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed line-clamp-3 mb-4">{{ $item->description ?? '' }}</p>
                    <span class="text-red-brand font-bold text-sm flex items-center gap-1">{{ $isAr ? 'اقرأ المزيد' : 'Read More' }} <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg></span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
{{-- Partners --}}
@php
$partners = [
    ['name' => 'FIFA',  'img' => '/images/partners/fifa.svg'],
    ['name' => 'AFC',   'img' => '/images/partners/afc.svg'],
    ['name' => 'IOC',   'img' => '/images/partners/ioc.svg'],
    ['name' => 'NSCA',  'img' => '/images/partners/nsca.svg'],
    ['name' => 'ACSM',  'img' => '/images/partners/acsm.svg'],
    ['name' => 'NASM',  'img' => '/images/partners/nasm.svg'],
    ['name' => 'UEFA',  'img' => '/images/partners/uefa.svg'],
    ['name' => 'WADA',  'img' => '/images/partners/wada.svg'],
    ['name' => 'IPC',   'img' => '/images/partners/ipc.svg'],
];
@endphp
<section class="bg-white py-16">
 
    {{-- Heading --}}
    <div class="text-center mb-10">
        <h2 class="text-2xl font-black text-gray-800 uppercase tracking-widest" style="font-family:'Roboto',sans-serif">
            {{ $lang === 'ar' ? 'شركاؤنا' : 'OUR PARTNERS' }}
        </h2>
        <div class="mx-auto mt-3 w-12 h-0.5 bg-gray-300"></div>
    </div>
 
    {{-- Carousel --}}
    <div
        x-data="{
            current: 0,
            total: {{ count($partners) }},
            perPage: 3,
            autoplayInterval: null,
            paused: false,
 
            init() {
                this.updatePerPage();
                window.addEventListener('resize', () => this.updatePerPage());
                this.startAutoplay();
            },
 
            updatePerPage() {
                const w = window.innerWidth;
                if (w < 640) this.perPage = 2;
                else if (w < 1024) this.perPage = 3;
                else this.perPage = 5;
                // Clamp current index so we don't overshoot
                if (this.current > this.maxIndex()) this.current = this.maxIndex();
            },
 
            maxIndex() {
                return Math.max(0, this.total - this.perPage);
            },
 
            prev() {
                this.current = this.current <= 0 ? this.maxIndex() : this.current - 1;
            },
 
            next() {
                this.current = this.current >= this.maxIndex() ? 0 : this.current + 1;
            },
 
            startAutoplay() {
                this.autoplayInterval = setInterval(() => {
                    if (!this.paused) this.next();
                }, 3000);
            },
 
            get offset() {
                // Each item is (100 / perPage)% wide; shift by current * that width
                // Positive for RTL (slide track rightward), negative for LTR
                const dir = document.documentElement.dir === 'rtl' || document.body.dir === 'rtl' ? 1 : -1;
                return dir * this.current * (100 / this.perPage);
            }
        }"
        @mouseenter="paused = true"
        @mouseleave="paused = false"
        class="relative max-w-6xl mx-auto px-14 sm:px-16"
    >
        {{-- Prev button --}}
        <button @click="prev()"
            class="absolute left-0 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white border-2 border-gray-200 shadow-md flex items-center justify-center text-gray-400 hover:text-navy hover:border-navy hover:shadow-lg transition-all duration-300 z-10 group">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
 
        {{-- Track --}}
        <div class="overflow-hidden">
            <div
                class="flex transition-transform duration-500 ease-in-out"
                :style="'transform: translateX(' + offset + '%)'"
            >
                @foreach($partners as $i => $p)
                <div
                    class="flex-shrink-0 px-3 sm:px-4"
                    :style="'width: ' + (100 / perPage) + '%'"
                >
                    <div class="flex items-center justify-center h-28 rounded-xl border border-gray-100 hover:border-navy/30 hover:shadow-lg transition-all duration-300 bg-white group cursor-pointer">
                        <img src="{{ $p['img'] }}"
                             alt="{{ $p['name'] }}"
                             class="max-h-16 sm:max-h-20 max-w-[80%] object-contain grayscale group-hover:grayscale-0 opacity-40 group-hover:opacity-90 transition-all duration-500 group-hover:scale-110 transform"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                        <span class="text-xl sm:text-2xl font-black text-gray-300 group-hover:text-navy hidden items-center justify-center h-20 transition-colors duration-300" style="font-family:'Roboto',sans-serif">{{ $p['name'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
 
        {{-- Next button --}}
        <button @click="next()"
            class="absolute right-0 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white border-2 border-gray-200 shadow-md flex items-center justify-center text-gray-400 hover:text-navy hover:border-navy hover:shadow-lg transition-all duration-300 z-10 group">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>
 
        {{-- Progress dots --}}
        <div class="flex justify-center gap-1.5 mt-8">
            <template x-for="i in (maxIndex() + 1)" :key="i">
                <button
                    @click="current = i - 1"
                    :class="(i - 1) === current
                        ? 'bg-navy w-6'
                        : 'bg-gray-300 w-2 hover:bg-gray-400'"
                    class="h-2 rounded-full transition-all duration-400"
                ></button>
            </template>
        </div>
    </div>
 
</section>
@endsection
