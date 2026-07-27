@extends('layouts.dashboard')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
@section('title', $isAr ? 'نتائج البحث' : 'Search Results')

@section('dashboard-content')
<div class="p-6">

    {{-- Header + search box --}}
    <div class="mb-6">
        <h1 class="text-2xl font-black text-navy">{{ $isAr ? 'البحث العام' : 'Global Search' }}</h1>
        <p class="text-gray-500 text-sm mt-1">
            @if($q !== '')
                {{ $isAr ? 'نتائج البحث عن' : 'Results for' }} «<span class="font-bold text-navy">{{ $q }}</span>»
            @else
                {{ $isAr ? 'اكتب كلمة للبحث في كل أقسام المنصة' : 'Type a term to search across the whole platform' }}
            @endif
        </p>
    </div>

    <form method="GET" action="{{ route('dashboard.search') }}" class="relative mb-8 max-w-2xl">
        <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
        <input type="text" name="q" value="{{ $q }}" autofocus
               placeholder="{{ $isAr ? 'بحث بالاسم، البريد، رقم الشهادة، عنوان الدورة...' : 'Search name, email, serial, course title...' }}"
               class="w-full bg-white border-2 border-gray-200 rounded-xl pr-10 pl-24 py-3 text-sm focus:border-navy transition-colors">
        <button type="submit" class="absolute left-1.5 top-1/2 -translate-y-1/2 bg-navy text-white text-sm font-bold px-4 py-1.5 rounded-lg hover:bg-navy-dark transition-colors">{{ $isAr ? 'بحث' : 'Search' }}</button>
    </form>

    @php $total = collect($results)->sum(fn($c) => $c->count()); @endphp

    @if($q !== '' && $total === 0)
        <div class="text-center py-20 text-gray-400">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
            <p class="font-bold">{{ $isAr ? 'لا توجد نتائج مطابقة' : 'No matching results' }}</p>
        </div>
    @endif

    @php
        $groups = [
            ['key' => 'users',        'title' => $isAr ? 'المستخدمون' : 'Users',        'icon' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4z'],
            ['key' => 'courses',      'title' => $isAr ? 'الدورات' : 'Courses',          'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253'],
            ['key' => 'batches',      'title' => $isAr ? 'المجموعات' : 'Batches',        'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2'],
            ['key' => 'certificates', 'title' => $isAr ? 'الشهادات' : 'Certificates',    'icon' => 'M12 15a3 3 0 100-6 3 3 0 000 6zm0 0v6'],
            ['key' => 'news',         'title' => $isAr ? 'الأخبار' : 'News',             'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 002 2z'],
        ];
    @endphp

    <div class="space-y-8">
        @foreach($groups as $g)
        @php $items = $results[$g['key']]; @endphp
        @if($items->count() > 0)
        <div>
            <h2 class="flex items-center gap-2 text-sm font-black text-navy uppercase tracking-wide mb-3">
                <svg class="w-4 h-4 text-red-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $g['icon'] }}"/></svg>
                {{ $g['title'] }} <span class="text-gray-400 font-bold">({{ $items->count() }})</span>
            </h2>
            <div class="bg-white rounded-2xl border border-gray-100 divide-y divide-gray-50 overflow-hidden">
                @foreach($items as $item)
                    @php
                        [$label, $sub, $href] = match($g['key']) {
                            'users'        => [$item->name_ar ?? $item->name, $item->email . ($item->role ? ' · ' . $item->role : ''), route('dashboard.users', ['q' => $item->email])],
                            'courses'      => [$item->tr('title'), $item->category, route('dashboard.courses.show', $item->id)],
                            'batches'      => [$item->tr('name'), optional($item->course)->tr('title'), route('dashboard.batches.detail', $item->id)],
                            'certificates' => [$item->serial_number, optional($item->student)->name_ar ?? optional($item->student)->name, route('dashboard.certificates', ['q' => $item->serial_number])],
                            'news'         => [$item->tr('title'), $item->tag, route('news.show', $item->id)],
                        };
                    @endphp
                    <a href="{{ $href }}" class="flex items-center justify-between px-5 py-3.5 hover:bg-gray-50 transition-colors group">
                        <div class="min-w-0">
                            <p class="font-bold text-navy text-sm truncate group-hover:text-red-brand transition-colors">{{ $label ?: '—' }}</p>
                            @if($sub)<p class="text-xs text-gray-400 truncate" style="font-family:'Roboto',sans-serif">{{ $sub }}</p>@endif
                        </div>
                        <svg class="w-4 h-4 text-gray-300 flex-shrink-0 {{ $isAr ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @endforeach
            </div>
        </div>
        @endif
        @endforeach
    </div>
</div>
@endsection
