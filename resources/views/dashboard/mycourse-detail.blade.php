@extends('layouts.dashboard')
@section('title', 'INSEP PRO - ' . ($enrollment->course->title ?? 'دورة'))
@section('dashboard-content')
@php
    $lang = app()->getLocale();
    $isAr = $lang === 'ar';
    $course = $enrollment->course;
    $batch  = $enrollment->batch;
    $resources    = $batch?->resources ?? collect();
    $liveSessions = $batch?->liveSessions ?? collect();
@endphp

{{-- Back --}}
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('dashboard.mycourses') }}" class="p-2 hover:bg-gray-100 rounded-xl transition-colors text-gray-500">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
        <h1 class="text-2xl font-black text-navy">{{ $course->title ?? ($isAr ? 'تفاصيل الدورة' : 'Course Details') }}</h1>
        <p class="text-sm text-gray-500">{{ $batch->name ?? '-' }} &bull; {{ $batch?->instructor?->name ?? '-' }}</p>
    </div>
</div>

{{-- Course Hero --}}
<div class="bg-gradient-to-br from-navy to-navy-light rounded-2xl overflow-hidden mb-6 relative">
    @if($course?->image)
    <img src="{{ str_starts_with($course->image, 'http') ? $course->image : asset('storage/' . ltrim($course->image, '/')) }}"
         class="absolute inset-0 w-full h-full object-cover opacity-20" alt="">
    @endif
    <div class="relative z-10 p-8 flex flex-col md:flex-row gap-6 items-start">
        <div class="flex-1">
            <span class="inline-block bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-lg mb-3">{{ $course->category ?? ($isAr ? 'تدريب' : 'Training') }}</span>
            <h2 class="text-2xl font-black text-white mb-2">{{ $course->title ?? '' }}</h2>
            <p class="text-white/70 text-sm max-w-xl">{{ $course->description ?? '' }}</p>
        </div>
        <div class="flex flex-col gap-3 text-sm flex-shrink-0">
            <div class="bg-white/10 rounded-xl p-4 text-white min-w-[160px]">
                <p class="text-white/60 text-xs mb-1">{{ $isAr ? 'التقدم' : 'Progress' }}</p>
                <div class="flex items-center gap-2">
                    <div class="flex-1 bg-white/20 rounded-full h-2">
                        <div class="bg-white rounded-full h-2" style="width: {{ $enrollment->progress ?? 0 }}%"></div>
                    </div>
                    <span class="font-black" style="font-family:'Roboto',sans-serif">{{ $enrollment->progress ?? 0 }}%</span>
                </div>
            </div>
            <div class="bg-white/10 rounded-xl px-4 py-3 text-white">
                <p class="text-white/60 text-xs mb-0.5">{{ $isAr ? 'الحالة' : 'Status' }}</p>
                <p class="font-bold">{{ ($enrollment->status ?? 'active') === 'active' ? ($isAr ? 'نشط' : 'Active') : $enrollment->status }}</p>
            </div>
            @if($enrollment->grade)
            <div class="bg-white/10 rounded-xl px-4 py-3 text-white">
                <p class="text-white/60 text-xs mb-0.5">{{ $isAr ? 'الدرجة' : 'Grade' }}</p>
                <p class="font-bold">{{ $enrollment->grade }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Stats Row --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-4 border border-gray-100 text-center">
        <p class="text-2xl font-black text-navy">{{ $resources->count() }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'ملفات الحقيبة' : 'Bag Files' }}</p>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-100 text-center">
        <p class="text-2xl font-black text-navy">{{ $liveSessions->count() }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'جلسات مباشرة' : 'Live Sessions' }}</p>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-100 text-center">
        <p class="text-lg font-black text-navy">{{ $batch?->start_date ?? '-' }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'تاريخ البدء' : 'Start Date' }}</p>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-100 text-center">
        <p class="text-lg font-black text-navy">{{ $batch?->end_date ?? '-' }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'تاريخ الانتهاء' : 'End Date' }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Training Bag (Resources) --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
            <svg class="w-5 h-5 text-red-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
            <h2 class="font-black text-navy">{{ $isAr ? 'الحقيبة التدريبية' : 'Training Bag' }}</h2>
            <span class="ml-auto bg-gray-100 text-gray-600 text-xs font-bold px-2 py-1 rounded-lg">{{ $resources->count() }}</span>
        </div>

        @if($resources->count() > 0)
        <div class="divide-y divide-gray-50">
            @foreach($resources as $res)
            @php
                $isLink  = in_array($res->type, ['VideoLink', 'YouTube', 'Vimeo', 'ExternalLink']);
                $isVideo = in_array(strtolower($res->type ?? ''), ['video', 'mp4', 'videolink', 'youtube', 'vimeo']);
            @endphp
            <div class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                    {{ $isVideo ? 'bg-red-100 text-red-brand' : ($res->type === 'PDF' ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600') }}">
                    @if($isVideo)
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                    @elseif($res->type === 'PDF')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    @else
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-navy text-sm truncate">{{ $res->title }}</p>
                    <p class="text-xs text-gray-400">{{ $res->type }}{{ $res->size ? ' · ' . $res->size : '' }}</p>
                </div>
                @if($res->file_url)
                <a href="{{ $res->file_url }}" target="_blank"
                   class="flex items-center gap-1.5 text-xs font-bold px-4 py-2 rounded-xl transition-colors flex-shrink-0
                       {{ $isLink ? 'bg-red-brand text-white hover:bg-red-brand-dark' : 'bg-navy text-white hover:bg-navy-dark' }}">
                    @if($isLink)
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                    {{ $isAr ? 'مشاهدة' : 'Watch' }}
                    @else
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    {{ $isAr ? 'تحميل' : 'Download' }}
                    @endif
                </a>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="p-12 text-center text-gray-400">
            <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
            <p class="font-bold text-sm">{{ $isAr ? 'لا يوجد محتوى في الحقيبة بعد' : 'No content in the bag yet' }}</p>
        </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">

        {{-- Live Sessions --}}
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-red-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <h3 class="font-black text-navy text-sm">{{ $isAr ? 'الجلسات المباشرة' : 'Live Sessions' }}</h3>
            </div>
            @if($liveSessions->count() > 0)
            <div class="divide-y divide-gray-50">
                @foreach($liveSessions as $sess)
                <div class="px-5 py-3">
                    <p class="font-bold text-navy text-sm">{{ $sess->title }}</p>
                    <p class="text-xs text-gray-400 mt-0.5" style="font-family:'Roboto',sans-serif">{{ $sess->scheduled_at ?? '-' }}</p>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-xs font-bold px-2 py-0.5 rounded-lg
                            {{ $sess->status === 'live' ? 'bg-red-100 text-red-700' : ($sess->status === 'ended' ? 'bg-gray-100 text-gray-500' : 'bg-blue-100 text-blue-700') }}">
                            {{ $sess->status === 'live' ? ($isAr ? 'مباشر الآن' : 'Live Now') : ($sess->status === 'ended' ? ($isAr ? 'انتهى' : 'Ended') : ($isAr ? 'قادم' : 'Upcoming')) }}
                        </span>
                        @if($sess->live_url && $sess->status !== 'ended')
                        <a href="{{ $sess->live_url }}" target="_blank" class="text-xs text-blue-500 font-bold hover:underline">{{ $isAr ? 'انضم ←' : 'Join ←' }}</a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-center text-gray-400 text-sm py-6">{{ $isAr ? 'لا توجد جلسات مجدولة' : 'No sessions scheduled' }}</p>
            @endif
        </div>

        {{-- Certificate --}}
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="6"/><path stroke-linecap="round" d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
                <h3 class="font-black text-navy text-sm">{{ $isAr ? 'الشهادة' : 'Certificate' }}</h3>
            </div>
            @if($certificate)
            <div class="p-5 text-center">
                <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-yellow-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-yellow-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="8" r="6"/><path stroke-linecap="round" d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
                </div>
                <p class="font-black text-navy">{{ $certificate->title ?? ($isAr ? 'شهادة إتمام' : 'Completion Certificate') }}</p>
                <p class="text-xs text-gray-400 mt-1" style="font-family:'Roboto',sans-serif">{{ $certificate->serial_number ?? '' }}</p>
                @if($certificate->grade)
                <p class="text-sm font-bold text-green-600 mt-2">{{ $isAr ? 'التقدير:' : 'Grade:' }} {{ $certificate->grade }}</p>
                @endif
                @if($certificate->issue_date)
                <p class="text-xs text-gray-400 mt-1" style="font-family:'Roboto',sans-serif">{{ $certificate->issue_date }}</p>
                @endif
            </div>
            @else
            <div class="p-5 text-center text-gray-400">
                <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="8" r="6"/><path stroke-linecap="round" d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
                <p class="text-sm font-bold">{{ $isAr ? 'لم تصدر الشهادة بعد' : 'Certificate not issued yet' }}</p>
            </div>
            @endif
        </div>

        {{-- Course Info --}}
        @if($course?->level || $course?->duration || $course?->instructor_name)
        <div class="bg-white rounded-2xl border border-gray-100 p-5 space-y-3">
            <h3 class="font-black text-navy text-sm mb-1">{{ $isAr ? 'معلومات الدورة' : 'Course Info' }}</h3>
            @if($course->level)
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">{{ $isAr ? 'المستوى' : 'Level' }}</span>
                <span class="font-bold text-navy">{{ $course->level }}</span>
            </div>
            @endif
            @if($course->duration)
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">{{ $isAr ? 'المدة' : 'Duration' }}</span>
                <span class="font-bold text-navy">{{ $course->duration }}</span>
            </div>
            @endif
            @if($batch?->instructor?->name)
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">{{ $isAr ? 'المدرب' : 'Instructor' }}</span>
                <span class="font-bold text-navy">{{ $batch->instructor->name }}</span>
            </div>
            @endif
        </div>
        @endif

    </div>
</div>
@endsection
