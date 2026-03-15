@extends('layouts.dashboard')
@section('title', 'INSEP PRO - المجموعات التدريبية')
@section('dashboard-content')
<div x-data="batchesManager()">

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-green-700 text-sm font-bold">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-navy">المجموعات التدريبية</h1>
            <p class="text-gray-500 text-sm">إجمالي {{ $batches->count() }} مجموعة</p>
        </div>
        <button @click="showAddModal = true" class="bg-red-brand hover:bg-red-brand-dark text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            إضافة مجموعة
        </button>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">#</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الاسم</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الدورة</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">المدرب</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">تاريخ البدء</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الحد الأقصى</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الحالة</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batches as $i => $batch)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $i + 1 }}</td>
                        <td class="px-6 py-4 font-bold text-navy text-sm">{{ $batch->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $batch->course->title ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $batch->instructor->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $batch->start_date ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $batch->max_students }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-lg text-xs font-bold {{ ($batch->status ?? 'active') === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ ($batch->status ?? 'active') === 'active' ? 'نشطة' : 'منتهية' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('dashboard.batches.detail', $batch->id) }}"
                                    class="p-2 hover:bg-blue-50 rounded-lg transition-colors text-blue-500" title="تفاصيل">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                <button @click="openEdit({{ $batch->id }}, '{{ addslashes($batch->name) }}', {{ $batch->course_id }}, {{ $batch->instructor_id }}, '{{ $batch->start_date ?? '' }}', '{{ $batch->end_date ?? '' }}', {{ $batch->max_students }}, '{{ $batch->status ?? 'active' }}')"
                                    class="p-2 hover:bg-yellow-50 rounded-lg transition-colors text-yellow-500" title="تعديل">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>
                                <form method="POST" action="{{ route('dashboard.batches.destroy', $batch->id) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذه المجموعة؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 hover:bg-red-50 rounded-lg transition-colors text-red-500" title="حذف">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-12 text-gray-400">لا يوجد مجموعات بعد</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add Modal --}}
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showAddModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-lg relative z-10 shadow-2xl max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl font-black text-navy mb-6">إضافة مجموعة جديدة</h2>
            <form method="POST" action="{{ route('dashboard.batches.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">اسم المجموعة</label>
                    <input type="text" name="name" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
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
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">المدرب</label>
                    <select name="instructor_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                        <option value="">-- اختر المدرب --</option>
                        @foreach($instructors as $inst)
                        <option value="{{ $inst->id }}">{{ $inst->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">تاريخ البدء</label>
                        <input type="date" name="start_date" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">تاريخ الانتهاء</label>
                        <input type="date" name="end_date" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الحد الأقصى للطلاب</label>
                    <input type="number" name="max_students" value="30" min="1" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الحالة</label>
                    <select name="status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        <option value="active">نشطة</option>
                        <option value="ended">منتهية</option>
                    </select>
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
            <h2 class="text-xl font-black text-navy mb-6">تعديل المجموعة</h2>
            <form method="POST" :action="'/dashboard/batches/' + editItem.id" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">اسم المجموعة</label>
                    <input type="text" name="name" x-model="editItem.name" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الدورة</label>
                    <select name="course_id" x-model="editItem.course_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">المدرب</label>
                    <select name="instructor_id" x-model="editItem.instructor_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        @foreach($instructors as $inst)
                        <option value="{{ $inst->id }}">{{ $inst->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">تاريخ البدء</label>
                        <input type="date" name="start_date" x-model="editItem.start_date" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">تاريخ الانتهاء</label>
                        <input type="date" name="end_date" x-model="editItem.end_date" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الحد الأقصى للطلاب</label>
                    <input type="number" name="max_students" x-model="editItem.max_students" min="1" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الحالة</label>
                    <select name="status" x-model="editItem.status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        <option value="active">نشطة</option>
                        <option value="ended">منتهية</option>
                    </select>
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
function batchesManager() {
    return {
        showAddModal: false,
        showEditModal: false,
        editItem: { id: null, name: '', course_id: '', instructor_id: '', start_date: '', end_date: '', max_students: 30, status: 'active' },
        openEdit(id, name, course_id, instructor_id, start_date, end_date, max_students, status) {
            this.editItem = { id, name, course_id, instructor_id, start_date, end_date, max_students, status };
            this.showEditModal = true;
        }
    };
}
</script>
@endpush
@endsection
