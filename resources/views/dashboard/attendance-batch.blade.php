@extends('layouts.dashboard')
@section('title', 'INSEP PRO')
@section('dashboard-content')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp

{{-- Flash --}}
@if(session('success'))
<div class="mb-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-green-700 text-sm font-bold">{{ session('success') }}</div>
@endif

{{-- Header --}}
<div class="flex items-center gap-4 mb-6">
    <a href="{{ route('dashboard.attendance') }}" class="p-2 hover:bg-gray-100 rounded-xl transition-colors text-gray-500">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
        <h1 class="text-2xl font-black text-navy">{{ $batch->name }}</h1>
        <p class="text-gray-500 text-sm">{{ $batch->course->title ?? '-' }} &bull; {{ $enrolled->count() }} {{ $isAr ? 'طالب' : 'students' }}</p>
    </div>
</div>

{{-- Date Selector --}}
<div class="bg-white rounded-2xl p-5 border border-gray-100 mb-6">
    <div class="flex flex-wrap items-center gap-4">
        <div>
            <label class="text-sm font-bold text-navy mb-1 block">{{ $isAr ? 'تاريخ الحضور' : 'Attendance Date' }}</label>
            <form method="GET" action="{{ route('dashboard.attendance.batch', $batch->id) }}" class="flex gap-3">
                <input type="date" name="date" value="{{ $selectedDate }}" class="border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:border-navy transition-colors" dir="ltr">
                <button type="submit" class="bg-navy text-white px-4 py-2.5 rounded-xl font-bold text-sm hover:bg-navy-dark transition-colors">{{ $isAr ? 'عرض' : 'View' }}</button>
            </form>
        </div>
        @if($dates->count() > 0)
        <div>
            <label class="text-sm font-bold text-navy mb-1 block">{{ $isAr ? 'التواريخ السابقة' : 'Previous Dates' }}</label>
            <div class="flex flex-wrap gap-2">
                @foreach($dates->take(5) as $d)
                <a href="{{ route('dashboard.attendance.batch', $batch->id) }}?date={{ $d }}"
                    class="px-3 py-1.5 rounded-lg text-xs font-bold {{ $d === $selectedDate ? 'bg-navy text-white' : 'bg-gray-100 text-gray-600 hover:bg-navy/10' }} transition-colors">
                    {{ $d }}
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Attendance Form --}}
<form method="POST" action="{{ route('dashboard.attendance.store', $batch->id) }}">
    @csrf
    <input type="hidden" name="date" value="{{ $selectedDate }}">

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden mb-6">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-navy">{{ $isAr ? 'حضور يوم' : 'Attendance for' }} {{ $selectedDate }}</h3>
            <div class="flex gap-3 text-xs font-bold">
                <button type="button" onclick="setAll('present')" class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors">{{ $isAr ? 'تحديد الكل حاضر' : 'Mark All Present' }}</button>
                <button type="button" onclick="setAll('absent')" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">{{ $isAr ? 'تحديد الكل غائب' : 'Mark All Absent' }}</button>
            </div>
        </div>
        @if($enrolled->count() > 0)
        <div class="divide-y divide-gray-50">
            @foreach($enrolled as $student)
            @php $record = $records->get($student->id); @endphp
            <div class="flex items-center gap-4 px-6 py-4">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-navy to-navy-light flex items-center justify-center text-white font-bold text-sm flex-shrink-0">{{ mb_substr($student->name, 0, 1) }}</div>
                <div class="flex-1">
                    <p class="font-bold text-navy text-sm">{{ $student->name }}</p>
                    <p class="text-xs text-gray-500" style="font-family:'Roboto',sans-serif">{{ $student->email }}</p>
                </div>
                <div class="flex gap-3 items-center">
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="radio" name="statuses[{{ $student->id }}]" value="present" class="attendance-radio" {{ ($record?->status ?? 'present') === 'present' ? 'checked' : '' }}>
                        <span class="text-xs font-bold text-green-600">{{ $isAr ? 'حاضر' : 'Present' }}</span>
                    </label>
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="radio" name="statuses[{{ $student->id }}]" value="absent" class="attendance-radio" {{ ($record?->status ?? '') === 'absent' ? 'checked' : '' }}>
                        <span class="text-xs font-bold text-red-600">{{ $isAr ? 'غائب' : 'Absent' }}</span>
                    </label>
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="radio" name="statuses[{{ $student->id }}]" value="excused" class="attendance-radio" {{ ($record?->status ?? '') === 'excused' ? 'checked' : '' }}>
                        <span class="text-xs font-bold text-yellow-600">{{ $isAr ? 'معذور' : 'Excused' }}</span>
                    </label>
                    <input type="text" name="notes[{{ $student->id }}]" value="{{ $record?->notes ?? '' }}" placeholder="{{ $isAr ? 'ملاحظة...' : 'Note...' }}" class="border border-gray-200 rounded-lg px-3 py-1.5 text-xs w-32 focus:border-navy transition-colors">
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-center py-12 text-gray-400">{{ $isAr ? 'لا يوجد طلاب مسجلون في هذه المجموعة' : 'No students enrolled in this batch' }}</p>
        @endif
    </div>

    @if($enrolled->count() > 0)
    <button type="submit" class="w-full bg-navy hover:bg-navy-dark text-white py-4 rounded-2xl font-black text-lg transition-colors shadow-xl">
        {{ $isAr ? 'حفظ الحضور' : 'Save Attendance' }}
    </button>
    @endif
</form>

@push('scripts')
<script>
function setAll(status) {
    document.querySelectorAll(`input[type="radio"][value="${status}"]`).forEach(r => r.checked = true);
}
</script>
@endpush
@endsection
