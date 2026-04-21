@extends('layouts.dashboard')
@section('title', 'INSEP PRO')
@section('dashboard-content')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
<div x-data="examsManager()">

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-green-700 text-sm font-bold">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-navy">
                @if($activeBatch)
                    {{ $isAr ? 'اختبارات:' : 'Exams:' }} {{ $activeBatch->name }}
                @else
                    {{ $isAr ? 'الاختبارات' : 'Exams' }}
                @endif
            </h1>
            <p class="text-gray-500 text-sm">{{ $isAr ? 'إجمالي' : 'Total' }} {{ $exams->count() }} {{ $isAr ? 'اختبار' : 'exams' }}</p>
        </div>
        <div class="flex items-center gap-3">
            @if($activeBatch)
            <a href="{{ route('dashboard.exams') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm font-bold transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                {{ $isAr ? 'إلغاء التصفية' : 'Clear Filter' }}
            </a>
            @endif
            @if(auth()->user()->role !== 'student')
            <button @click="showAddModal = true" class="bg-red-brand hover:bg-red-brand-dark text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                {{ $isAr ? 'إضافة اختبار' : 'Add Exam' }}
            </button>
            @endif
        </div>
    </div>

    {{-- Batch filter pills (admin only) --}}
    @if(auth()->user()->role !== 'instructor' && auth()->user()->role !== 'student' && $batches->count() > 0)
    <div class="mb-5 flex flex-wrap gap-2">
        <a href="{{ route('dashboard.exams') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ !$activeBatch ? 'bg-navy text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-navy hover:text-navy' }}">
            {{ $isAr ? 'الكل' : 'All' }}
        </a>
        @foreach($batches->take(12) as $b)
        <a href="{{ route('dashboard.exams') }}?batch={{ $b->id }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ ($activeBatch?->id === $b->id) ? 'bg-navy text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-navy hover:text-navy' }}">
            {{ $b->name }}
        </a>
        @endforeach
    </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead><tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'العنوان' : 'Title' }}</th>
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'المجموعة' : 'Batch' }}</th>
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'الدورة' : 'Course' }}</th>
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'النوع' : 'Type' }}</th>
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'الأسئلة' : 'Questions' }}</th>
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'المدة' : 'Duration' }}</th>
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'الحالة' : 'Status' }}</th>
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'الرابط' : 'Link' }}</th>
                    @if(auth()->user()->role !== 'student')
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'إجراءات' : 'Actions' }}</th>
                    @endif
                </tr></thead>
                <tbody>
                    @forelse($exams as $exam)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                        <td class="px-6 py-4 font-bold text-navy text-sm">{{ $exam->title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @if($exam->batch)
                            <a href="{{ route('dashboard.exams') }}?batch={{ $exam->batch_id }}" class="font-bold text-navy hover:underline">{{ $exam->batch->name }}</a>
                            @else
                            <span class="text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $exam->course->title ?? '-' }}</td>
                        <td class="px-6 py-4"><span class="px-3 py-1 rounded-lg text-xs font-bold bg-navy/10 text-navy">{{ $exam->type === 'quiz' ? ($isAr ? 'اختبار قصير' : 'Quiz') : ($exam->type === 'final' ? ($isAr ? 'نهائي' : 'Final') : ($exam->type === 'midterm' ? ($isAr ? 'منتصف الفصل' : 'Midterm') : $exam->type)) }}</span></td>
                        <td class="px-6 py-4 text-sm text-gray-600" style="font-family:'Roboto',sans-serif">{{ $exam->questions }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $exam->duration ? $exam->duration . ($isAr ? ' د' : ' min') : '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $exam->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $exam->status === 'active' ? ($isAr ? 'نشط' : 'Active') : ($isAr ? 'مخفي' : 'Hidden') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($exam->exam_link)
                            <a href="{{ $exam->exam_link }}" target="_blank"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-navy/10 hover:bg-navy/20 text-navy rounded-lg text-xs font-bold transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
                                {{ $isAr ? 'ابدأ الاختبار' : 'Start Exam' }}
                            </a>
                            @else
                            <span class="text-gray-300 text-xs">-</span>
                            @endif
                        </td>
                        @if(auth()->user()->role !== 'student')
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button @click="openEdit({{ $exam->id }}, '{{ addslashes($exam->title) }}', {{ $exam->batch_id ?? 'null' }}, {{ $exam->course_id }}, '{{ $exam->type }}', {{ $exam->questions }}, '{{ $exam->duration ?? '' }}', {{ $exam->attempts }}, '{{ $exam->status }}', '{{ addslashes($exam->exam_link ?? '') }}')"
                                    class="p-2 hover:bg-yellow-50 rounded-lg transition-colors text-yellow-500" title="{{ $isAr ? 'تعديل' : 'Edit' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>
                                <form method="POST" action="{{ route('dashboard.exams.destroy', $exam->id) }}" onsubmit="return confirm('{{ $isAr ? 'هل أنت متأكد من حذف هذا الاختبار؟' : 'Are you sure you want to delete this exam?' }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 hover:bg-red-50 rounded-lg transition-colors text-red-500" title="{{ $isAr ? 'حذف' : 'Delete' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-12 text-gray-400">{{ $isAr ? 'لا يوجد اختبارات بعد' : 'No exams found yet' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add Modal --}}
    @if(auth()->user()->role !== 'student')
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showAddModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-lg relative z-10 shadow-2xl max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl font-black text-navy mb-6">{{ $isAr ? 'إضافة اختبار جديد' : 'Add New Exam' }}</h2>
            <form method="POST" action="{{ route('dashboard.exams.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="batch_id_default" value="{{ $activeBatch?->id ?? '' }}">
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'عنوان الاختبار' : 'Exam Title' }}</label>
                    <input type="text" name="title" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'المجموعة' : 'Batch' }}</label>
                    <select name="batch_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        <option value="">{{ $isAr ? '-- اختر المجموعة --' : '-- Select Batch --' }}</option>
                        @foreach($batches as $b)
                        <option value="{{ $b->id }}" {{ ($activeBatch?->id === $b->id) ? 'selected' : '' }}>{{ $b->name }} — {{ $b->course->title ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
                @if(auth()->user()->role !== 'instructor')
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الدورة (اختياري إذا اخترت مجموعة)' : 'Course (optional if batch selected)' }}</label>
                    <select name="course_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        <option value="">{{ $isAr ? '-- اختر الدورة --' : '-- Select Course --' }}</option>
                        @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'رابط الاختبار' : 'Exam Link' }}</label>
                    <input type="url" name="exam_link" placeholder="https://..." class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'نوع الاختبار' : 'Exam Type' }}</label>
                        <select name="type" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="quiz">{{ $isAr ? 'اختبار قصير' : 'Quiz' }}</option>
                            <option value="final">{{ $isAr ? 'نهائي' : 'Final' }}</option>
                            <option value="midterm">{{ $isAr ? 'منتصف الفصل' : 'Midterm' }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الحالة' : 'Status' }}</label>
                        <select name="status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="active">{{ $isAr ? 'نشط' : 'Active' }}</option>
                            <option value="hidden">{{ $isAr ? 'مخفي' : 'Hidden' }}</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'عدد الأسئلة' : 'Questions' }}</label>
                        <input type="number" name="questions" value="30" min="1" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'المدة (د)' : 'Duration (min)' }}</label>
                        <input type="number" name="duration" min="1" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'المحاولات' : 'Attempts' }}</label>
                        <input type="number" name="attempts" value="1" min="1" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showAddModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200 transition-colors">{{ $isAr ? 'إلغاء' : 'Cancel' }}</button>
                    <button type="submit" class="flex-1 bg-navy hover:bg-navy-dark text-white py-3 rounded-xl font-bold transition-colors">{{ $isAr ? 'حفظ' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showEditModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-lg relative z-10 shadow-2xl max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl font-black text-navy mb-6">{{ $isAr ? 'تعديل الاختبار' : 'Edit Exam' }}</h2>
            <form method="POST" :action="'/dashboard/exams/' + editItem.id" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'عنوان الاختبار' : 'Exam Title' }}</label>
                    <input type="text" name="title" x-model="editItem.title" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'المجموعة' : 'Batch' }}</label>
                    <select name="batch_id" x-model="editItem.batch_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        <option value="">{{ $isAr ? '-- اختر المجموعة --' : '-- Select Batch --' }}</option>
                        @foreach($batches as $b)
                        <option value="{{ $b->id }}">{{ $b->name }} — {{ $b->course->title ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
                @if(auth()->user()->role !== 'instructor')
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الدورة' : 'Course' }}</label>
                    <select name="course_id" x-model="editItem.course_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'رابط الاختبار' : 'Exam Link' }}</label>
                    <input type="url" name="exam_link" x-model="editItem.exam_link" placeholder="https://..." class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'نوع الاختبار' : 'Exam Type' }}</label>
                        <select name="type" x-model="editItem.type" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="quiz">{{ $isAr ? 'اختبار قصير' : 'Quiz' }}</option>
                            <option value="final">{{ $isAr ? 'نهائي' : 'Final' }}</option>
                            <option value="midterm">{{ $isAr ? 'منتصف الفصل' : 'Midterm' }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الحالة' : 'Status' }}</label>
                        <select name="status" x-model="editItem.status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="active">{{ $isAr ? 'نشط' : 'Active' }}</option>
                            <option value="hidden">{{ $isAr ? 'مخفي' : 'Hidden' }}</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'عدد الأسئلة' : 'Questions' }}</label>
                        <input type="number" name="questions" x-model="editItem.questions" min="1" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'المدة (د)' : 'Duration (min)' }}</label>
                        <input type="number" name="duration" x-model="editItem.duration" min="1" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'المحاولات' : 'Attempts' }}</label>
                        <input type="number" name="attempts" x-model="editItem.attempts" min="1" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showEditModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200 transition-colors">{{ $isAr ? 'إلغاء' : 'Cancel' }}</button>
                    <button type="submit" class="flex-1 bg-navy hover:bg-navy-dark text-white py-3 rounded-xl font-bold transition-colors">{{ $isAr ? 'حفظ التعديلات' : 'Save Changes' }}</button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
@push('scripts')
<script>
function examsManager() {
    return {
        showAddModal: false,
        showEditModal: false,
        editItem: { id: null, title: '', exam_link: '', batch_id: '', course_id: '', type: 'quiz', questions: 30, duration: '', attempts: 1, status: 'active' },
        openEdit(id, title, batch_id, course_id, type, questions, duration, attempts, status, exam_link) {
            this.editItem = { id, title, exam_link: exam_link || '', batch_id: batch_id || '', course_id, type, questions, duration, attempts, status };
            this.showEditModal = true;
        }
    };
}
</script>
@endpush
@endsection
