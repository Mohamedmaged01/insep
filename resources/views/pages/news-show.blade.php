@extends('layouts.app')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
@section('title', ($news->tr('title') ?? ($isAr ? 'خبر' : 'News')) . ' - INSEP PRO')

@section('content')
{{-- Hero --}}
<section class="bg-navy py-16">
    <div class="container mx-auto px-4 text-center">
        <span class="inline-block bg-red-brand text-white px-4 py-1.5 rounded-full text-sm font-bold mb-4">{{ $news->tag ?? ($isAr ? 'أخبار' : 'News') }}</span>
        <h1 class="text-3xl md:text-4xl font-black text-white mb-4 max-w-3xl mx-auto leading-snug">{{ $news->tr('title') }}</h1>
        @if($news->date ?? $news->created_at)
        <p class="text-white/50 text-sm">{{ $news->date ?? \Carbon\Carbon::parse($news->created_at)->format('Y-m-d') }}</p>
        @endif
    </div>
</section>

{{-- Content --}}
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4 max-w-3xl">
        @if($news->image)
        <div class="rounded-2xl overflow-hidden mb-10 shadow-lg">
            <img src="{{ str_starts_with($news->image, 'http') ? $news->image : asset('storage/' . ltrim($news->image, '/')) }}" alt="{{ $news->tr('title') }}" class="w-full h-72 object-cover">
        </div>
        @endif

        {{-- Video (YouTube / Google Drive) embedded inline like the course preview --}}
        @php
        $videoEmbed = null;
        if (!empty($news->video_url)) {
            $vu = $news->video_url;
            if      (preg_match('/youtube\.com\/embed\/([^?&\s]+)/', $vu, $m)) $videoEmbed = "https://www.youtube.com/embed/{$m[1]}";
            elseif  (preg_match('/[?&]v=([^&\s]+)/', $vu, $m))                  $videoEmbed = "https://www.youtube.com/embed/{$m[1]}";
            elseif  (preg_match('/youtu\.be\/([^?&\s]+)/', $vu, $m))            $videoEmbed = "https://www.youtube.com/embed/{$m[1]}";
            elseif  (preg_match('/youtube\.com\/shorts\/([^?&\s]+)/', $vu, $m)) $videoEmbed = "https://www.youtube.com/embed/{$m[1]}";
            elseif  (str_contains(strtolower($vu), 'drive.google.com')) {
                if      (preg_match('~/d/([a-zA-Z0-9_-]+)~', $vu, $m))     $videoEmbed = "https://drive.google.com/file/d/{$m[1]}/preview";
                elseif  (preg_match('~[?&]id=([a-zA-Z0-9_-]+)~', $vu, $m)) $videoEmbed = "https://drive.google.com/file/d/{$m[1]}/preview";
            }
        }
        @endphp
        @if($videoEmbed)
        <div class="rounded-2xl overflow-hidden mb-10 shadow-lg bg-black relative" style="padding-top: 56.25%">
            <iframe class="absolute inset-0 w-full h-full"
                src="{{ $videoEmbed }}"
                title="{{ $news->tr('title') }}"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 md:p-12">
            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed whitespace-pre-line">
                {!! nl2br(e($news->tr('description'))) !!}
            </div>

            {{-- Registration form call-to-action --}}
            @if($news->form_url)
            <div class="mt-10 pt-8 border-t border-gray-100 text-center">
                <p class="text-navy font-bold text-lg mb-4">{{ $isAr ? 'سجّل الآن للمشاركة' : 'Register Now to Participate' }}</p>
                <a href="{{ $news->form_url }}" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 bg-red-brand hover:bg-red-brand-dark text-white px-10 py-4 rounded-xl font-black text-lg shadow-lg shadow-red-brand/30 transition-all duration-300 hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $isAr ? 'سجل الآن' : 'Register Now' }}
                </a>
                <p class="text-gray-400 text-xs mt-3">{{ $isAr ? 'سيتم فتح نموذج التسجيل في نافذة جديدة' : 'The registration form opens in a new tab' }}</p>
            </div>
            @endif
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 bg-navy text-white px-8 py-3 rounded-xl font-bold hover:bg-opacity-90 transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
                {{ $isAr ? 'العودة للرئيسية' : 'Back to Home' }}
            </a>
        </div>
    </div>
</section>
@endsection
