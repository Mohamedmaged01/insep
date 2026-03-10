@extends('layouts.dashboard')
@section('title', 'INSEP PRO - التقارير')
@section('dashboard-content')
<h1 class="text-2xl font-black text-navy mb-6">التقارير</h1>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach([
        ['title' => 'تقرير الإيرادات', 'desc' => 'تقرير مفصل عن الإيرادات والمصروفات', 'color' => 'from-green-500 to-green-600'],
        ['title' => 'تقرير الطلاب', 'desc' => 'إحصائيات وتقارير عن الطلاب المسجلين', 'color' => 'from-blue-500 to-blue-600'],
        ['title' => 'تقرير الدورات', 'desc' => 'نظرة عامة على أداء الدورات التدريبية', 'color' => 'from-purple-500 to-purple-600'],
        ['title' => 'تقرير الحضور', 'desc' => 'إحصائيات الحضور والغياب للمتدربين', 'color' => 'from-orange-500 to-orange-600'],
        ['title' => 'تقرير الشهادات', 'desc' => 'تتبع الشهادات الصادرة والمعلقة', 'color' => 'from-red-500 to-red-600'],
        ['title' => 'تقرير عام', 'desc' => 'ملخص شامل لأداء المعهد', 'color' => 'from-navy to-navy-light'],
    ] as $report)
    <div class="bg-white rounded-2xl p-6 border border-gray-100 card-hover">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $report['color'] }} flex items-center justify-center mb-4 shadow-lg">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 20V10M18 20V4M6 20v-4"/></svg>
        </div>
        <h3 class="font-bold text-navy mb-2">{{ $report['title'] }}</h3>
        <p class="text-sm text-gray-500 mb-4">{{ $report['desc'] }}</p>
        <button class="bg-navy/10 text-navy px-4 py-2 rounded-xl text-sm font-bold hover:bg-navy/20 transition-colors">عرض التقرير</button>
    </div>
    @endforeach
</div>
@endsection
