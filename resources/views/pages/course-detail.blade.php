@extends('layouts.app')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
@section('title', 'INSEP PRO - ' . $course->title)

@section('content')
{{-- Hero --}}
<section class="bg-gradient-to-br from-navy via-navy-light to-navy-dark py-16 relative overflow-hidden">
    <div class="absolute inset-0 hero-pattern opacity-30"></div>
    <div class="container mx-auto px-4 relative z-10">
        <div class="flex items-center gap-2 text-white/60 text-sm mb-4">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">{{ $isAr ? 'الرئيسية' : 'Home' }}</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('courses') }}" class="hover:text-white transition-colors">{{ $isAr ? 'البرامج' : 'Courses' }}</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            <span class="text-white/90">{{ Str::limit($course->title, 40) }}</span>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">
            <div class="lg:col-span-2">
                <div class="flex flex-wrap gap-2 mb-3">
                    @if($course->category)
                    <span class="inline-block bg-white/10 text-white px-3 py-1 rounded-full text-xs font-bold border border-white/20">{{ $course->category }}</span>
                    @endif
                    @if($course->is_featured)
                    <span class="inline-flex items-center gap-1 bg-yellow-500/20 text-yellow-300 px-3 py-1 rounded-full text-xs font-bold border border-yellow-400/30">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        {{ $isAr ? 'دورة مميزة' : 'Featured' }}
                    </span>
                    @endif
                </div>
                <h1 class="text-3xl md:text-4xl font-black text-white mb-4">{{ $course->title }}</h1>
                <p class="text-white/70 text-lg mb-6">{{ $course->description }}</p>
                <div class="flex flex-wrap gap-5 text-white/80 text-sm">
                    @if($course->duration)
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        {{ $course->duration }}
                    </span>
                    @endif
                    @if($course->level)
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 20V10M18 20V4M6 20v-4"/></svg>
                        {{ $course->level }}
                    </span>
                    @endif
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        {{ $isAr ? 'شهادة معتمدة دولياً' : 'Internationally Accredited Certificate' }}
                    </span>
                </div>
            </div>
            {{-- Enrollment Card --}}
            <div class="bg-white rounded-2xl p-6 shadow-2xl">
                @if($course->image)
                <img src="{{ str_starts_with($course->image, 'http') ? $course->image : asset('storage/' . ltrim($course->image, '/')) }}"
                     alt="{{ $course->title }}" class="w-full h-40 object-cover rounded-xl mb-5"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                <div class="w-full h-40 bg-gradient-to-br from-navy to-navy-light rounded-xl mb-5 items-center justify-center" style="display:none">
                    <svg class="w-12 h-12 text-white/40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                @else
                <div class="w-full h-40 bg-gradient-to-br from-navy to-navy-light rounded-xl mb-5 flex items-center justify-center">
                    <svg class="w-12 h-12 text-white/40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                @endif
                <div class="text-3xl font-black text-red-brand mb-1" style="font-family: 'Roboto', sans-serif">
                    {{ number_format($course->price ?? 0) }} <span class="text-base font-medium text-gray-500">{{ $course->currency ?? 'USD' }}</span>
                </div>
                <p class="text-xs text-gray-400 mb-5">{{ $isAr ? 'شامل جميع المواد والشهادة' : 'Includes all materials and certificate' }}</p>
                <a href="{{ route('register') }}"
                   class="block w-full bg-red-brand hover:bg-red-brand-dark text-white text-center py-4 rounded-xl font-bold text-lg transition-all duration-300 hover:shadow-lg hover:shadow-red-brand/30 mb-3">
                    {{ $isAr ? 'سجل الآن' : 'Enroll Now' }}
                </a>
                <a href="{{ route('contact') }}"
                   class="block w-full border-2 border-navy text-navy text-center py-3 rounded-xl font-bold transition-all duration-300 hover:bg-navy hover:text-white text-sm">
                    {{ $isAr ? 'تواصل معنا' : 'Contact Us' }}
                </a>
                @if($course->features)
                @php $featList = array_filter(array_map('trim', explode("\n", $course->features))); @endphp
                <ul class="mt-5 space-y-2 text-sm text-gray-500">
                    @foreach(array_slice($featList, 0, 5) as $feat)
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        {{ $feat }}
                    </li>
                    @endforeach
                </ul>
                @else
                <ul class="mt-5 space-y-2 text-sm text-gray-500">
                    @foreach($isAr
                        ? ['دورة تدريبية متكاملة', 'مقاطع فيديو + قراءات', 'اختبارات تفاعلية', 'شهادة معتمدة دولياً', 'دعم مستمر من المدربين']
                        : ['Comprehensive Training Course', 'Video + Reading Materials', 'Interactive Exams', 'Internationally Accredited Certificate', 'Ongoing Trainer Support']
                    as $feature)
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        {{ $feature }}
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Promo Video --}}
@if($course->promo_video)
<section class="py-12 bg-navy/5">
    <div class="container mx-auto px-4 max-w-3xl text-center">
        <h2 class="text-2xl font-black text-navy mb-6">{{ $isAr ? 'فيديو تعريفي بالدورة' : 'Course Preview' }}</h2>
        <div class="relative rounded-2xl overflow-hidden shadow-2xl" style="padding-top: 56.25%">
            <iframe class="absolute inset-0 w-full h-full"
                src="{{ $course->promo_video }}"
                title="{{ $course->title }}"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
        </div>
    </div>
</section>
@endif

{{-- Course Content --}}
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2 space-y-10">

                {{-- Features / What you'll learn --}}
                @if($course->features)
                @php $feats = array_filter(array_map('trim', explode("\n", $course->features))); @endphp
                <div>
                    <h2 class="text-2xl font-black text-navy mb-6">{{ $isAr ? 'مميزات الدورة' : 'Course Features' }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($feats as $feat)
                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-sm text-gray-700">{{ $feat }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div>
                    <h2 class="text-2xl font-black text-navy mb-6">{{ $isAr ? 'ماذا ستتعلم في هذه الدورة؟' : 'What Will You Learn?' }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($isAr ? [
                            'أسس ومبادئ التدريب الرياضي العلمي',
                            'تطبيق أحدث الأبحاث في الميدان الرياضي',
                            'تقييم مستوى الأداء الرياضي',
                            'تصميم برامج تدريبية متكاملة',
                            'إدارة الجلسات التدريبية باحترافية',
                            'الحصول على شهادة معتمدة دولياً',
                        ] : [
                            'Foundations and principles of scientific sports training',
                            'Applying the latest research in the sports field',
                            'Evaluating sports performance levels',
                            'Designing comprehensive training programs',
                            'Managing training sessions professionally',
                            'Obtaining an internationally accredited certificate',
                        ] as $obj)
                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-sm text-gray-700">{{ $obj }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Content / Curriculum --}}
                @if($course->content)
                @php
                    $lines = array_filter(array_map('trim', explode("\n", $course->content)));
                @endphp
                <div>
                    <h2 class="text-2xl font-black text-navy mb-6">{{ $isAr ? 'محتوى الدورة التدريبية' : 'Course Curriculum' }}</h2>
                    <div class="space-y-3">
                        @foreach(array_values($lines) as $idx => $line)
                        <div class="flex items-center gap-3 bg-gray-50 rounded-xl px-5 py-4 border border-gray-100">
                            <div class="w-8 h-8 rounded-lg bg-navy text-white flex items-center justify-center text-sm font-bold flex-shrink-0">{{ $idx + 1 }}</div>
                            <span class="font-medium text-navy text-sm">{{ $line }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Accreditation --}}
                @if($course->accreditation)
                <div>
                    <h2 class="text-2xl font-black text-navy mb-6">{{ $isAr ? 'الاعتماد والشهادة' : 'Accreditation & Certificate' }}</h2>
                    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 flex gap-4 items-start">
                        <div class="w-12 h-12 rounded-xl bg-navy flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        </div>
                        <div class="text-gray-700 leading-relaxed whitespace-pre-line text-sm">{{ $course->accreditation }}</div>
                    </div>
                </div>
                @endif

                {{-- Job Opportunities --}}
                @if($course->job_opportunities)
                @php $jobs = array_filter(array_map('trim', explode("\n", $course->job_opportunities))); @endphp
                <div>
                    <h2 class="text-2xl font-black text-navy mb-6">{{ $isAr ? 'فرص العمل المتاحة' : 'Career Opportunities' }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($jobs as $job)
                        <div class="flex items-start gap-3 p-3 bg-green-50 rounded-xl border border-green-100">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span class="text-sm text-gray-700">{{ $job }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>

            {{-- Sticky sidebar info (desktop) --}}
            <div class="hidden lg:block">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 sticky top-24">
                    <h3 class="font-bold text-navy mb-4">{{ $isAr ? 'تفاصيل الدورة' : 'Course Details' }}</h3>
                    <ul class="space-y-3 text-sm">
                        @if($course->duration)
                        <li class="flex items-center justify-between">
                            <span class="text-gray-500">{{ $isAr ? 'المدة' : 'Duration' }}</span>
                            <span class="font-semibold text-navy">{{ $course->duration }}</span>
                        </li>
                        @endif
                        @if($course->level)
                        <li class="flex items-center justify-between">
                            <span class="text-gray-500">{{ $isAr ? 'المستوى' : 'Level' }}</span>
                            <span class="font-semibold text-navy">{{ $course->level }}</span>
                        </li>
                        @endif
                        @if($course->category)
                        <li class="flex items-center justify-between">
                            <span class="text-gray-500">{{ $isAr ? 'التصنيف' : 'Category' }}</span>
                            <span class="font-semibold text-navy">{{ $course->category }}</span>
                        </li>
                        @endif
                        <li class="flex items-center justify-between">
                            <span class="text-gray-500">{{ $isAr ? 'اللغة' : 'Language' }}</span>
                            <span class="font-semibold text-navy">{{ $isAr ? 'العربية' : 'Arabic' }}</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="text-gray-500">{{ $isAr ? 'الشهادة' : 'Certificate' }}</span>
                            <span class="font-semibold text-green-600">{{ $isAr ? 'معتمدة دولياً' : 'International' }}</span>
                        </li>
                    </ul>
                    <hr class="my-4 border-gray-200">
                    <a href="{{ route('register') }}"
                       class="block w-full bg-red-brand hover:bg-red-brand-dark text-white text-center py-3.5 rounded-xl font-bold transition-all duration-300">
                        {{ $isAr ? 'سجل الآن' : 'Enroll Now' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Related Courses --}}
@if($related->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-black text-navy mb-8">{{ $isAr ? 'دورات ذات صلة' : 'Related Courses' }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($related as $rc)
            <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 card-hover">
                <div class="h-36 overflow-hidden">
                    <img src="{{ $rc->image ? (str_starts_with($rc->image, 'http') ? $rc->image : asset('storage/' . ltrim($rc->image, '/'))) : 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?auto=format&fit=crop&w=800&q=80' }}"
                         alt="{{ $rc->title }}" class="w-full h-full object-cover"
                         onerror="this.src='https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?auto=format&fit=crop&w=800&q=80'">
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-navy mb-2 text-sm">{{ $rc->title }}</h3>
                    <div class="flex items-center justify-between mt-3">
                        <span class="text-red-brand font-black text-sm">{{ number_format($rc->price ?? 0) }} {{ $rc->currency ?? 'USD' }}</span>
                        <a href="{{ route('course.detail', $rc->id) }}" class="text-xs bg-navy text-white px-3 py-1.5 rounded-lg font-bold hover:bg-navy-dark transition-colors">
                            {{ $isAr ? 'عرض' : 'View' }}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
