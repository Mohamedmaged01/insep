@extends('layouts.dashboard')
@section('title', 'INSEP PRO - Reports')
@section('dashboard-content')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp

<div x-data="{ tab: 'overview' }">

{{-- Header --}}
<div class="flex flex-wrap justify-between items-center gap-4 mb-4">
    <h1 class="text-2xl font-black text-navy">{{ $isAr ? 'التقارير والتحليلات' : 'Reports & Analytics' }}</h1>
    <div class="flex items-center gap-2 flex-wrap">
        <a href="{{ route('dashboard.reports.export', ['format' => 'excel']) }}"
           style="background:#1d6f42;color:#fff;padding:9px 18px;border-radius:12px;font-weight:700;font-size:12px;display:inline-flex;align-items:center;gap:6px;text-decoration:none;">
            <svg style="width:14px;height:14px" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Excel
        </a>
        <a href="{{ route('dashboard.reports.export', ['format' => 'pdf']) }}"
           style="background:#D61A23;color:#fff;padding:9px 18px;border-radius:12px;font-weight:700;font-size:12px;display:inline-flex;align-items:center;gap:6px;text-decoration:none;">
            <svg style="width:14px;height:14px" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            PDF
        </a>
    </div>
</div>

{{-- Date Filter --}}
<form method="GET" action="{{ route('dashboard.reports') }}" class="bg-white rounded-2xl border border-gray-100 p-4 mb-4 flex flex-wrap items-end gap-4">
    <div>
        <label class="text-xs font-bold text-gray-500 block mb-1">{{ $isAr ? 'من تاريخ' : 'From' }}</label>
        <input type="date" name="from" value="{{ $from ? $from->format('Y-m-d') : '' }}"
               class="border-2 border-gray-200 rounded-xl px-3 py-2 text-sm focus:border-navy transition-colors" dir="ltr">
    </div>
    <div>
        <label class="text-xs font-bold text-gray-500 block mb-1">{{ $isAr ? 'إلى تاريخ' : 'To' }}</label>
        <input type="date" name="to" value="{{ $to ? $to->format('Y-m-d') : '' }}"
               class="border-2 border-gray-200 rounded-xl px-3 py-2 text-sm focus:border-navy transition-colors" dir="ltr">
    </div>
    <button type="submit" class="bg-navy text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-navy-dark transition-colors">
        {{ $isAr ? 'تطبيق الفلتر' : 'Apply Filter' }}
    </button>
    @if($from || $to)
    <a href="{{ route('dashboard.reports') }}" class="text-sm text-gray-400 hover:text-red-500 font-bold py-2.5">
        {{ $isAr ? 'إلغاء الفلتر' : 'Clear Filter' }}
    </a>
    @endif
    @if($from || $to)
    <span class="text-xs text-navy bg-navy/5 px-3 py-2 rounded-xl font-bold">
        {{ $isAr ? 'النتائج مفلترة حسب التاريخ' : 'Results filtered by date' }}
    </span>
    @endif
</form>

{{-- Tab Nav --}}
<div class="flex flex-wrap gap-2 mb-6 bg-white rounded-2xl p-2 border border-gray-100 overflow-x-auto">
    @php
    $tabs = [
        ['key' => 'overview',      'ar' => 'نظرة عامة',           'en' => 'Overview'],
        ['key' => 'students',      'ar' => 'تقارير المتدربين',    'en' => 'Trainees'],
        ['key' => 'courses',       'ar' => 'تقارير الدورات',      'en' => 'Courses'],
        ['key' => 'trainers',      'ar' => 'تقارير المدربين',     'en' => 'Trainers'],
        ['key' => 'financial',     'ar' => 'التقارير المالية',    'en' => 'Financial'],
        ['key' => 'installments',  'ar' => 'الأقساط',             'en' => 'Installments'],
        ['key' => 'attendance',    'ar' => 'الحضور والغياب',      'en' => 'Attendance'],
        ['key' => 'exams',         'ar' => 'الاختبارات',          'en' => 'Assessments'],
        ['key' => 'kpis',          'ar' => 'المؤشرات والاتجاهات', 'en' => 'KPIs & Trends'],
        ['key' => 'platform',      'ar' => 'إحصاءات المنصة',     'en' => 'Platform Stats'],
    ];
    @endphp
    @foreach($tabs as $t)
    <button @click="tab = '{{ $t['key'] }}'"
        :class="tab === '{{ $t['key'] }}' ? 'bg-navy text-white shadow' : 'text-gray-600 hover:text-navy hover:bg-gray-50'"
        class="px-4 py-2 rounded-xl text-sm font-bold transition-all whitespace-nowrap">
        {{ $isAr ? $t['ar'] : $t['en'] }}
    </button>
    @endforeach
</div>

{{-- ============================= OVERVIEW ============================= --}}
<div x-show="tab === 'overview'" x-cloak>
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 mb-6">
        @php
        $kpis = [
            ['value' => $stats['students'],     'label_ar' => 'إجمالي المتدربين',     'label_en' => 'Total Trainees',    'color' => 'blue-600',   'bg' => 'blue-50'],
            ['value' => $stats['instructors'],  'label_ar' => 'المدربون',             'label_en' => 'Trainers',          'color' => 'purple-600', 'bg' => 'purple-50'],
            ['value' => $stats['courses'],      'label_ar' => 'الدورات التدريبية',    'label_en' => 'Courses',           'color' => 'green-600',  'bg' => 'green-50'],
            ['value' => $stats['batches'],      'label_ar' => 'المجموعات',            'label_en' => 'Batches',           'color' => 'orange-600', 'bg' => 'orange-50'],
            ['value' => $stats['enrollments'],  'label_ar' => 'التسجيلات',            'label_en' => 'Enrollments',       'color' => 'navy',       'bg' => 'blue-50'],
            ['value' => $stats['certificates'], 'label_ar' => 'الشهادات',             'label_en' => 'Certificates',      'color' => 'yellow-600', 'bg' => 'yellow-50'],
            ['value' => $completionRate . '%',  'label_ar' => 'معدل الإكمال',         'label_en' => 'Completion Rate',   'color' => 'indigo-600', 'bg' => 'indigo-50'],
            ['value' => $retentionRate . '%',   'label_ar' => 'معدل الاحتفاظ',        'label_en' => 'Retention Rate',   'color' => 'teal-600',   'bg' => 'teal-50'],
            ['value' => $dropoutRate . '%',     'label_ar' => 'معدل الانسحاب',        'label_en' => 'Dropout Rate',      'color' => 'red-500',    'bg' => 'red-50'],
            ['value' => number_format($stats['income']),  'label_ar' => 'الإيرادات (ج.م)',  'label_en' => 'Revenue (EGP)',  'color' => 'green-600', 'bg' => 'green-50'],
            ['value' => number_format($stats['expense']), 'label_ar' => 'المصروفات (ج.م)',  'label_en' => 'Expenses (EGP)', 'color' => 'red-600',   'bg' => 'red-50'],
            ['value' => number_format($stats['profit']),  'label_ar' => 'صافي الربح (ج.م)', 'label_en' => 'Net Profit (EGP)', 'color' => ($stats['profit'] >= 0 ? 'green-700' : 'red-700'), 'bg' => ($stats['profit'] >= 0 ? 'green-50' : 'red-50')],
        ];
        @endphp
        @foreach($kpis as $kpi)
        <div class="bg-white rounded-2xl p-5 border border-gray-100">
            <p class="text-2xl font-black text-{{ $kpi['color'] }}" style="font-family:'Roboto',sans-serif">{{ $kpi['value'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? $kpi['label_ar'] : $kpi['label_en'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-navy">{{ $isAr ? 'آخر المتدربين المسجلين' : 'Recently Enrolled Trainees' }}</h3>
                <button @click="tab='students'" class="text-xs text-navy hover:underline font-bold">{{ $isAr ? 'عرض الكل' : 'View All' }}</button>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentStudents->take(8) as $student)
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
                <p class="text-center py-8 text-gray-400 text-sm">{{ $isAr ? 'لا يوجد بيانات' : 'No data' }}</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-navy">{{ $isAr ? 'أكثر الدورات تسجيلاً' : 'Top Courses by Enrollment' }}</h3>
                <button @click="tab='courses'" class="text-xs text-navy hover:underline font-bold">{{ $isAr ? 'عرض الكل' : 'View All' }}</button>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($topCourses->take(8) as $course)
                <div class="flex items-center gap-3 px-6 py-3">
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-navy text-sm truncate">{{ $course->title }}</p>
                        <p class="text-xs text-gray-500">{{ $course->category ?? '-' }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm font-bold text-navy" style="font-family:'Roboto',sans-serif">{{ $course->enrollments_count }}</p>
                        <p class="text-xs text-gray-400">{{ $isAr ? 'مسجل' : 'enrolled' }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm font-bold text-red-brand" style="font-family:'Roboto',sans-serif">{{ number_format($course->price ?? 0) }}</p>
                        <p class="text-xs text-gray-400">{{ $isAr ? 'ج.م' : 'EGP' }}</p>
                    </div>
                </div>
                @empty
                <p class="text-center py-8 text-gray-400 text-sm">{{ $isAr ? 'لا يوجد بيانات' : 'No data' }}</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-navy">{{ $isAr ? 'آخر المعاملات المالية' : 'Recent Financial Transactions' }}</h3>
            <button @click="tab='financial'" class="text-xs text-navy hover:underline font-bold">{{ $isAr ? 'عرض الكل' : 'View All' }}</button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead><tr class="bg-gray-50/50">
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-6 py-3">{{ $isAr ? 'الوصف' : 'Description' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-6 py-3">{{ $isAr ? 'المبلغ' : 'Amount' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-6 py-3">{{ $isAr ? 'النوع' : 'Type' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-6 py-3">{{ $isAr ? 'التاريخ' : 'Date' }}</th>
                </tr></thead>
                <tbody>
                    @forelse($recentTransactions->take(8) as $tx)
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
                        <td class="px-6 py-3 text-sm text-gray-500" style="font-family:'Roboto',sans-serif">{{ \Carbon\Carbon::parse($tx->created_at)->format('Y-m-d') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-8 text-gray-400 text-sm">{{ $isAr ? 'لا يوجد بيانات' : 'No data' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================= TRAINEES ============================= --}}
<div x-show="tab === 'students'" x-cloak>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-3xl font-black text-blue-600">{{ $stats['students'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'إجمالي المتدربين' : 'Total Trainees' }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-3xl font-black text-green-600">{{ $activeStudents }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'نشط' : 'Active' }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-3xl font-black text-yellow-600">{{ $stats['certificates'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'شهادات مكتسبة' : 'Certificates Earned' }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-3xl font-black text-navy">{{ $stats['enrollments'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'إجمالي التسجيلات' : 'Total Enrollments' }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
            <h3 class="font-bold text-navy">{{ $isAr ? 'بيانات المتدربين' : 'Trainee Data' }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead><tr class="bg-gray-50/50">
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">#</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">{{ $isAr ? 'الاسم' : 'Name' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">{{ $isAr ? 'البريد الإلكتروني' : 'Email' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">{{ $isAr ? 'الهاتف' : 'Phone' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">{{ $isAr ? 'التسجيلات' : 'Enrollments' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">{{ $isAr ? 'الشهادات' : 'Certs' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">{{ $isAr ? 'الحالة' : 'Status' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">{{ $isAr ? 'تاريخ التسجيل' : 'Registered' }}</th>
                </tr></thead>
                <tbody>
                    @forelse($recentStudents as $i => $student)
                    <tr class="border-t border-gray-50 hover:bg-gray-50/50">
                        <td class="px-4 py-3 text-sm text-gray-400">{{ $i + 1 }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-navy/10 flex items-center justify-center text-navy font-bold text-xs flex-shrink-0">{{ mb_substr($student->name, 0, 1) }}</div>
                                <span class="font-bold text-navy text-sm">{{ $student->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600" style="font-family:'Roboto',sans-serif">{{ $student->email }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600" style="font-family:'Roboto',sans-serif">{{ $student->phone ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-center text-navy" style="font-family:'Roboto',sans-serif">{{ $student->enrollments_count ?? 0 }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-center text-yellow-600" style="font-family:'Roboto',sans-serif">{{ $student->certificates_count ?? 0 }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded-lg text-xs font-bold {{ ($student->status ?? 'active') === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ($student->status ?? 'active') === 'active' ? ($isAr ? 'نشط' : 'Active') : ($isAr ? 'معلق' : 'Suspended') }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500" style="font-family:'Roboto',sans-serif">{{ $student->created_at ? \Carbon\Carbon::parse($student->created_at)->format('Y-m-d') : '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-12 text-gray-400">{{ $isAr ? 'لا يوجد بيانات' : 'No data' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================= COURSES ============================= --}}
<div x-show="tab === 'courses'" x-cloak>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-3xl font-black text-green-600">{{ $stats['courses'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'إجمالي الدورات' : 'Total Courses' }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-3xl font-black text-navy">{{ $stats['enrollments'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'إجمالي التسجيلات' : 'Total Enrollments' }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-3xl font-black text-yellow-600">{{ $stats['certificates'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'شهادات ممنوحة' : 'Certificates Issued' }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-3xl font-black text-indigo-600">{{ $completionRate }}%</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'معدل الإكمال' : 'Completion Rate' }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
            <h3 class="font-bold text-navy">{{ $isAr ? 'الدورات حسب التسجيل' : 'Courses by Enrollment' }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead><tr class="bg-gray-50/50">
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">#</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">{{ $isAr ? 'الدورة' : 'Course' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">{{ $isAr ? 'التصنيف' : 'Category' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">{{ $isAr ? 'المستوى' : 'Level' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">{{ $isAr ? 'المسجلون' : 'Enrolled' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">{{ $isAr ? 'المحتوى' : 'Resources' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">{{ $isAr ? 'الاختبارات' : 'Exams' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">{{ $isAr ? 'السعر' : 'Price' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">{{ $isAr ? 'إيرادات متوقعة' : 'Est. Revenue' }}</th>
                </tr></thead>
                <tbody>
                    @forelse($topCourses as $i => $course)
                    <tr class="border-t border-gray-50 hover:bg-gray-50/50">
                        <td class="px-4 py-3 text-sm text-gray-400">{{ $i + 1 }}</td>
                        <td class="px-4 py-3 font-bold text-navy text-sm max-w-xs">{{ $course->title }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $course->category ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @if($course->level)
                            <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-bold">{{ $course->level }}</span>
                            @else <span class="text-gray-400 text-sm">-</span> @endif
                        </td>
                        <td class="px-4 py-3 text-sm font-bold text-navy text-center" style="font-family:'Roboto',sans-serif">{{ $course->enrollments_count }}</td>
                        <td class="px-4 py-3 text-sm text-center text-purple-600 font-bold" style="font-family:'Roboto',sans-serif">{{ $course->resources_count ?? 0 }}</td>
                        <td class="px-4 py-3 text-sm text-center text-orange-600 font-bold" style="font-family:'Roboto',sans-serif">{{ $course->exams_count ?? 0 }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-red-brand" style="font-family:'Roboto',sans-serif">{{ number_format($course->price ?? 0) }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-green-600" style="font-family:'Roboto',sans-serif">{{ number_format(($course->price ?? 0) * $course->enrollments_count) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-12 text-gray-400">{{ $isAr ? 'لا يوجد بيانات' : 'No data' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================= TRAINERS ============================= --}}
<div x-show="tab === 'trainers'" x-cloak>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-3xl font-black text-purple-600">{{ $stats['instructors'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'إجمالي المدربين' : 'Total Trainers' }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-3xl font-black text-orange-600">{{ $stats['batches'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'إجمالي المجموعات' : 'Total Batches' }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-3xl font-black text-green-600">{{ $stats['students'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'إجمالي المتدربين' : 'Total Trainees' }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
            <h3 class="font-bold text-navy">{{ $isAr ? 'بيانات المدربين' : 'Trainer Data' }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead><tr class="bg-gray-50/50">
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">#</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">{{ $isAr ? 'المدرب' : 'Trainer' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">{{ $isAr ? 'البريد الإلكتروني' : 'Email' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">{{ $isAr ? 'المجموعات' : 'Batches' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">{{ $isAr ? 'المتدربون' : 'Trainees' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">{{ $isAr ? 'معدل النجاح' : 'Pass Rate' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">{{ $isAr ? 'تاريخ الانضمام' : 'Joined' }}</th>
                </tr></thead>
                <tbody>
                    @forelse($instructorList as $i => $instructor)
                    <tr class="border-t border-gray-50 hover:bg-gray-50/50">
                        <td class="px-4 py-3 text-sm text-gray-400">{{ $i + 1 }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-purple-100 flex items-center justify-center text-purple-700 font-bold text-xs flex-shrink-0">{{ mb_substr($instructor->name, 0, 1) }}</div>
                                <span class="font-bold text-navy text-sm">{{ $instructor->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600" style="font-family:'Roboto',sans-serif">{{ $instructor->email }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-navy text-center" style="font-family:'Roboto',sans-serif">{{ $instructor->batch_count }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-blue-600 text-center" style="font-family:'Roboto',sans-serif">{{ $instructor->student_count }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-sm font-bold {{ $instructor->pass_rate >= 70 ? 'text-green-600' : ($instructor->pass_rate >= 50 ? 'text-orange-500' : 'text-red-500') }}" style="font-family:'Roboto',sans-serif">
                                {{ $instructor->pass_rate }}%
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500" style="font-family:'Roboto',sans-serif">{{ $instructor->created_at ? \Carbon\Carbon::parse($instructor->created_at)->format('Y-m-d') : '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-12 text-gray-400">{{ $isAr ? 'لا يوجد بيانات' : 'No data' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================= FINANCIAL ============================= --}}
<div x-show="tab === 'financial'" x-cloak>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-2xl font-black text-green-600" style="font-family:'Roboto',sans-serif">{{ number_format($stats['income']) }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'إجمالي الإيرادات (ج.م)' : 'Total Revenue (EGP)' }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-2xl font-black text-red-600" style="font-family:'Roboto',sans-serif">{{ number_format($stats['expense']) }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'إجمالي المصروفات (ج.م)' : 'Total Expenses (EGP)' }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-2xl font-black {{ $stats['profit'] >= 0 ? 'text-green-700' : 'text-red-700' }}" style="font-family:'Roboto',sans-serif">{{ number_format($stats['profit']) }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'صافي الربح (ج.م)' : 'Net Profit (EGP)' }}</p>
        </div>
    </div>

    {{-- Monthly trend bars --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6">
        <h3 class="font-bold text-navy mb-5">{{ $isAr ? 'الإيرادات والمصروفات — آخر 6 أشهر' : 'Revenue & Expenses — Last 6 Months' }}</h3>
        @php $maxFinancial = max(max(array_values($incomeByMonth) ?: [1]), max(array_values($expenseByMonth) ?: [1]), 1); @endphp
        <div class="space-y-4">
            @foreach($incomeByMonth as $month => $income)
            @php $expense = $expenseByMonth[$month] ?? 0; @endphp
            <div>
                <div class="flex justify-between text-xs text-gray-500 mb-1">
                    <span>{{ $month }}</span>
                    <span style="font-family:'Roboto',sans-serif">{{ $isAr ? 'إيراد: ' : 'Rev: ' }}{{ number_format($income) }} / {{ $isAr ? 'مصروف: ' : 'Exp: ' }}{{ number_format($expense) }}</span>
                </div>
                <div class="flex gap-1 h-4">
                    <div class="bg-green-500 rounded-l" style="width:{{ $maxFinancial > 0 ? round(($income/$maxFinancial)*100) : 0 }}%; min-width:2px"></div>
                    <div class="bg-red-400 rounded-r" style="width:{{ $maxFinancial > 0 ? round(($expense/$maxFinancial)*100) : 0 }}%; min-width:{{ $expense > 0 ? '2px' : '0' }}"></div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="flex gap-4 mt-4 text-xs text-gray-500">
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-green-500 inline-block"></span>{{ $isAr ? 'إيرادات' : 'Revenue' }}</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-red-400 inline-block"></span>{{ $isAr ? 'مصروفات' : 'Expenses' }}</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
            <h3 class="font-bold text-navy">{{ $isAr ? 'سجل المعاملات المالية' : 'Transaction Log' }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead><tr class="bg-gray-50/50">
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-6 py-3">{{ $isAr ? 'الوصف' : 'Description' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-6 py-3">{{ $isAr ? 'المبلغ' : 'Amount' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-6 py-3">{{ $isAr ? 'النوع' : 'Type' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-6 py-3">{{ $isAr ? 'المتدرب' : 'Student' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-6 py-3">{{ $isAr ? 'التاريخ' : 'Date' }}</th>
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
                    <tr><td colspan="5" class="text-center py-12 text-gray-400">{{ $isAr ? 'لا يوجد بيانات' : 'No data' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================= INSTALLMENTS ============================= --}}
<div x-show="tab === 'installments'" x-cloak>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-2xl font-black text-navy" style="font-family:'Roboto',sans-serif">{{ number_format($installmentTotal) }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'إجمالي الأقساط (ج.م)' : 'Total Installments (EGP)' }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-2xl font-black text-green-600" style="font-family:'Roboto',sans-serif">{{ number_format($installmentPaid) }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'المدفوع (ج.م)' : 'Paid (EGP)' }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-2xl font-black text-orange-600" style="font-family:'Roboto',sans-serif">{{ number_format($installmentPending) }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'المتبقي (ج.م)' : 'Remaining (EGP)' }}</p>
        </div>
    </div>

    @if($installmentTotal > 0)
    <div class="bg-white rounded-2xl border border-gray-100 p-5 mb-6">
        <h3 class="font-bold text-navy mb-3">{{ $isAr ? 'نسبة التحصيل' : 'Collection Rate' }}</h3>
        @php $collectRate = $installmentTotal > 0 ? round(($installmentPaid / $installmentTotal) * 100) : 0; @endphp
        <div class="flex rounded-xl overflow-hidden h-6 mb-2">
            <div class="bg-green-500 flex items-center justify-center text-white text-xs font-bold" style="width:{{ $collectRate }}%">
                @if($collectRate > 10) {{ $collectRate }}% @endif
            </div>
            <div class="bg-orange-300 flex items-center justify-center text-white text-xs font-bold" style="width:{{ 100 - $collectRate }}%">
                @if((100 - $collectRate) > 10) {{ 100 - $collectRate }}% @endif
            </div>
        </div>
        <div class="flex gap-4 text-xs text-gray-500">
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-green-500 inline-block"></span>{{ $isAr ? 'مدفوع' : 'Paid' }}</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-orange-300 inline-block"></span>{{ $isAr ? 'متبقي' : 'Remaining' }}</span>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
            <h3 class="font-bold text-navy">{{ $isAr ? 'سجل الأقساط' : 'Installment Log' }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead><tr class="bg-gray-50/50">
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">{{ $isAr ? 'المتدرب' : 'Trainee' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">{{ $isAr ? 'الدورة' : 'Course' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">{{ $isAr ? 'الإجمالي' : 'Total' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">{{ $isAr ? 'المدفوع' : 'Paid' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">{{ $isAr ? 'الموعد النهائي' : 'Due Date' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-4 py-3">{{ $isAr ? 'الحالة' : 'Status' }}</th>
                </tr></thead>
                <tbody>
                    @forelse($recentInstallments as $inst)
                    <tr class="border-t border-gray-50 hover:bg-gray-50/50">
                        <td class="px-4 py-3 text-sm font-bold text-navy">{{ $inst->student->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $inst->course->title ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-navy" style="font-family:'Roboto',sans-serif">{{ number_format($inst->total_amount) }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-green-600" style="font-family:'Roboto',sans-serif">{{ number_format($inst->paid_amount) }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500" style="font-family:'Roboto',sans-serif">{{ $inst->due_date ? \Carbon\Carbon::parse($inst->due_date)->format('Y-m-d') : '-' }}</td>
                        <td class="px-4 py-3">
                            @php
                            $sc = ['pending' => 'bg-yellow-100 text-yellow-700', 'paid' => 'bg-green-100 text-green-700', 'overdue' => 'bg-red-100 text-red-700'];
                            $sl = ['pending' => ($isAr ? 'معلق' : 'Pending'), 'paid' => ($isAr ? 'مدفوع' : 'Paid'), 'overdue' => ($isAr ? 'متأخر' : 'Overdue')];
                            $s = $inst->status ?? 'pending';
                            @endphp
                            <span class="px-2 py-0.5 rounded-lg text-xs font-bold {{ $sc[$s] ?? 'bg-gray-100 text-gray-600' }}">{{ $sl[$s] ?? $s }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-12 text-gray-400">{{ $isAr ? 'لا يوجد أقساط' : 'No installments' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================= ATTENDANCE ============================= --}}
<div x-show="tab === 'attendance'" x-cloak>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-3xl font-black text-navy">{{ $totalAttendance }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'إجمالي السجلات' : 'Total Records' }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-3xl font-black text-green-600">{{ $presentCount }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'حاضر' : 'Present' }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-3xl font-black text-red-600">{{ $absentCount }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'غائب' : 'Absent' }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-3xl font-black text-indigo-600">{{ $attendanceRate }}%</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'معدل الحضور' : 'Attendance Rate' }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6">
        <h3 class="font-bold text-navy mb-4">{{ $isAr ? 'نسبة الحضور والغياب' : 'Attendance vs Absence' }}</h3>
        <div class="flex rounded-xl overflow-hidden h-8 mb-3">
            <div class="bg-green-500 flex items-center justify-center text-white text-xs font-bold" style="width:{{ $attendanceRate }}%">
                @if($attendanceRate > 10) {{ $attendanceRate }}% @endif
            </div>
            <div class="bg-red-400 flex items-center justify-center text-white text-xs font-bold" style="width:{{ 100 - $attendanceRate }}%">
                @if((100 - $attendanceRate) > 10) {{ 100 - $attendanceRate }}% @endif
            </div>
        </div>
        <div class="flex gap-4 text-xs text-gray-500">
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-green-500 inline-block"></span>{{ $isAr ? 'حضور' : 'Present' }} ({{ $presentCount }})</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-red-400 inline-block"></span>{{ $isAr ? 'غياب' : 'Absent' }} ({{ $absentCount }})</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
            <h3 class="font-bold text-navy">{{ $isAr ? 'سجل الحضور' : 'Attendance Log' }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead><tr class="bg-gray-50/50">
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-6 py-3">{{ $isAr ? 'المتدرب' : 'Trainee' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-6 py-3">{{ $isAr ? 'المجموعة' : 'Batch' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-6 py-3">{{ $isAr ? 'الحالة' : 'Status' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-6 py-3">{{ $isAr ? 'التاريخ' : 'Date' }}</th>
                </tr></thead>
                <tbody>
                    @forelse($recentAttendance as $att)
                    <tr class="border-t border-gray-50 hover:bg-gray-50/50">
                        <td class="px-6 py-3 text-sm font-bold text-navy">{{ $att->student->name ?? '-' }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ $att->batch->name ?? '-' }}</td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-0.5 rounded-lg text-xs font-bold {{ $att->status === 'present' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $att->status === 'present' ? ($isAr ? 'حاضر' : 'Present') : ($isAr ? 'غائب' : 'Absent') }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-500" style="font-family:'Roboto',sans-serif">{{ $att->created_at ? \Carbon\Carbon::parse($att->created_at)->format('Y-m-d') : '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-12 text-gray-400">{{ $isAr ? 'لا يوجد بيانات' : 'No data' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================= EXAMS / ASSESSMENTS ============================= --}}
<div x-show="tab === 'exams'" x-cloak>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-3xl font-black text-navy">{{ $totalExamsTaken }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'اختبارات مؤداة' : 'Exams Taken' }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-3xl font-black text-blue-600">{{ $avgScore }}%</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'متوسط الدرجات' : 'Avg Score' }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-3xl font-black text-green-600">{{ $passRate }}%</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'معدل النجاح' : 'Pass Rate' }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-3xl font-black text-yellow-600">{{ $stats['certificates'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'شهادات مُصدرة' : 'Certificates Issued' }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
            <h3 class="font-bold text-navy">{{ $isAr ? 'نتائج الاختبارات' : 'Exam Results' }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead><tr class="bg-gray-50/50">
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-6 py-3">{{ $isAr ? 'المتدرب' : 'Trainee' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-6 py-3">{{ $isAr ? 'الاختبار' : 'Exam' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-6 py-3">{{ $isAr ? 'الدرجة' : 'Score' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-6 py-3">{{ $isAr ? 'النتيجة' : 'Result' }}</th>
                    <th class="{{ $isAr ? 'text-right' : 'text-left' }} text-xs font-bold text-gray-500 px-6 py-3">{{ $isAr ? 'التاريخ' : 'Date' }}</th>
                </tr></thead>
                <tbody>
                    @forelse($recentExams as $result)
                    @php $passed = ($result->score ?? 0) >= 60; @endphp
                    <tr class="border-t border-gray-50 hover:bg-gray-50/50">
                        <td class="px-6 py-3 text-sm font-bold text-navy">{{ $result->student->name ?? '-' }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ $result->exam->title ?? '-' }}</td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold {{ $passed ? 'text-green-600' : 'text-red-600' }}" style="font-family:'Roboto',sans-serif">{{ $result->score ?? 0 }}%</span>
                                <div class="w-16 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="{{ $passed ? 'bg-green-500' : 'bg-red-400' }} h-full rounded-full" style="width:{{ min($result->score ?? 0, 100) }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-0.5 rounded-lg text-xs font-bold {{ $passed ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $passed ? ($isAr ? 'ناجح' : 'Pass') : ($isAr ? 'راسب' : 'Fail') }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-500" style="font-family:'Roboto',sans-serif">{{ $result->created_at ? \Carbon\Carbon::parse($result->created_at)->format('Y-m-d') : '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-12 text-gray-400">{{ $isAr ? 'لا يوجد بيانات' : 'No data' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================= KPIs & TRENDS ============================= --}}
<div x-show="tab === 'kpis'" x-cloak>
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 border border-gray-100">
            <p class="text-xs text-gray-500 mb-1">{{ $isAr ? 'معدل الإكمال' : 'Completion Rate' }}</p>
            <p class="text-2xl font-black text-indigo-600">{{ $completionRate }}%</p>
            <div class="w-full h-1.5 bg-gray-100 rounded-full mt-2"><div class="bg-indigo-500 h-full rounded-full" style="width:{{ $completionRate }}%"></div></div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100">
            <p class="text-xs text-gray-500 mb-1">{{ $isAr ? 'معدل نجاح الاختبارات' : 'Exam Pass Rate' }}</p>
            <p class="text-2xl font-black text-green-600">{{ $passRate }}%</p>
            <div class="w-full h-1.5 bg-gray-100 rounded-full mt-2"><div class="bg-green-500 h-full rounded-full" style="width:{{ $passRate }}%"></div></div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100">
            <p class="text-xs text-gray-500 mb-1">{{ $isAr ? 'معدل الحضور' : 'Attendance Rate' }}</p>
            <p class="text-2xl font-black text-blue-600">{{ $attendanceRate }}%</p>
            <div class="w-full h-1.5 bg-gray-100 rounded-full mt-2"><div class="bg-blue-500 h-full rounded-full" style="width:{{ $attendanceRate }}%"></div></div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100">
            <p class="text-xs text-gray-500 mb-1">{{ $isAr ? 'متوسط درجات الاختبارات' : 'Avg Exam Score' }}</p>
            <p class="text-2xl font-black text-orange-600">{{ $avgScore }}%</p>
            <div class="w-full h-1.5 bg-gray-100 rounded-full mt-2"><div class="bg-orange-500 h-full rounded-full" style="width:{{ $avgScore }}%"></div></div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100">
            <p class="text-xs text-gray-500 mb-1">{{ $isAr ? 'معدل الاحتفاظ' : 'Retention Rate' }}</p>
            <p class="text-2xl font-black text-teal-600">{{ $retentionRate }}%</p>
            <div class="w-full h-1.5 bg-gray-100 rounded-full mt-2"><div class="bg-teal-500 h-full rounded-full" style="width:{{ $retentionRate }}%"></div></div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100">
            <p class="text-xs text-gray-500 mb-1">{{ $isAr ? 'معدل الانسحاب' : 'Dropout Rate' }}</p>
            <p class="text-2xl font-black text-red-500">{{ $dropoutRate }}%</p>
            <div class="w-full h-1.5 bg-gray-100 rounded-full mt-2"><div class="bg-red-400 h-full rounded-full" style="width:{{ $dropoutRate }}%"></div></div>
        </div>
    </div>

    {{-- Monthly Student Registrations — 12 months --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6">
        <h3 class="font-bold text-navy mb-5">{{ $isAr ? 'تسجيلات المتدربين — آخر 12 شهراً' : 'Trainee Registrations — Last 12 Months' }}</h3>
        @php $maxStudents = max(max(array_values($monthlyStudents) ?: [1]), 1); @endphp
        <div class="flex items-end gap-1.5 h-28 overflow-x-auto pb-1">
            @foreach($monthlyStudents as $month => $count)
            @php $barH = $maxStudents > 0 ? max(round(($count / $maxStudents) * 100), $count > 0 ? 5 : 0) : 0; @endphp
            <div class="flex flex-col items-center gap-1 min-w-[40px] flex-1">
                <span class="text-xs font-bold text-navy" style="font-family:'Roboto',sans-serif">{{ $count }}</span>
                <div class="w-full bg-navy rounded-t-lg" style="height:{{ $barH }}%"></div>
                <span class="text-xs text-gray-400 text-center leading-tight" style="font-size:10px">{{ explode(' ', $month)[0] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Monthly Enrollments -- 12 months --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6">
        <h3 class="font-bold text-navy mb-5">{{ $isAr ? 'التسجيلات الشهرية — آخر 12 شهراً' : 'Monthly Enrollments — Last 12 Months' }}</h3>
        @php $maxEnrollments = max(max(array_values($monthlyEnrollments) ?: [1]), 1); @endphp
        <div class="flex items-end gap-1.5 h-28 overflow-x-auto pb-1">
            @foreach($monthlyEnrollments as $month => $count)
            @php $barH = $maxEnrollments > 0 ? max(round(($count / $maxEnrollments) * 100), $count > 0 ? 5 : 0) : 0; @endphp
            <div class="flex flex-col items-center gap-1 min-w-[40px] flex-1">
                <span class="text-xs font-bold text-indigo-600" style="font-family:'Roboto',sans-serif">{{ $count }}</span>
                <div class="w-full bg-indigo-500 rounded-t-lg" style="height:{{ $barH }}%"></div>
                <span class="text-xs text-gray-400 text-center leading-tight" style="font-size:10px">{{ explode(' ', $month)[0] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- KPI Summary Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
            <h3 class="font-bold text-navy">{{ $isAr ? 'ملخص المؤشرات' : 'KPI Summary' }}</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @php
            $kpiRows = [
                ['label_ar' => 'إجمالي المتدربين',          'label_en' => 'Total Trainees',          'value' => $stats['students'],                    'color' => 'blue-600'],
                ['label_ar' => 'المتدربون النشطون',           'label_en' => 'Active Trainees',          'value' => $activeStudents,                       'color' => 'green-600'],
                ['label_ar' => 'إجمالي المدربين',            'label_en' => 'Total Trainers',          'value' => $stats['instructors'],                 'color' => 'purple-600'],
                ['label_ar' => 'إجمالي الدورات',             'label_en' => 'Total Courses',           'value' => $stats['courses'],                     'color' => 'green-600'],
                ['label_ar' => 'إجمالي التسجيلات',           'label_en' => 'Total Enrollments',       'value' => $stats['enrollments'],                 'color' => 'navy'],
                ['label_ar' => 'شهادات مُصدرة',              'label_en' => 'Certificates Issued',     'value' => $stats['certificates'],                'color' => 'yellow-600'],
                ['label_ar' => 'معدل إكمال الدورات',         'label_en' => 'Course Completion Rate',  'value' => $completionRate . '%',                 'color' => 'indigo-600'],
                ['label_ar' => 'معدل نجاح الاختبارات',       'label_en' => 'Exam Pass Rate',          'value' => $passRate . '%',                       'color' => 'green-600'],
                ['label_ar' => 'متوسط درجات الاختبارات',     'label_en' => 'Avg Exam Score',          'value' => $avgScore . '%',                       'color' => 'orange-600'],
                ['label_ar' => 'معدل الحضور',                'label_en' => 'Attendance Rate',         'value' => $attendanceRate . '%',                 'color' => 'blue-600'],
                ['label_ar' => 'معدل الاحتفاظ بالمتدربين',   'label_en' => 'Trainee Retention Rate',  'value' => $retentionRate . '%',                  'color' => 'teal-600'],
                ['label_ar' => 'معدل الانسحاب',              'label_en' => 'Dropout Rate',            'value' => $dropoutRate . '%',                    'color' => 'red-500'],
                ['label_ar' => 'صافي الربح (ج.م)',           'label_en' => 'Net Profit (EGP)',         'value' => number_format($stats['profit']),        'color' => ($stats['profit'] >= 0 ? 'green-700' : 'red-700')],
            ];
            @endphp
            @foreach($kpiRows as $row)
            <div class="flex items-center justify-between px-6 py-3 hover:bg-gray-50/50">
                <span class="text-sm text-gray-700 font-medium">{{ $isAr ? $row['label_ar'] : $row['label_en'] }}</span>
                <span class="text-sm font-black text-{{ $row['color'] }}" style="font-family:'Roboto',sans-serif">{{ $row['value'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ============================= PLATFORM STATS ============================= --}}
<div x-show="tab === 'platform'" x-cloak>
    {{-- User Breakdown --}}
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4 mb-6">
        @php
        $platformKpis = [
            ['value' => $stats['students'],    'label_ar' => 'المتدربون',            'label_en' => 'Trainees',       'color' => 'blue-600'],
            ['value' => $activeStudents,       'label_ar' => 'نشط',                  'label_en' => 'Active',         'color' => 'green-600'],
            ['value' => $inactiveStudents,     'label_ar' => 'غير نشط',              'label_en' => 'Inactive',       'color' => 'red-500'],
            ['value' => $stats['instructors'], 'label_ar' => 'المدربون',             'label_en' => 'Trainers',       'color' => 'purple-600'],
            ['value' => $stats['batches'],     'label_ar' => 'المجموعات',            'label_en' => 'Batches',        'color' => 'orange-600'],
            ['value' => $totalResources,       'label_ar' => 'ملفات المحتوى',        'label_en' => 'Content Files',  'color' => 'indigo-600'],
            ['value' => number_format($totalDownloads), 'label_ar' => 'إجمالي التحميلات', 'label_en' => 'Total Downloads', 'color' => 'blue-700'],
            ['value' => $totalLiveSessions,    'label_ar' => 'جلسات مباشرة',         'label_en' => 'Live Sessions',  'color' => 'red-600'],
            ['value' => $totalNotifications,   'label_ar' => 'إشعارات مُرسلة',       'label_en' => 'Notifications',  'color' => 'yellow-600'],
            ['value' => number_format($totalPoints), 'label_ar' => 'نقاط ممنوحة',   'label_en' => 'Points Awarded', 'color' => 'green-600'],
        ];
        @endphp
        @foreach($platformKpis as $kpi)
        <div class="bg-white rounded-2xl p-5 border border-gray-100">
            <p class="text-2xl font-black text-{{ $kpi['color'] }}" style="font-family:'Roboto',sans-serif">{{ $kpi['value'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? $kpi['label_ar'] : $kpi['label_en'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Active vs Inactive --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6">
        <h3 class="font-bold text-navy mb-4">{{ $isAr ? 'نسبة المتدربين النشطين وغير النشطين' : 'Active vs Inactive Trainees' }}</h3>
        @php $activeRate = $stats['students'] > 0 ? round(($activeStudents / $stats['students']) * 100) : 0; @endphp
        <div class="flex rounded-xl overflow-hidden h-8 mb-3">
            <div class="bg-green-500 flex items-center justify-center text-white text-xs font-bold" style="width:{{ $activeRate }}%">
                @if($activeRate > 10) {{ $activeRate }}% @endif
            </div>
            <div class="bg-red-300 flex items-center justify-center text-white text-xs font-bold" style="width:{{ 100 - $activeRate }}%">
                @if((100 - $activeRate) > 10) {{ 100 - $activeRate }}% @endif
            </div>
        </div>
        <div class="flex gap-4 text-xs text-gray-500">
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-green-500 inline-block"></span>{{ $isAr ? 'نشط' : 'Active' }} ({{ $activeStudents }})</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-red-300 inline-block"></span>{{ $isAr ? 'غير نشط' : 'Inactive' }} ({{ $inactiveStudents }})</span>
        </div>
    </div>

    {{-- Retention vs Dropout --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6">
        <h3 class="font-bold text-navy mb-4">{{ $isAr ? 'الاحتفاظ بالمتدربين مقابل الانسحاب' : 'Retention vs Dropout' }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="font-bold text-teal-600">{{ $isAr ? 'معدل الاحتفاظ' : 'Retention Rate' }}</span>
                    <span class="font-black text-teal-600" style="font-family:'Roboto',sans-serif">{{ $retentionRate }}%</span>
                </div>
                <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                    <div class="bg-teal-500 h-full rounded-full" style="width:{{ $retentionRate }}%"></div>
                </div>
                <p class="text-xs text-gray-400 mt-1">{{ $retainedStudents }} {{ $isAr ? 'متدرب مسجل في أكثر من دورة' : 'trainees enrolled in 2+ courses' }}</p>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="font-bold text-red-500">{{ $isAr ? 'معدل الانسحاب' : 'Dropout Rate' }}</span>
                    <span class="font-black text-red-500" style="font-family:'Roboto',sans-serif">{{ $dropoutRate }}%</span>
                </div>
                <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                    <div class="bg-red-400 h-full rounded-full" style="width:{{ $dropoutRate }}%"></div>
                </div>
                <p class="text-xs text-gray-400 mt-1">{{ $dropoutCount }} {{ $isAr ? 'انسحاب من الدورات' : 'course dropouts' }}</p>
            </div>
        </div>
    </div>

    {{-- Summary Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
            <h3 class="font-bold text-navy">{{ $isAr ? 'ملخص إحصاءات المنصة' : 'Platform Statistics Summary' }}</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @php
            $platformRows = [
                ['label_ar' => 'إجمالي المتدربين',          'label_en' => 'Total Trainees',          'value' => $stats['students'],             'color' => 'blue-600'],
                ['label_ar' => 'المتدربون النشطون',           'label_en' => 'Active Trainees',          'value' => $activeStudents,                'color' => 'green-600'],
                ['label_ar' => 'المتدربون غير النشطين',       'label_en' => 'Inactive Trainees',        'value' => $inactiveStudents,              'color' => 'red-500'],
                ['label_ar' => 'إجمالي المدربين',            'label_en' => 'Total Trainers',          'value' => $stats['instructors'],          'color' => 'purple-600'],
                ['label_ar' => 'إجمالي الدورات',             'label_en' => 'Total Courses',           'value' => $stats['courses'],              'color' => 'green-600'],
                ['label_ar' => 'إجمالي المجموعات',           'label_en' => 'Total Batches',           'value' => $stats['batches'],              'color' => 'orange-600'],
                ['label_ar' => 'إجمالي التسجيلات',           'label_en' => 'Total Enrollments',       'value' => $stats['enrollments'],          'color' => 'navy'],
                ['label_ar' => 'انسحابات من الدورات',         'label_en' => 'Course Dropouts',         'value' => $dropoutCount,                  'color' => 'red-500'],
                ['label_ar' => 'ملفات المحتوى التدريبي',      'label_en' => 'Training Content Files',  'value' => $totalResources,                'color' => 'indigo-600'],
                ['label_ar' => 'إجمالي تحميلات المحتوى',     'label_en' => 'Total Content Downloads', 'value' => number_format($totalDownloads), 'color' => 'blue-700'],
                ['label_ar' => 'جلسات مباشرة',               'label_en' => 'Live Sessions',           'value' => $totalLiveSessions,             'color' => 'red-600'],
                ['label_ar' => 'إشعارات مُرسلة',             'label_en' => 'Notifications Sent',      'value' => $totalNotifications,            'color' => 'yellow-600'],
                ['label_ar' => 'إجمالي النقاط الممنوحة',     'label_en' => 'Total Points Awarded',    'value' => number_format($totalPoints),    'color' => 'green-600'],
            ];
            @endphp
            @foreach($platformRows as $row)
            <div class="flex items-center justify-between px-6 py-3 hover:bg-gray-50/50">
                <span class="text-sm text-gray-700 font-medium">{{ $isAr ? $row['label_ar'] : $row['label_en'] }}</span>
                <span class="text-sm font-black text-{{ $row['color'] }}" style="font-family:'Roboto',sans-serif">{{ $row['value'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

</div>{{-- end x-data --}}
@endsection
