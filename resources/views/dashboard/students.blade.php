@extends('layouts.dashboard')
@section('title', 'INSEP PRO - إدارة الطلاب')
@section('dashboard-content')
<div x-data="studentsManager()">

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-green-700 text-sm font-bold">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-navy">إدارة الطلاب</h1>
            <p class="text-gray-500 text-sm">إجمالي {{ $students->count() }} طالب</p>
        </div>
        <button @click="showAddModal = true" class="bg-red-brand hover:bg-red-brand-dark text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            إضافة طالب
        </button>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-2xl p-4 mb-6 border border-gray-100">
        <div class="relative flex-1">
            <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
            <input type="text" x-model="search" placeholder="بحث عن طالب..." class="w-full bg-gray-50 border border-gray-200 rounded-xl pr-10 pl-4 py-2.5 text-sm">
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">#</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الاسم بالعربي</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الاسم بالإنجليزي</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">البريد الإلكتروني</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الهاتف</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الحالة</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">تاريخ التسجيل</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $i => $student)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors" x-show="!search || '{{ addslashes($student->name_ar ?? $student->name) }} {{ addslashes($student->name_en ?? '') }} {{ $student->email }}'.toLowerCase().includes(search.toLowerCase())">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $i + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-navy to-navy-light flex items-center justify-center text-white font-bold text-sm">{{ mb_substr($student->name_ar ?? $student->name, 0, 1) }}</div>
                                <span class="font-bold text-navy text-sm">{{ $student->name_ar ?? $student->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600" style="font-family:'Roboto',sans-serif">{{ $student->name_en ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600" style="font-family:'Roboto',sans-serif">{{ $student->email }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600" style="font-family:'Roboto',sans-serif">{{ $student->phone ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-lg text-xs font-bold {{ ($student->status ?? 'active') === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ($student->status ?? 'active') === 'active' ? 'نشط' : 'معلق' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $student->created_at?->format('Y-m-d') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button @click="openEdit({{ $student->id }}, '{{ addslashes($student->name_ar ?? $student->name) }}', '{{ addslashes($student->name_en ?? '') }}', '{{ $student->email }}', '{{ $student->phone ?? '' }}', '{{ $student->status ?? 'active' }}')"
                                    class="p-2 hover:bg-yellow-50 rounded-lg transition-colors text-yellow-500" title="تعديل">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>
                                <form method="POST" action="{{ route('dashboard.students.destroy', $student->id) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطالب؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 hover:bg-red-50 rounded-lg transition-colors text-red-500" title="حذف">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-12 text-gray-400">لا يوجد طلاب مسجلين بعد</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add Modal --}}
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showAddModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-lg relative z-10 shadow-2xl">
            <h2 class="text-xl font-black text-navy mb-6">إضافة طالب جديد</h2>
            <form method="POST" action="{{ route('dashboard.students.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الاسم الكامل بالعربي</label>
                    <input type="text" name="name_ar" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الاسم الكامل بالإنجليزي</label>
                    <input type="text" name="name_en" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">البريد الإلكتروني</label>
                    <input type="email" name="email" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">رقم الهاتف</label>
                    <input type="text" name="phone" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">كلمة المرور</label>
                    <input type="password" name="password" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr" required>
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
        <div class="bg-white rounded-2xl p-8 w-full max-w-lg relative z-10 shadow-2xl">
            <h2 class="text-xl font-black text-navy mb-6">تعديل بيانات الطالب</h2>
            <form method="POST" :action="'/dashboard/students/' + editItem.id" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الاسم الكامل بالعربي</label>
                    <input type="text" name="name_ar" x-model="editItem.name_ar" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الاسم الكامل بالإنجليزي</label>
                    <input type="text" name="name_en" x-model="editItem.name_en" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">البريد الإلكتروني</label>
                    <input type="email" name="email" x-model="editItem.email" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">رقم الهاتف</label>
                    <input type="text" name="phone" x-model="editItem.phone" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">كلمة مرور جديدة (اتركها فارغة إن لم ترد تغييرها)</label>
                    <input type="password" name="password" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الحالة</label>
                    <select name="status" x-model="editItem.status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        <option value="active">نشط</option>
                        <option value="suspended">معلق</option>
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
function studentsManager() {
    return {
        search: '',
        showAddModal: false,
        showEditModal: false,
        editItem: { id: null, name_ar: '', name_en: '', email: '', phone: '', status: 'active' },
        openEdit(id, name_ar, name_en, email, phone, status) {
            this.editItem = { id, name_ar, name_en, email, phone, status };
            this.showEditModal = true;
        }
    };
}
</script>
@endpush
@endsection
