@extends('layouts.dashboard')
@section('title', 'INSEP PRO - البث المباشر')
@section('dashboard-content')
<h1 class="text-2xl font-black text-navy mb-6">البث المباشر</h1>
<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">العنوان</th>
                <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">المجموعة</th>
                <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الموعد</th>
                <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الحالة</th>
            </tr></thead>
            <tbody>
                @forelse($sessions as $sess)
                <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                    <td class="px-6 py-4 font-bold text-navy text-sm">{{ $sess->title }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $sess->batch->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500" style="font-family: 'Roboto', sans-serif">{{ $sess->scheduled_at ? \Carbon\Carbon::parse($sess->scheduled_at)->format('Y-m-d H:i') : '-' }}</td>
                    <td class="px-6 py-4"><span class="px-3 py-1 rounded-lg text-xs font-bold {{ ($sess->status ?? '') === 'live' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">{{ ($sess->status ?? '') === 'live' ? 'مباشر الآن' : 'قادم' }}</span></td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-12 text-gray-400">لا يوجد جلسات بث</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
