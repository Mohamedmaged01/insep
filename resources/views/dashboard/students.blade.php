@extends('layouts.dashboard')
@section('title', 'INSEP PRO - إدارة الطلاب')

@section('dashboard-content')
<div x-data="studentsManager()">
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
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="relative flex-1">
                <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
                <input type="text" x-model="search" placeholder="بحث عن طالب..." class="w-full bg-gray-50 border border-gray-200 rounded-xl pr-10 pl-4 py-2.5 text-sm">
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">#</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الطالب</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">البريد الإلكتروني</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الهاتف</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الحالة</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">تاريخ التسجيل</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $i => $student)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors" x-show="!search || '{{ $student->name }} {{ $student->email }}'.toLowerCase().includes(search.toLowerCase())">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $i + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-navy to-navy-light flex items-center justify-center text-white font-bold text-sm">{{ mb_substr($student->name, 0, 1) }}</div>
                                <span class="font-bold text-navy text-sm">{{ $student->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600" style="font-family: 'Roboto', sans-serif">{{ $student->email }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600" style="font-family: 'Roboto', sans-serif">{{ $student->phone ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-lg text-xs font-bold {{ ($student->status ?? 'active') === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ($student->status ?? 'active') === 'active' ? 'نشط' : 'معلق' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $student->created_at?->format('Y-m-d') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button class="p-2 hover:bg-blue-50 rounded-lg transition-colors text-blue-500" title="عرض">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                                <button class="p-2 hover:bg-red-50 rounded-lg transition-colors text-red-500" title="حذف">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-400">لا يوجد طلاب مسجلين بعد</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add Student Modal --}}
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showAddModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-lg relative z-10 shadow-2xl animate-scaleIn">
            <h2 class="text-xl font-black text-navy mb-6">إضافة طالب جديد</h2>
            <form method="POST" action="/api/users" class="space-y-4">
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الاسم الكامل</label>
                    <input type="text" name="name" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">البريد الإلكتروني</label>
                    <input type="email" name="email" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 transition-colors" dir="ltr" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">كلمة المرور</label>
                    <input type="password" name="password" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 transition-colors" dir="ltr" required>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showAddModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold transition-colors hover:bg-gray-200">إلغاء</button>
                    <button type="submit" class="flex-1 bg-navy hover:bg-navy-dark text-white py-3 rounded-xl font-bold transition-colors">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function studentsManager() {
    return { search: '', showAddModal: false };
}
</script>
@endpush
@endsection
