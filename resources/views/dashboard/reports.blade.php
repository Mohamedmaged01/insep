@extends('layouts.dashboard')
@section('title', 'INSEP PRO')
@section('dashboard-content')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <h1 class="text-2xl font-black text-navy">{{ $isAr ? 'التقارير' : 'Reports' }}</h1>
    <div class="flex items-center gap-3">
        <a href="{{ route('dashboard.reports.export', ['format' => 'excel']) }}"
           style="background:#1d6f42;color:#fff;padding:10px 20px;border-radius:12px;font-weight:700;font-size:13px;display:inline-flex;align-items:center;gap:8px;text-decoration:none;">
            <svg style="width:16px;height:16px" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            {{ $isAr ? 'تصدير Excel' : 'Export Excel' }}
        </a>
        <a href="{{ route('dashboard.reports.export', ['format' => 'pdf']) }}"
           style="background:#D61A23;color:#fff;padding:10px 20px;border-radius:12px;font-weight:700;font-size:13px;display:inline-flex;align-items:center;gap:8px;text-decoration:none;">
            <svg style="width:16px;height:16px" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            {{ $isAr ? 'تصدير PDF' : 'Export PDF' }}
        </a>
    </div>
</div>

{{-- Summary Stats --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
        <p class="text-3xl font-black text-blue-600">{{ $stats['students'] }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'إجمالي الطلاب' : 'Total Students' }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
        <p class="text-3xl font-black text-purple-600">{{ $stats['instructors'] }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'المدربون' : 'Trainers' }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
        <p class="text-3xl font-black text-green-600">{{ $stats['courses'] }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'الدورات' : 'Courses' }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
        <p class="text-3xl font-black text-orange-600">{{ $stats['batches'] }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'المجموعات' : 'Batches' }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
        <p class="text-3xl font-black text-navy">{{ $stats['enrollments'] }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'التسجيلات' : 'Enrollments' }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
        <p class="text-3xl font-black text-yellow-600">{{ $stats['certificates'] }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'الشهادات' : 'Certificates' }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
        <p class="text-2xl font-black text-green-600" style="font-family:'Roboto',sans-serif">{{ number_format($stats['income']) }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'الإيرادات (ج.م)' : 'Revenue (EGP)' }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
        <p class="text-2xl font-black text-red-600" style="font-family:'Roboto',sans-serif">{{ number_format($stats['expense']) }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'المصروفات (ج.م)' : 'Expenses (EGP)' }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Recent Students --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-navy">{{ $isAr ? 'آخر الطلاب المسجلين' : 'Recently Enrolled Students' }}</h3>
            <a href="{{ route('dashboard.students') }}" class="text-xs text-navy hover:underline font-bold">{{ $isAr ? 'عرض الكل' : 'View All' }}</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentStudents as $student)
            <div class="flex items-center gap-3 px-6 py-3">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-navy to-navy-light flex items-center justify-center text-white font-bold text-sm flex-shrink-0">{{ mb_substr($student->name, 0, 1) }}</div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-navy text-sm truncate">{{ $student->name }}</p>
                    <p class="text-xs text-gray-500 truncate" style="font-family:'Roboto',sans-serif">{{ $student->email }}</p>
                </div>
                <span class="px-2 py-0.5 rounded-lg text-xs font-bold {{ ($student->status ?? 'active') === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ ($student->status ?? 'active') === 'active' ? ($isAr ? 'نشط' : 'Active') : ($isAr ? 'معلق' : 'Suspended') }}
                </span>
            </div>
            @empty
            <p class="text-center py-8 text-gray-400 text-sm">{{ $isAr ? 'لا يوجد طلاب' : 'No students found' }}</p>
            @endforelse
        </div>
    </div>

    {{-- Top Courses --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-navy">{{ $isAr ? 'الدورات حسب التسجيل' : 'Courses by Enrollment' }}</h3>
            <a href="{{ route('dashboard.courses') }}" class="text-xs text-navy hover:underline font-bold">{{ $isAr ? 'عرض الكل' : 'View All' }}</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentCourses as $course)
            <div class="flex items-center gap-3 px-6 py-3">
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-navy text-sm truncate">{{ $course->title }}</p>
                    <p class="text-xs text-gray-500">{{ $course->category ?? '-' }}</p>
                </div>
                <div class="text-left">
                    <p class="text-sm font-bold text-navy" style="font-family:'Roboto',sans-serif">{{ $course->enrollments_count }}</p>
                    <p class="text-xs text-gray-400">{{ $isAr ? 'مسجل' : 'enrolled' }}</p>
                </div>
                <div class="text-left">
                    <p class="text-sm font-bold text-red-brand" style="font-family:'Roboto',sans-serif">{{ number_format($course->price) }}</p>
                    <p class="text-xs text-gray-400">{{ $isAr ? 'ج.م' : 'EGP' }}</p>
                </div>
            </div>
            @empty
            <p class="text-center py-8 text-gray-400 text-sm">{{ $isAr ? 'لا يوجد دورات' : 'No courses found' }}</p>
            @endforelse
        </div>
    </div>

    {{-- Recent Transactions --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden lg:col-span-2">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-navy">{{ $isAr ? 'آخر المعاملات المالية' : 'Recent Financial Transactions' }}</h3>
            <a href="{{ route('dashboard.finance') }}" class="text-xs text-navy hover:underline font-bold">{{ $isAr ? 'عرض الكل' : 'View All' }}</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead><tr class="bg-gray-50/50">
                    <th class="text-right text-xs font-bold text-gray-500 px-6 py-3">{{ $isAr ? 'الوصف' : 'Description' }}</th>
                    <th class="text-right text-xs font-bold text-gray-500 px-6 py-3">{{ $isAr ? 'المبلغ' : 'Amount' }}</th>
                    <th class="text-right text-xs font-bold text-gray-500 px-6 py-3">{{ $isAr ? 'النوع' : 'Type' }}</th>
                    <th class="text-right text-xs font-bold text-gray-500 px-6 py-3">{{ $isAr ? 'الطالب' : 'Student' }}</th>
                    <th class="text-right text-xs font-bold text-gray-500 px-6 py-3">{{ $isAr ? 'التاريخ' : 'Date' }}</th>
                </tr></thead>
                <tbody>
                    @forelse($recentTransactions as $tx)
                    <tr class="border-t border-gray-50 hover:bg-gray-50/50">
                        <td class="px-6 py-3 text-sm text-navy font-bold">{{ $tx->description }}</td>
                        <td class="px-6 py-3 text-sm font-bold {{ $tx->type === 'income' ? 'text-green-600' : 'text-red-600' }}" style="font-family:'Roboto',sans-serif">
                            {{ $tx->type === 'income' ? '+' : '-' }}{{ number_format($tx->amount) }}
                        </td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-0.5 rounded-lg text-xs font-bold {{ $tx->type === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $tx->type === 'income' ? ($isAr ? 'إيراد' : 'Income') : ($isAr ? 'مصروف' : 'Expense') }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ $tx->user->name ?? '-' }}</td>
                        <td class="px-6 py-3 text-sm text-gray-500" style="font-family:'Roboto',sans-serif">{{ \Carbon\Carbon::parse($tx->created_at)->format('Y-m-d') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-8 text-gray-400 text-sm">{{ $isAr ? 'لا يوجد معاملات' : 'No transactions found' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
