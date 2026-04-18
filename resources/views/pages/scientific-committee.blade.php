@extends('layouts.app')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
@section('title', ($isAr ? 'اللجنة العلمية للمعهد' : 'Scientific Committee') . ' - INSEP PRO')

@section('content')
{{-- Hero --}}
<section class="bg-gradient-to-br from-navy via-navy-light to-navy-dark py-20 relative overflow-hidden">
    <div class="absolute inset-0 hero-pattern opacity-30"></div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <span class="inline-block bg-white/10 text-white px-4 py-1.5 rounded-full text-sm font-bold mb-4 border border-white/20">INSEP</span>
        <h1 class="text-4xl md:text-5xl font-black text-white mb-4">
            {{ $isAr ? 'اللجنة العلمية للمعهد' : 'Scientific Committee' }}
        </h1>
        <p class="text-white/70 text-lg max-w-2xl mx-auto">
            {{ $isAr
                ? 'نخبة من أبرز الأكاديميين والخبراء في علوم الرياضة والتدريب، يشرفون على الجودة العلمية لبرامج المعهد'
                : 'An elite group of leading academics and experts in sports science and coaching, overseeing the scientific quality of the institute\'s programs' }}
        </p>
    </div>
</section>

{{-- Members --}}
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        @if($members->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($members as $i => $member)
            <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 opacity-0 animate-fadeInUp text-center" style="animation-delay: {{ $i * 0.07 }}s; animation-fill-mode: forwards">
                <div class="bg-gradient-to-br from-navy to-navy-light h-4 w-full"></div>
                <div class="p-6">
                    @if($member->image)
                    <img src="{{ str_starts_with($member->image, 'http') ? $member->image : asset('storage/' . ltrim($member->image, '/')) }}"
                         alt="{{ $member->name }}"
                         class="w-28 h-28 rounded-full object-cover mx-auto mb-4 border-4 border-white shadow-lg -mt-10 relative z-10"
                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=1B2B4B&color=fff&size=112'">
                    @else
                    <div class="w-28 h-28 rounded-full bg-gradient-to-br from-navy to-navy-light flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-lg -mt-10 relative z-10 text-white text-3xl font-black">
                        {{ mb_substr($member->name, 0, 1) }}
                    </div>
                    @endif

                    <h3 class="font-black text-navy text-lg mb-1 leading-snug">{{ $member->name }}</h3>

                    @if($member->title)
                    <p class="text-red-brand font-bold text-sm mb-1">{{ $member->title }}</p>
                    @endif

                    @if($member->specialization)
                    <div class="inline-block bg-navy/5 text-navy px-3 py-1 rounded-full text-xs font-semibold mb-3">
                        {{ $member->specialization }}
                    </div>
                    @endif

                    @if($member->bio)
                    <p class="text-gray-500 text-sm leading-relaxed line-clamp-4">{{ $member->bio }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        {{-- Empty state --}}
        <div class="text-center py-20">
            <svg class="w-20 h-20 text-gray-200 mx-auto mb-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
            <h3 class="text-2xl font-black text-gray-300 mb-2">{{ $isAr ? 'سيتم إضافة أعضاء اللجنة قريباً' : 'Committee members will be added soon' }}</h3>
            <p class="text-gray-400">{{ $isAr ? 'نعمل على تجميع بيانات أعضاء هيئة التدريس' : 'We are compiling faculty member data' }}</p>
        </div>
        @endif
    </div>
</section>

{{-- CTA --}}
<section class="py-16 bg-navy">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-black text-white mb-4">
            {{ $isAr ? 'تعلّم على أيدي الخبراء' : 'Learn From the Experts' }}
        </h2>
        <p class="text-white/70 text-lg mb-8 max-w-xl mx-auto">
            {{ $isAr
                ? 'اكتشف برامجنا التدريبية المصممة تحت إشراف اللجنة العلمية'
                : 'Explore our training programs designed under the supervision of the scientific committee' }}
        </p>
        <a href="{{ route('courses') }}"
           class="inline-block bg-red-brand hover:bg-red-brand-dark text-white px-10 py-4 rounded-xl font-black text-lg transition-all duration-300 hover:shadow-xl hover:shadow-red-brand/30">
            {{ $isAr ? 'تصفح البرامج التدريبية' : 'Browse Training Programs' }}
        </a>
    </div>
</section>
@endsection
