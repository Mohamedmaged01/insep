@extends('layouts.dashboard')
@section('title', 'INSEP PRO - المجموعات التدريبية')
@section('dashboard-content')
<div>
    <h1 class="text-2xl font-black text-navy mb-6">المجموعات التدريبية</h1>
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead><tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">#</th>
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الاسم</th>
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الدورة</th>
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">المدرب</th>
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الحالة</th>
                </tr></thead>
                <tbody>
                    @forelse($batches as $i => $batch)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $i + 1 }}</td>
                        <td class="px-6 py-4 font-bold text-navy text-sm">{{ $batch->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $batch->course->title ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $batch->instructor->name ?? '-' }}</td>
                        <td class="px-6 py-4"><span class="px-3 py-1 rounded-lg text-xs font-bold {{ ($batch->status ?? 'active') === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $batch->status ?? 'نشط' }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-12 text-gray-400">لا يوجد مجموعات بعد</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
