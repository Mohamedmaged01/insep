@extends('layouts.dashboard')
@section('title', 'INSEP PRO - الحضور والغياب')
@section('dashboard-content')
<h1 class="text-2xl font-black text-navy mb-6">الحضور والغياب</h1>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($batches as $batch)
    <div class="bg-white rounded-2xl p-6 border border-gray-100 card-hover">
        <h3 class="text-lg font-bold text-navy mb-2">{{ $batch->name }}</h3>
        <p class="text-sm text-gray-500 mb-4">{{ $batch->course->title ?? '-' }}</p>
        <a href="#" class="bg-navy/10 text-navy px-4 py-2 rounded-xl text-sm font-bold hover:bg-navy/20 transition-colors inline-block">تسجيل الحضور</a>
    </div>
    @empty
    <div class="col-span-3 text-center py-12 text-gray-400">لا يوجد مجموعات للحضور</div>
    @endforelse
</div>
@endsection
