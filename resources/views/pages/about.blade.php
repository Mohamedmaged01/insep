@extends('layouts.app')
@section('title', 'INSEP PRO - من نحن')

@section('content')
{{-- Hero --}}
<section class="bg-gradient-to-br from-navy via-navy-light to-navy-dark py-20 relative overflow-hidden">
    <div class="absolute inset-0 hero-pattern opacity-30"></div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <span class="inline-block bg-white/10 text-white px-4 py-1.5 rounded-full text-sm font-bold mb-4 border border-white/20">تعرف علينا</span>
        <h1 class="text-4xl md:text-5xl font-black text-white mb-4">من نحن</h1>
        <p class="text-white/70 text-lg max-w-2xl mx-auto">معهد INSEP هو المعهد الرائد في مجال علوم الرياضة في الشرق الأوسط والعالم العربي</p>
    </div>
</section>

{{-- About Content --}}
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <span class="inline-block bg-red-brand/10 text-red-brand px-4 py-1.5 rounded-full text-sm font-bold mb-4">قصتنا</span>
                <h2 class="text-3xl md:text-4xl font-black text-navy mb-6">نحو ريادة علوم الرياضة في المنطقة</h2>
                <p class="text-gray-600 leading-relaxed mb-6 text-lg">تأسس معهد INSEP لعلوم الرياضة بهدف سد الفجوة في مجال التعليم والتدريب الرياضي المتخصص في المنطقة العربية.</p>
                <p class="text-gray-600 leading-relaxed mb-8">منذ تأسيسنا، قدمنا أكثر من 5,000 برنامج تدريبي لأكثر من 20,000 متدرب من مختلف أنحاء الشرق الأوسط.</p>
                <div class="flex flex-wrap gap-4">
                    @foreach(['شهادات معتمدة دولياً', 'مدربون متخصصون', 'محتوى علمي حديث'] as $item)
                    <div class="flex items-center gap-2 text-navy font-bold">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $item }}
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="relative">
                <div class="bg-gradient-to-br from-navy to-navy-light rounded-3xl p-10 relative overflow-hidden">
                    <div class="absolute inset-0 hero-pattern opacity-20"></div>
                    <div class="relative z-10 grid grid-cols-2 gap-6">
                        @foreach([
                            ['num' => '+20,000', 'label' => 'متدرب'],
                            ['num' => '+5,000', 'label' => 'دورة تدريبية'],
                            ['num' => '+150', 'label' => 'مدرب معتمد'],
                            ['num' => '+15', 'label' => 'دولة'],
                        ] as $stat)
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-5 text-center border border-white/10">
                            <div class="text-2xl font-black text-white" style="font-family: 'Roboto', sans-serif">{{ $stat['num'] }}</div>
                            <div class="text-white/60 text-sm mt-1">{{ $stat['label'] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="absolute -bottom-4 -right-4 w-full h-full bg-red-brand/10 rounded-3xl -z-10"></div>
            </div>
        </div>
    </div>
</section>

{{-- Vision, Mission, Goals --}}
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-14">
            <span class="inline-block bg-navy/10 text-navy px-4 py-1.5 rounded-full text-sm font-bold mb-4">هويتنا</span>
            <h2 class="text-3xl md:text-4xl font-black text-navy">رؤيتنا ورسالتنا وأهدافنا</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([
                ['title' => 'رؤيتنا', 'desc' => 'أن نكون المعهد الرائد والمرجع الأول في علوم الرياضة على مستوى الشرق الأوسط والعالم العربي، ونساهم في بناء منظومة رياضية متكاملة تعتمد على العلم والبحث.', 'color' => 'from-navy to-navy-light', 'bg' => 'bg-blue-50'],
                ['title' => 'رسالتنا', 'desc' => 'تقديم برامج تدريبية احترافية معتمدة دولياً تساهم في تطوير الكوادر الرياضية وفق أحدث المعايير العلمية، مع توفير بيئة تعليمية محفزة ومبتكرة.', 'color' => 'from-red-brand to-red-brand-dark', 'bg' => 'bg-red-50'],
                ['title' => 'أهدافنا', 'desc' => 'بناء جيل متميز من المتخصصين في علوم الرياضة، نشر ثقافة التدريب العلمي، تعزيز الشراكات الدولية، وتطوير البحث العلمي في المجال الرياضي.', 'color' => 'from-yellow-500 to-yellow-600', 'bg' => 'bg-yellow-50'],
            ] as $item)
            <div class="{{ $item['bg'] }} rounded-2xl p-8 card-hover border border-transparent">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br {{ $item['color'] }} flex items-center justify-center mb-6 shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                </div>
                <h3 class="text-2xl font-bold text-navy mb-4">{{ $item['title'] }}</h3>
                <p class="text-gray-600 leading-relaxed">{{ $item['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Team --}}
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-14">
            <span class="inline-block bg-red-brand/10 text-red-brand px-4 py-1.5 rounded-full text-sm font-bold mb-4">فريقنا</span>
            <h2 class="text-3xl md:text-4xl font-black text-navy mb-4">نخبة من الخبراء والمتخصصين</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['name' => 'د. أحمد المحمد', 'role' => 'المدير التنفيذي', 'specialty' => 'إدارة رياضية', 'rating' => 5.0],
                ['name' => 'د. سارة الأحمد', 'role' => 'مدرب أول', 'specialty' => 'التدريب الرياضي', 'rating' => 4.9],
                ['name' => 'د. عمر الخالد', 'role' => 'مدرب أول', 'specialty' => 'التغذية الرياضية', 'rating' => 4.7],
                ['name' => 'د. ليلى المنصور', 'role' => 'مدربة', 'specialty' => 'العلاج الطبيعي', 'rating' => 4.8],
            ] as $member)
            <div class="bg-white rounded-2xl p-6 border border-gray-100 card-hover text-center group">
                <div class="w-24 h-24 mx-auto mb-5 bg-gradient-to-br from-navy to-navy-light rounded-2xl flex items-center justify-center text-white font-black text-3xl shadow-xl group-hover:scale-105 transform transition-transform duration-500">
                    {{ mb_substr($member['name'], 2, 1) }}
                </div>
                <h3 class="text-lg font-bold text-navy mb-1">{{ $member['name'] }}</h3>
                <p class="text-red-brand font-semibold text-sm mb-1">{{ $member['role'] }}</p>
                <p class="text-gray-500 text-sm mb-3">{{ $member['specialty'] }}</p>
                <div class="flex items-center justify-center gap-1">
                    @for($s = 1; $s <= 5; $s++)
                    <svg class="w-3.5 h-3.5 {{ $s <= floor($member['rating']) ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300' }}" viewBox="0 0 24 24"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                    @endfor
                    <span class="text-sm text-gray-500 mr-1">{{ $member['rating'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="bg-gradient-to-br from-red-brand to-red-brand-dark py-16 relative overflow-hidden">
    <div class="absolute inset-0 hero-pattern opacity-20"></div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <h2 class="text-3xl font-black text-white mb-4">انضم إلى مجتمعنا الرياضي اليوم</h2>
        <p class="text-white/80 mb-8 max-w-xl mx-auto">سجل الآن وابدأ رحلتك المهنية في عالم علوم الرياضة</p>
        <a href="{{ route('login') }}" class="bg-white text-red-brand px-8 py-4 rounded-xl font-bold text-lg hover:shadow-xl transition-all hover:scale-105 transform inline-flex items-center gap-2">
            سجل الآن
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
        </a>
    </div>
</section>
@endsection
