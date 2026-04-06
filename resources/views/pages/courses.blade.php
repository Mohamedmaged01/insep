@extends('layouts.app')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
@section('title', 'INSEP PRO - ' . ($isAr ? 'البرامج التدريبية' : 'Training Programs'))

@section('content')
{{-- Hero --}}
<section class="bg-gradient-to-br from-navy via-navy-light to-navy-dark py-20 relative overflow-hidden">
    <div class="absolute inset-0 hero-pattern opacity-30"></div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <span class="inline-block bg-white/10 text-white px-4 py-1.5 rounded-full text-sm font-bold mb-4 border border-white/20">{{ $isAr ? 'أكثر من 5,000 دورة تدريبية' : 'Over 5,000 Training Courses' }}</span>
        <h1 class="text-4xl md:text-5xl font-black text-white mb-4">{{ $isAr ? 'البرامج التدريبية' : 'Training Programs' }}</h1>
        <p class="text-white/70 text-lg max-w-2xl mx-auto mb-8">{{ $isAr ? 'اكتشف مجموعة واسعة من البرامج التدريبية المعتمدة في مجال علوم الرياضة' : 'Discover a broad range of accredited training programs in sports science' }}</p>
        <form action="{{ route('courses') }}" method="GET" class="max-w-xl mx-auto relative">
            <svg class="w-5 h-5 absolute right-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ $isAr ? 'ابحث عن دورة...' : 'Search for a course...' }}" class="w-full bg-white rounded-xl pr-12 pl-4 py-4 text-lg shadow-xl focus:ring-2 focus:ring-red-brand">
        </form>
    </div>
</section>

{{-- Content --}}
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        @php
            $categories = $isAr
                ? ['الكل', 'التدريب الرياضي', 'العلاج الطبيعي', 'التغذية الرياضية', 'الإدارة الرياضية']
                : ['All', 'Sports Coaching', 'Physiotherapy', 'Sports Nutrition', 'Sports Management'];
            $catValues = ['', 'التدريب الرياضي', 'العلاج الطبيعي', 'التغذية الرياضية', 'الإدارة الرياضية'];
        @endphp
        <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
            <div class="flex flex-wrap gap-2">
                @foreach($categories as $idx => $cat)
                @php $val = $catValues[$idx]; @endphp
                <a href="{{ route('courses', array_merge(request()->query(), ['category' => $val])) }}"
                   class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ (request('category', '') === $val) ? 'bg-navy text-white shadow-lg' : 'bg-white text-navy border border-gray-200 hover:border-navy' }}">
                    {{ $cat }}
                </a>
                @endforeach
            </div>
        </div>

        <p class="text-gray-500 mb-6">{{ $isAr ? 'عرض' : 'Showing' }} {{ $courses->count() }} {{ $isAr ? 'دورة' : 'courses' }}</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($courses as $i => $course)
            <div class="bg-white rounded-2xl overflow-hidden card-hover border border-gray-100 opacity-0 animate-fadeInUp" style="animation-delay: {{ $i * 0.08 }}s; animation-fill-mode: forwards">
                <div class="relative h-48 overflow-hidden group">
                    <img src="{{ $course->image ? (str_starts_with($course->image, 'http') ? $course->image : asset('storage/' . $course->image)) : 'https://picsum.photos/seed/course' . $course->id . '/800/400' }}" alt="{{ $course->title }}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                    <div class="absolute top-4 right-4 bg-navy/80 backdrop-blur-sm text-white px-3 py-1 rounded-lg text-xs font-bold shadow-sm">{{ $course->category }}</div>
                    <div class="absolute top-4 left-4 bg-red-brand/90 text-white px-3 py-1 rounded-lg text-xs font-bold shadow-sm">{{ $course->level }}</div>
                </div>
                <div class="p-6">
                    <a href="{{ route('course.detail', $course->id) }}" class="block">
                        <h3 class="text-lg font-bold text-navy mb-2 hover:text-red-brand transition-colors">{{ $course->title }}</h3>
                    </a>
                    <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $course->description }}</p>
                    <p class="text-xs text-gray-400 mb-4 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-red-brand flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        {{ $isAr ? 'شهادة احترافية معتمدة — فيديوهات + قراءات + اختبارات' : 'Professional Accredited Certificate — Videos + Readings + Exams' }}
                    </p>
                    <div class="flex items-center gap-4 text-sm text-gray-500 mb-4">
                        <span class="flex items-center gap-1">{{ $course->duration ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <span class="text-xl font-black text-red-brand" style="font-family: 'Roboto', sans-serif">{{ number_format($course->price ?? 0) }} <span class="text-sm font-medium">USD</span></span>
                        <div class="flex gap-2">
                            <a href="{{ route('course.detail', $course->id) }}" class="border border-navy text-navy hover:bg-navy hover:text-white px-3 py-2 rounded-xl text-xs font-bold transition-all duration-300">
                                {{ $isAr ? 'التفاصيل' : 'Details' }}
                            </a>
                            <a href="{{ route('register') }}" class="bg-navy hover:bg-navy-dark text-white px-4 py-2 rounded-xl text-xs font-bold transition-all duration-300">
                                {{ $isAr ? 'سجل الآن' : 'Enroll' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-20 col-span-3">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
                <h3 class="text-xl font-bold text-gray-400">{{ $isAr ? 'لم يتم العثور على نتائج' : 'No Results Found' }}</h3>
                <p class="text-gray-400 mt-2">{{ $isAr ? 'حاول تغيير معايير البحث أو الفلترة' : 'Try changing your search or filter criteria' }}</p>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
