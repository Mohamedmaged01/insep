@extends('layouts.app')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
@section('title', $isAr ? 'سياسة المنصة - INSEP PRO' : 'Platform Policy - INSEP PRO')

@section('content')
<section class="bg-navy py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-black text-white mb-3">{{ $isAr ? 'سياسة المنصة' : 'Platform Policy' }}</h1>
        <p class="text-white/60 text-lg">{{ $isAr ? 'الشروط والسياسات المتعلقة باستخدام منصة INSEP PRO' : 'Terms and policies related to using INSEP PRO platform' }}</p>
    </div>
</section>

<section class="bg-gray-50 py-16">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
            @php
                $content = $isAr
                    ? ($settings['platform_policy_ar'] ?? '')
                    : ($settings['platform_policy_en'] ?? '');
            @endphp

            @if($content)
                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed whitespace-pre-line">
                    {!! nl2br(e($content)) !!}
                </div>
            @else
                <div class="text-center py-16 text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p class="text-lg font-medium">{{ $isAr ? 'سيتم إضافة سياسة المنصة قريباً' : 'Platform policy will be added soon' }}</p>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
