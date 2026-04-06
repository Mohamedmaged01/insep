@extends('layouts.app')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
@section('title', 'INSEP PRO - ' . ($isAr ? 'من نحن' : 'About Us'))

@section('content')
{{-- Hero --}}
<section class="bg-gradient-to-br from-navy via-navy-light to-navy-dark py-20 relative overflow-hidden">
    <div class="absolute inset-0 hero-pattern opacity-30"></div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <span class="inline-block bg-white/10 text-white px-4 py-1.5 rounded-full text-sm font-bold mb-4 border border-white/20">{{ $isAr ? 'تعرف علينا' : 'Get to Know Us' }}</span>
        <h1 class="text-4xl md:text-5xl font-black text-white mb-4">{{ $isAr ? 'من نحن' : 'About Us' }}</h1>
        <p class="text-white/70 text-lg max-w-2xl mx-auto">{{ $isAr ? 'معهد INSEP PRO — الاسم الأول في علوم الرياضة بمنطقة الشرق الأوسط وشمال أفريقيا منذ أكثر من 20 عامًا' : 'INSEP PRO Institute — The leading name in sports science across MENA for over 20 years' }}</p>
    </div>
</section>

{{-- About Content --}}
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <span class="inline-block bg-red-brand/10 text-red-brand px-4 py-1.5 rounded-full text-sm font-bold mb-4">{{ $isAr ? 'قصتنا' : 'Our Story' }}</span>
                <h2 class="text-3xl md:text-4xl font-black text-navy mb-6">{{ $isAr ? 'ريادة علوم الرياضة في المنطقة العربية' : 'Leading Sports Science Across the Arab World' }}</h2>
                <p class="text-gray-600 leading-relaxed mb-5 text-lg">
                    {{ $isAr
                        ? 'معهد INSEP PRO هو المعهد الرائد في مجال علوم الرياضة في منطقة الشرق الأوسط وشمال أفريقيا. تأسس المعهد منذ أكثر من 20 عامًا برؤية واضحة: تطوير الكوادر الرياضية العربية وفق أحدث المعايير العلمية والعالمية.'
                        : 'INSEP PRO Institute is the leading sports science institute in the Middle East and North Africa region. Founded over 20 years ago with a clear vision: to develop Arab sports professionals according to the latest scientific and global standards.' }}
                </p>
                <p class="text-gray-600 leading-relaxed mb-5">
                    {{ $isAr
                        ? 'يتميز المعهد بحصوله على اعتماد ERASMUS الأوروبي، وهو مسجل في السجل الأوروبي للمهنيين الرياضيين EREPS، مما يجعل شهاداته معترفًا بها دوليًا في أكثر من 15 دولة حول العالم.'
                        : 'The institute is distinguished by holding ERASMUS European accreditation and is registered in the European Register of Exercise Professionals (EREPS), making its certificates internationally recognized in more than 15 countries worldwide.' }}
                </p>
                <p class="text-gray-600 leading-relaxed mb-8">
                    {{ $isAr
                        ? 'منذ تأسيسنا، أطلقنا آلاف البرامج التدريبية وخرّجنا عشرات الآلاف من المتدربين في مجالات التدريب الرياضي، العلاج الطبيعي، التغذية الرياضية، والإدارة الرياضية.'
                        : 'Since our founding, we have launched thousands of training programs and graduated tens of thousands of trainees in sports coaching, physiotherapy, sports nutrition, and sports management.' }}
                </p>
                <div class="flex flex-wrap gap-4">
                    @foreach($isAr
                        ? ['اعتماد ERASMUS الأوروبي', 'مسجل في EREPS', 'أكثر من 15 دولة', 'أكثر من 20 عامًا من الخبرة']
                        : ['ERASMUS European Accreditation', 'Registered in EREPS', '15+ Countries', '20+ Years of Experience']
                    as $item)
                    <div class="flex items-center gap-2 text-navy font-bold text-sm">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
                            ['num' => '+20,000', 'label_ar' => 'متدرب مؤهَّل',     'label_en' => 'Qualified Trainees'],
                            ['num' => '+5,000',  'label_ar' => 'دورة تدريبية',      'label_en' => 'Training Programs'],
                            ['num' => '+150',    'label_ar' => 'مدرب معتمد',        'label_en' => 'Certified Trainers'],
                            ['num' => '+15',     'label_ar' => 'دولة حول العالم',   'label_en' => 'Countries Worldwide'],
                        ] as $stat)
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-5 text-center border border-white/10">
                            <div class="text-2xl font-black text-white" style="font-family: 'Roboto', sans-serif">{{ $stat['num'] }}</div>
                            <div class="text-white/60 text-sm mt-1">{{ $isAr ? $stat['label_ar'] : $stat['label_en'] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="absolute -bottom-4 -right-4 w-full h-full bg-red-brand/10 rounded-3xl -z-10"></div>
            </div>
        </div>
    </div>
</section>

{{-- Accreditations --}}
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="inline-block bg-navy/10 text-navy px-4 py-1.5 rounded-full text-sm font-bold mb-4">{{ $isAr ? 'اعتماداتنا' : 'Our Accreditations' }}</span>
            <h2 class="text-3xl font-black text-navy">{{ $isAr ? 'معتمدون دولياً' : 'Internationally Accredited' }}</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                [
                    'title_ar' => 'اعتماد ERASMUS',
                    'title_en' => 'ERASMUS Accreditation',
                    'desc_ar'  => 'حاصل على اعتماد البرنامج الأوروبي ERASMUS مما يتيح تبادل المعرفة والخبرات مع المؤسسات الأوروبية الرائدة في علوم الرياضة.',
                    'desc_en'  => 'Holder of ERASMUS European Program accreditation, enabling knowledge and expertise exchange with leading European sports science institutions.',
                    'color'    => 'bg-blue-50', 'icon_color' => 'from-blue-500 to-blue-600',
                ],
                [
                    'title_ar' => 'السجل الأوروبي EREPS',
                    'title_en' => 'EREPS European Register',
                    'desc_ar'  => 'مسجل في السجل الأوروبي للمهنيين الرياضيين EREPS، مما يمنح خريجينا اعترافًا مهنيًا دوليًا في أوروبا وحول العالم.',
                    'desc_en'  => 'Registered in the European Register of Exercise Professionals (EREPS), granting our graduates international professional recognition across Europe and worldwide.',
                    'color'    => 'bg-green-50', 'icon_color' => 'from-green-500 to-green-600',
                ],
                [
                    'title_ar' => 'حضور في +15 دولة',
                    'title_en' => 'Present in 15+ Countries',
                    'desc_ar'  => 'نعمل في أكثر من 15 دولة في منطقة الشرق الأوسط وشمال أفريقيا وأوروبا، مع شراكات استراتيجية مع مؤسسات دولية كبرى.',
                    'desc_en'  => 'Operating in more than 15 countries across MENA and Europe, with strategic partnerships with major international institutions.',
                    'color'    => 'bg-red-50', 'icon_color' => 'from-red-brand to-red-brand-dark',
                ],
            ] as $item)
            <div class="{{ $item['color'] }} rounded-2xl p-8 border border-transparent card-hover">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br {{ $item['icon_color'] }} flex items-center justify-center mb-6 shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-navy mb-3">{{ $isAr ? $item['title_ar'] : $item['title_en'] }}</h3>
                <p class="text-gray-600 leading-relaxed text-sm">{{ $isAr ? $item['desc_ar'] : $item['desc_en'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Vision, Mission, Goals --}}
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-14">
            <span class="inline-block bg-navy/10 text-navy px-4 py-1.5 rounded-full text-sm font-bold mb-4">{{ $isAr ? 'هويتنا' : 'Our Identity' }}</span>
            <h2 class="text-3xl md:text-4xl font-black text-navy">{{ $isAr ? 'رؤيتنا ورسالتنا وأهدافنا' : 'Vision, Mission & Goals' }}</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([
                [
                    'title_ar' => 'رؤيتنا',   'title_en' => 'Our Vision',
                    'desc_ar'  => 'أن يكون معهد INSEP PRO المرجع الأول والاختيار الأمثل لكل من يسعى إلى الاحتراف في علوم الرياضة في المنطقة العربية والعالم، من خلال تقديم تعليم رياضي متكامل يُبنى على الأدلة العلمية ويواكب التطورات الدولية.',
                    'desc_en'  => 'For INSEP PRO to be the first reference and optimal choice for anyone seeking professionalism in sports science in the Arab region and the world, through comprehensive evidence-based sports education that keeps pace with international developments.',
                    'color' => 'from-navy to-navy-light', 'bg' => 'bg-blue-50',
                ],
                [
                    'title_ar' => 'رسالتنا',  'title_en' => 'Our Mission',
                    'desc_ar'  => 'تمكين الكوادر الرياضية العربية من خلال برامج تدريبية احترافية معتمدة دولياً، تُسهم في رفع مستوى الأداء الرياضي وتطوير صناعة الرياضة في المنطقة، في إطار بيئة تعليمية محفزة تجمع بين العلم والتطبيق.',
                    'desc_en'  => 'Empowering Arab sports professionals through internationally accredited training programs that contribute to raising sports performance levels and developing the sports industry in the region, within a motivating educational environment that combines science and practice.',
                    'color' => 'from-red-brand to-red-brand-dark', 'bg' => 'bg-red-50',
                ],
                [
                    'title_ar' => 'أهدافنا',  'title_en' => 'Our Goals',
                    'desc_ar'  => 'بناء جيل متميز من المتخصصين في علوم الرياضة، نشر ثقافة التدريب العلمي المبني على الأدلة، تعزيز الشراكات الدولية مع المؤسسات الأكاديمية، وتطوير البحث العلمي في المجال الرياضي بالمنطقة العربية.',
                    'desc_en'  => 'Building a distinguished generation of sports science specialists, spreading evidence-based scientific training culture, strengthening international partnerships with academic institutions, and advancing sports science research across the Arab region.',
                    'color' => 'from-yellow-500 to-yellow-600', 'bg' => 'bg-yellow-50',
                ],
            ] as $item)
            <div class="{{ $item['bg'] }} rounded-2xl p-8 card-hover border border-transparent">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br {{ $item['color'] }} flex items-center justify-center mb-6 shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                </div>
                <h3 class="text-2xl font-bold text-navy mb-4">{{ $isAr ? $item['title_ar'] : $item['title_en'] }}</h3>
                <p class="text-gray-600 leading-relaxed">{{ $isAr ? $item['desc_ar'] : $item['desc_en'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Specializations --}}
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="inline-block bg-red-brand/10 text-red-brand px-4 py-1.5 rounded-full text-sm font-bold mb-4">{{ $isAr ? 'تخصصاتنا' : 'Our Specializations' }}</span>
            <h2 class="text-3xl font-black text-navy">{{ $isAr ? 'مجالات التدريب والتخصص' : 'Training & Specialization Fields' }}</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach([
                ['ar' => 'التدريب الرياضي',    'en' => 'Sports Coaching',    'color' => 'bg-blue-500'],
                ['ar' => 'العلاج الطبيعي',     'en' => 'Physiotherapy',       'color' => 'bg-green-500'],
                ['ar' => 'التغذية الرياضية',   'en' => 'Sports Nutrition',    'color' => 'bg-orange-500'],
                ['ar' => 'الإدارة الرياضية',   'en' => 'Sports Management',   'color' => 'bg-purple-500'],
                ['ar' => 'اللياقة البدنية',    'en' => 'Physical Fitness',    'color' => 'bg-red-500'],
                ['ar' => 'علم الحركة',          'en' => 'Kinesiology',         'color' => 'bg-teal-500'],
                ['ar' => 'علم النفس الرياضي',  'en' => 'Sports Psychology',   'color' => 'bg-pink-500'],
                ['ar' => 'التحليل الرياضي',    'en' => 'Sports Analytics',    'color' => 'bg-indigo-500'],
            ] as $spec)
            <div class="bg-white rounded-2xl p-5 border border-gray-100 card-hover flex items-center gap-3">
                <div class="w-3 h-3 rounded-full {{ $spec['color'] }} flex-shrink-0"></div>
                <span class="font-semibold text-navy text-sm">{{ $isAr ? $spec['ar'] : $spec['en'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="bg-gradient-to-br from-red-brand to-red-brand-dark py-16 relative overflow-hidden">
    <div class="absolute inset-0 hero-pattern opacity-20"></div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <h2 class="text-3xl font-black text-white mb-4">{{ $isAr ? 'انضم إلى مجتمعنا الرياضي اليوم' : 'Join Our Sports Community Today' }}</h2>
        <p class="text-white/80 mb-8 max-w-xl mx-auto">{{ $isAr ? 'سجل الآن وابدأ رحلتك المهنية في عالم علوم الرياضة مع شهادة معترف بها دوليًا' : 'Register now and start your professional journey in the world of sports science with an internationally recognized certificate' }}</p>
        <a href="{{ route('register') }}" class="bg-white text-red-brand px-8 py-4 rounded-xl font-bold text-lg hover:shadow-xl transition-all hover:scale-105 transform inline-flex items-center gap-2">
            {{ $isAr ? 'سجل الآن' : 'Register Now' }}
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
        </a>
    </div>
</section>
@endsection
