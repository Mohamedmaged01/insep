@extends('layouts.dashboard')
@section('title', 'INSEP PRO - إدارة المدربين')
@section('dashboard-content')
<div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-navy">إدارة المدربين</h1>
            <p class="text-gray-500 text-sm">إجمالي {{ $instructors->count() }} مدرب</p>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($instructors as $inst)
        <div class="bg-white rounded-2xl p-6 border border-gray-100 card-hover text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-navy to-navy-light rounded-2xl flex items-center justify-center text-white font-black text-xl">{{ mb_substr($inst->name, 0, 1) }}</div>
            <h3 class="text-lg font-bold text-navy mb-1">{{ $inst->name }}</h3>
            <p class="text-sm text-gray-500 mb-1" style="font-family: 'Roboto', sans-serif">{{ $inst->email }}</p>
            <p class="text-xs text-gray-400">{{ $inst->phone ?? '-' }}</p>
            <span class="inline-block mt-3 px-3 py-1 rounded-lg text-xs font-bold {{ ($inst->status ?? 'active') === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ($inst->status ?? 'active') === 'active' ? 'نشط' : 'معلق' }}</span>
        </div>
        @empty
        <div class="col-span-3 text-center py-12 text-gray-400">لا يوجد مدربين مسجلين بعد</div>
        @endforelse
    </div>
</div>
@endsection
