@extends('layouts.dashboard')
@section('title', 'INSEP PRO - الاختبارات')
@section('dashboard-content')
<div x-data="examsManager()">

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-green-700 text-sm font-bold">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-navy">الاختبارات</h1>
            <p class="text-gray-500 text-sm">إجمالي {{ $exams->count() }} اختبار</p>
        </div>
        @if(auth()->user()->role !== 'student')
        <button @click="showAddModal = true" class="bg-red-brand hover:bg-red-brand-dark text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            إضافة اختبار
        </button>
        @endif
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead><tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">العنوان</th>
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الدورة</th>
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">النوع</th>
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الأسئلة</th>
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">المدة</th>
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">المحاولات</th>
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الحالة</th>
                    @if(auth()->user()->role !== 'student')
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">إجراءات</th>
                    @endif
                </tr></thead>
                <tbody>
                    @forelse($exams as $exam)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                        <td class="px-6 py-4 font-bold text-navy text-sm">{{ $exam->title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $exam->course->title ?? '-' }}</td>
                        <td class="px-6 py-4"><span class="px-3 py-1 rounded-lg text-xs font-bold bg-navy/10 text-navy">{{ $exam->type === 'quiz' ? 'اختبار قصير' : ($exam->type === 'final' ? 'نهائي' : $exam->type) }}</span></td>
                        <td class="px-6 py-4 text-sm text-gray-600" style="font-family:'Roboto',sans-serif">{{ $exam->questions }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $exam->duration ? $exam->duration . ' د' : '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600" style="font-family:'Roboto',sans-serif">{{ $exam->attempts }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $exam->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $exam->status === 'active' ? 'نشط' : 'مخفي' }}
                            </span>
                        </td>
                        @if(auth()->user()->role !== 'student')
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button @click="openEdit({{ $exam->id }}, '{{ addslashes($exam->title) }}', {{ $exam->course_id }}, '{{ $exam->type }}', {{ $exam->questions }}, '{{ $exam->duration ?? '' }}', {{ $exam->attempts }}, '{{ $exam->status }}')"
                                    class="p-2 hover:bg-yellow-50 rounded-lg transition-colors text-yellow-500" title="تعديل">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>
                                <form method="POST" action="{{ route('dashboard.exams.destroy', $exam->id) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا الاختبار؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 hover:bg-red-50 rounded-lg transition-colors text-red-500" title="حذف">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-12 text-gray-400">لا يوجد اختبارات بعد</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add Modal --}}
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showAddModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-lg relative z-10 shadow-2xl max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl font-black text-navy mb-6">إضافة اختبار جديد</h2>
            <form method="POST" action="{{ route('dashboard.exams.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">عنوان الاختبار</label>
                    <input type="text" name="title" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الدورة</label>
                    <select name="course_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                        <option value="">-- اختر الدورة --</option>
                        @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">نوع الاختبار</label>
                        <select name="type" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="quiz">اختبار قصير</option>
                            <option value="final">نهائي</option>
                            <option value="midterm">منتصف الفصل</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">الحالة</label>
                        <select name="status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="active">نشط</option>
                            <option value="hidden">مخفي</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">عدد الأسئلة</label>
                        <input type="number" name="questions" value="30" min="1" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">المدة (دقيقة)</label>
                        <input type="number" name="duration" min="1" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">المحاولات</label>
                        <input type="number" name="attempts" value="1" min="1" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showAddModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200 transition-colors">إلغاء</button>
                    <button type="submit" class="flex-1 bg-navy hover:bg-navy-dark text-white py-3 rounded-xl font-bold transition-colors">حفظ</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showEditModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-lg relative z-10 shadow-2xl max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl font-black text-navy mb-6">تعديل الاختبار</h2>
            <form method="POST" :action="'/dashboard/exams/' + editItem.id" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">عنوان الاختبار</label>
                    <input type="text" name="title" x-model="editItem.title" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الدورة</label>
                    <select name="course_id" x-model="editItem.course_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">نوع الاختبار</label>
                        <select name="type" x-model="editItem.type" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="quiz">اختبار قصير</option>
                            <option value="final">نهائي</option>
                            <option value="midterm">منتصف الفصل</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">الحالة</label>
                        <select name="status" x-model="editItem.status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="active">نشط</option>
                            <option value="hidden">مخفي</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">عدد الأسئلة</label>
                        <input type="number" name="questions" x-model="editItem.questions" min="1" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">المدة (دقيقة)</label>
                        <input type="number" name="duration" x-model="editItem.duration" min="1" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">المحاولات</label>
                        <input type="number" name="attempts" x-model="editItem.attempts" min="1" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showEditModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200 transition-colors">إلغاء</button>
                    <button type="submit" class="flex-1 bg-navy hover:bg-navy-dark text-white py-3 rounded-xl font-bold transition-colors">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>

</div>
@push('scripts')
<script>
function examsManager() {
    return {
        showAddModal: false,
        showEditModal: false,
        editItem: { id: null, title: '', course_id: '', type: 'quiz', questions: 30, duration: '', attempts: 1, status: 'active' },
        openEdit(id, title, course_id, type, questions, duration, attempts, status) {
            this.editItem = { id, title, course_id, type, questions, duration, attempts, status };
            this.showEditModal = true;
        }
    };
}
</script>
@endpush
@endsection
