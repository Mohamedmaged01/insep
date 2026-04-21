@extends('layouts.dashboard')
@section('title', 'INSEP PRO - دوراتي')
@section('dashboard-content')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
<h1 class="text-2xl font-black text-navy mb-6">{{ $isAr ? 'دوراتي المسجلة' : 'My Enrolled Courses' }}</h1>

@if($enrollments->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($enrollments as $enr)
    @php $resourceCount = $enr->batch?->resources?->count() ?? 0; @endphp
    <a href="{{ route('dashboard.mycourses.detail', $enr->id) }}"
       class="bg-white rounded-2xl overflow-hidden border border-gray-100 card-hover block group">
        <div class="h-36 bg-gradient-to-br from-navy to-navy-light relative overflow-hidden">
            <div class="absolute inset-0 hero-pattern opacity-30"></div>
            @if($enr->course?->image)
            <img src="{{ str_starts_with($enr->course->image, 'http') ? $enr->course->image : asset('storage/' . ltrim($enr->course->image, '/')) }}"
                 class="absolute inset-0 w-full h-full object-cover opacity-60" alt="">
            @endif
            <div class="absolute bottom-3 right-3 left-3">
                <p class="text-white font-black text-base drop-shadow line-clamp-2">{{ $enr->course->title ?? ($isAr ? 'دورة' : 'Course') }}</p>
            </div>
            @if($resourceCount > 0)
            <div class="absolute top-3 left-3 bg-red-brand text-white text-xs font-bold px-2 py-1 rounded-lg">
                {{ $resourceCount }} {{ $isAr ? 'ملف' : 'files' }}
            </div>
            @endif
        </div>
        <div class="p-5">
            <p class="text-sm text-gray-500 mb-3">{{ $isAr ? 'المجموعة:' : 'Batch:' }} <span class="font-bold text-navy">{{ $enr->batch->name ?? '-' }}</span></p>

            <div class="mb-3">
                <div class="flex justify-between items-center mb-1">
                    <p class="text-xs text-gray-500">{{ $isAr ? 'التقدم' : 'Progress' }}</p>
                    <p class="text-xs font-bold text-navy" style="font-family:'Roboto',sans-serif">{{ $enr->progress ?? 0 }}%</p>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-navy rounded-full h-2 transition-all" style="width: {{ $enr->progress ?? 0 }}%"></div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <span class="px-3 py-1 rounded-lg text-xs font-bold {{ ($enr->status ?? 'active') === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ ($enr->status ?? 'active') === 'active' ? ($isAr ? 'نشط' : 'Active') : $enr->status }}
                </span>
                <span class="text-xs text-navy font-bold group-hover:underline flex items-center gap-1">
                    {{ $isAr ? 'عرض التفاصيل' : 'View Details' }}
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </span>
            </div>
        </div>
    </a>
    @endforeach
</div>
@else
<div class="bg-white rounded-2xl p-16 border border-gray-100 text-center">
    <div class="w-16 h-16 mx-auto mb-4 bg-navy/10 rounded-2xl flex items-center justify-center">
        <svg class="w-8 h-8 text-navy/40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
    </div>
    <h3 class="font-black text-navy text-lg mb-2">{{ $isAr ? 'لم تسجل في أي دورة بعد' : 'No courses enrolled yet' }}</h3>
    <p class="text-gray-500 text-sm mb-6">{{ $isAr ? 'تصفح الدورات المتاحة وابدأ رحلتك التعليمية' : 'Browse available courses and start your learning journey' }}</p>
    <a href="{{ route('courses') }}" class="bg-navy text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-navy-dark transition-colors inline-block">{{ $isAr ? 'استعرض الدورات' : 'Browse Courses' }}</a>
</div>
@endif
@endsection
