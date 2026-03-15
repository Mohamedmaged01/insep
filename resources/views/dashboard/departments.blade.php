@extends('layouts.dashboard')
@section('title', 'INSEP PRO - الأقسام')
@section('dashboard-content')
<div x-data="departmentsManager()">

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-green-700 text-sm font-bold">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-navy">الأقسام</h1>
            <p class="text-gray-500 text-sm">إجمالي {{ $departments->count() }} قسم</p>
        </div>
        <button @click="showAddModal = true" class="bg-red-brand hover:bg-red-brand-dark text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            إضافة قسم
        </button>
    </div>

    {{-- Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($departments as $dept)
        <div class="bg-white rounded-2xl p-6 border border-gray-100 card-hover">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-navy to-navy-light flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                </div>
                <div class="flex gap-2">
                    <button @click="openEdit({{ $dept->id }}, '{{ addslashes($dept->name_ar) }}', '{{ addslashes($dept->name_en) }}')"
                        class="p-2 hover:bg-yellow-50 rounded-lg transition-colors text-yellow-500" title="تعديل">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                    <form method="POST" action="{{ route('dashboard.departments.destroy', $dept->id) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا القسم؟')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 hover:bg-red-50 rounded-lg transition-colors text-red-500" title="حذف">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                        </button>
                    </form>
                </div>
            </div>
            <h3 class="text-lg font-bold text-navy mb-1">{{ $dept->name_ar }}</h3>
            <p class="text-sm text-gray-500">{{ $dept->name_en }}</p>
        </div>
        @empty
        <div class="col-span-3 text-center py-12 text-gray-400">لا يوجد أقسام بعد</div>
        @endforelse
    </div>

    {{-- Add Modal --}}
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showAddModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-md relative z-10 shadow-2xl">
            <h2 class="text-xl font-black text-navy mb-6">إضافة قسم جديد</h2>
            <form method="POST" action="{{ route('dashboard.departments.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الاسم بالعربية</label>
                    <input type="text" name="name_ar" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الاسم بالإنجليزية</label>
                    <input type="text" name="name_en" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
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
        <div class="bg-white rounded-2xl p-8 w-full max-w-md relative z-10 shadow-2xl">
            <h2 class="text-xl font-black text-navy mb-6">تعديل القسم</h2>
            <form method="POST" :action="'/dashboard/departments/' + editItem.id" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الاسم بالعربية</label>
                    <input type="text" name="name_ar" x-model="editItem.name_ar" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الاسم بالإنجليزية</label>
                    <input type="text" name="name_en" x-model="editItem.name_en" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
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
function departmentsManager() {
    return {
        showAddModal: false,
        showEditModal: false,
        editItem: { id: null, name_ar: '', name_en: '' },
        openEdit(id, name_ar, name_en) {
            this.editItem = { id, name_ar, name_en };
            this.showEditModal = true;
        }
    };
}
</script>
@endpush
@endsection
