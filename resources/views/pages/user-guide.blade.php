@extends('layouts.app')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
@section('title', $isAr ? 'دليل استخدام المنصة - INSEP PRO' : 'User Guide - INSEP PRO')

@section('content')
<section class="bg-navy py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-black text-white mb-3">{{ $isAr ? 'دليل استخدام المنصة' : 'User Guide' }}</h1>
        <p class="text-white/60 text-lg">{{ $isAr ? 'كل ما تحتاجه لاستخدام منصة INSEP PRO بكفاءة' : 'Everything you need to use INSEP PRO efficiently' }}</p>
    </div>
</section>

<section class="bg-gray-50 py-16">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
            @php
                $content = $isAr
                    ? ($settings['user_guide_ar'] ?? '')
                    : ($settings['user_guide_en'] ?? '');
            @endphp

            @if($content)
                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed whitespace-pre-line">
                    {!! nl2br(e($content)) !!}
                </div>
            @else
                <div class="text-center py-16 text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <p class="text-lg font-medium">{{ $isAr ? 'سيتم إضافة دليل الاستخدام قريباً' : 'User guide will be added soon' }}</p>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
