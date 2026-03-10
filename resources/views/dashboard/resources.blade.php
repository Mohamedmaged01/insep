@extends('layouts.dashboard')
@section('title', 'INSEP PRO - المحاضرات المسجلة')
@section('dashboard-content')
<h1 class="text-2xl font-black text-navy mb-6">المحاضرات المسجلة</h1>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($resources as $res)
    <div class="bg-white rounded-2xl p-6 border border-gray-100 card-hover">
        <div class="w-12 h-12 rounded-xl bg-navy/10 flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-navy" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
        </div>
        <h3 class="font-bold text-navy mb-1">{{ $res->title }}</h3>
        <p class="text-sm text-gray-500 mb-2">{{ $res->course->title ?? '-' }}</p>
        <span class="text-xs text-gray-400">{{ $res->type ?? 'فيديو' }}</span>
    </div>
    @empty
    <div class="col-span-3 text-center py-12 text-gray-400">لا يوجد محاضرات مسجلة بعد</div>
    @endforelse
</div>
@endsection
