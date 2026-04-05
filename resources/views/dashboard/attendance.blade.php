@extends('layouts.dashboard')
@section('title', 'INSEP PRO')
@section('dashboard-content')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
<h1 class="text-2xl font-black text-navy mb-6">{{ $isAr ? 'الحضور والغياب' : 'Attendance' }}</h1>
<p class="text-gray-500 text-sm mb-6">{{ $isAr ? 'اختر مجموعة لتسجيل الحضور' : 'Select a batch to record attendance' }}</p>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($batches as $batch)
    <div class="bg-white rounded-2xl p-6 border border-gray-100 card-hover">
        <div class="flex justify-between items-start mb-3">
            <h3 class="text-lg font-bold text-navy">{{ $batch->name }}</h3>
            <span class="px-2 py-1 rounded-lg text-xs font-bold {{ $batch->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                {{ $batch->status === 'active' ? ($isAr ? 'نشطة' : 'Active') : ($isAr ? 'منتهية' : 'Completed') }}
            </span>
        </div>
        <p class="text-sm text-gray-500 mb-4">{{ $batch->course->title ?? '-' }}</p>
        <a href="{{ route('dashboard.attendance.batch', $batch->id) }}" class="bg-navy/10 text-navy px-4 py-2 rounded-xl text-sm font-bold hover:bg-navy/20 transition-colors inline-block">
            {{ $isAr ? 'تسجيل الحضور ←' : 'Record Attendance ←' }}
        </a>
    </div>
    @empty
    <div class="col-span-3 text-center py-12 text-gray-400">{{ $isAr ? 'لا يوجد مجموعات بعد' : 'No batches found yet' }}</div>
    @endforelse
</div>
@endsection
