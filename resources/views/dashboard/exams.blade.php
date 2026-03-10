@extends('layouts.dashboard')
@section('title', 'INSEP PRO - الاختبارات')
@section('dashboard-content')
<h1 class="text-2xl font-black text-navy mb-6">الاختبارات</h1>
<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">العنوان</th>
                <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الدورة</th>
                <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">النوع</th>
                <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">عدد الأسئلة</th>
                <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">المدة</th>
            </tr></thead>
            <tbody>
                @forelse($exams as $exam)
                <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                    <td class="px-6 py-4 font-bold text-navy text-sm">{{ $exam->title }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $exam->course->title ?? '-' }}</td>
                    <td class="px-6 py-4"><span class="px-3 py-1 rounded-lg text-xs font-bold bg-navy/10 text-navy">{{ $exam->type ?? 'نهائي' }}</span></td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $exam->questions_count ?? 0 }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $exam->duration ?? '-' }} دقيقة</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-12 text-gray-400">لا يوجد اختبارات بعد</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
