@extends('layouts.app')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
@section('title', $isAr ? 'الشكاوي والدعم - INSEP PRO' : 'Support - INSEP PRO')

@section('content')
<section class="bg-navy py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-black text-white mb-3">{{ $isAr ? 'الشكاوي والدعم' : 'Complaints & Support' }}</h1>
        <p class="text-white/60 text-lg">{{ $isAr ? 'نحن هنا لمساعدتك والرد على استفساراتك' : 'We are here to help you and answer your inquiries' }}</p>
    </div>
</section>

<section class="bg-gray-50 py-16">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
            @php
                $content = $isAr
                    ? ($settings['support_ar'] ?? '')
                    : ($settings['support_en'] ?? '');
            @endphp

            @if($content)
                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed whitespace-pre-line">
                    {!! nl2br(e($content)) !!}
                </div>
            @else
                <div class="text-center py-16 text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <p class="text-lg font-medium">{{ $isAr ? 'سيتم إضافة معلومات الدعم قريباً' : 'Support information will be added soon' }}</p>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
