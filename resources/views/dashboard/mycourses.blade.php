@extends('layouts.dashboard')
@section('title', 'INSEP PRO - دوراتي')
@section('dashboard-content')
<h1 class="text-2xl font-black text-navy mb-6">دوراتي المسجلة</h1>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($enrollments as $enr)
    <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 card-hover">
        <div class="h-32 bg-gradient-to-br from-navy to-navy-light relative overflow-hidden">
            <div class="absolute inset-0 hero-pattern opacity-30"></div>
            <div class="absolute bottom-3 right-3 text-white text-sm font-bold">{{ $enr->course->title ?? 'دورة' }}</div>
        </div>
        <div class="p-5">
            <p class="text-sm text-gray-500 mb-2">المجموعة: {{ $enr->batch->name ?? '-' }}</p>
            <span class="px-3 py-1 rounded-lg text-xs font-bold {{ ($enr->status ?? 'active') === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ ($enr->status ?? 'active') === 'active' ? 'نشط' : ($enr->status ?? '-') }}</span>
        </div>
    </div>
    @empty
    <div class="col-span-3 text-center py-12 text-gray-400">لم تسجل في أي دورة بعد</div>
    @endforelse
</div>
@endsection
