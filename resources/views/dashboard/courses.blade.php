@extends('layouts.dashboard')
@section('title', 'INSEP PRO - إدارة الدورات')
@section('dashboard-content')
<div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-navy">إدارة الدورات</h1>
            <p class="text-gray-500 text-sm">إجمالي {{ $courses->count() }} دورة</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">#</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الدورة</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">التصنيف</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">المستوى</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">السعر</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">المسجلين</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $i => $course)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $i + 1 }}</td>
                        <td class="px-6 py-4 font-bold text-navy text-sm">{{ $course->title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $course->category }}</td>
                        <td class="px-6 py-4"><span class="px-3 py-1 rounded-lg text-xs font-bold bg-navy/10 text-navy">{{ $course->level }}</span></td>
                        <td class="px-6 py-4 text-sm font-bold text-red-brand" style="font-family: 'Roboto', sans-serif">{{ $course->price }} ج.م</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $course->enrollments_count ?? 0 }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-12 text-gray-400">لا يوجد دورات بعد</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
