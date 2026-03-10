@extends('layouts.dashboard')
@section('title', 'INSEP PRO - الأقسام')
@section('dashboard-content')
<h1 class="text-2xl font-black text-navy mb-6">الأقسام</h1>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($departments as $dept)
    <div class="bg-white rounded-2xl p-6 border border-gray-100 card-hover">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-navy to-navy-light flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
        </div>
        <h3 class="text-lg font-bold text-navy mb-2">{{ $dept->name }}</h3>
        <p class="text-sm text-gray-500">{{ $dept->description ?? '' }}</p>
    </div>
    @empty
    <div class="col-span-3 text-center py-12 text-gray-400">لا يوجد أقسام بعد</div>
    @endforelse
</div>
@endsection
