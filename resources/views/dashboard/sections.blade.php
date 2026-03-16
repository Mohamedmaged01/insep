@extends('layouts.dashboard')
@section('title', 'INSEP PRO - الشعب التدريبية')
@section('dashboard-content')
<div x-data="sectionsManager()">

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-green-700 text-sm font-bold">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-navy">الشعب التدريبية</h1>
            <p class="text-gray-500 text-sm">إجمالي {{ $sections->count() }} شعبة</p>
        </div>
        <button @click="showAddModal = true" class="bg-red-brand hover:bg-red-brand-dark text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            إضافة شعبة
        </button>
    </div>

    {{-- Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($sections as $section)
        <div class="bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-3">
                <div class="w-11 h-11 bg-navy/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-navy" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/>
                        <rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                </div>
                <div class="flex items-center gap-1">
                    <button @click="openEdit({{ $section->id }}, '{{ addslashes($section->name_ar) }}', '{{ addslashes($section->name_en) }}', '{{ addslashes($section->description ?? '') }}')"
                        class="p-2 hover:bg-yellow-50 rounded-lg transition-colors text-yellow-500" title="تعديل">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                    <form method="POST" action="{{ route('dashboard.sections.destroy', $section->id) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذه الشعبة؟')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 hover:bg-red-50 rounded-lg transition-colors text-red-500" title="حذف">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                        </button>
                    </form>
                </div>
            </div>
            <h3 class="font-black text-navy text-base mb-0.5">{{ $section->name_ar }}</h3>
            <p class="text-gray-400 text-xs mb-2" style="font-family:'Roboto',sans-serif">{{ $section->name_en }}</p>
            @if($section->description)
            <p class="text-gray-500 text-sm line-clamp-2 mb-3">{{ $section->description }}</p>
            @endif
            <div class="flex items-center gap-2 pt-3 border-t border-gray-50">
                <span class="text-xs text-gray-400">{{ $section->courses_count ?? 0 }} دورة</span>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-16 text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <rect x="3" y="3" width="7" height="7" rx="1"/>
                <rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="3" y="14" width="7" height="7" rx="1"/>
                <rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
            لا توجد شعب بعد
        </div>
        @endforelse
    </div>

    {{-- Add Modal --}}
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showAddModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-md relative z-10 shadow-2xl">
            <h2 class="text-xl font-black text-navy mb-6">إضافة شعبة جديدة</h2>
            <form method="POST" action="{{ route('dashboard.sections.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الاسم بالعربية</label>
                    <input type="text" name="name_ar" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الاسم بالإنجليزية</label>
                    <input type="text" name="name_en" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الوصف</label>
                    <textarea name="description" rows="3" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors"></textarea>
                </div>
                <div class="flex gap-3 pt-2">
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
            <h2 class="text-xl font-black text-navy mb-6">تعديل الشعبة</h2>
            <form method="POST" :action="'/dashboard/sections/' + editItem.id" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الاسم بالعربية</label>
                    <input type="text" name="name_ar" x-model="editItem.name_ar" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الاسم بالإنجليزية</label>
                    <input type="text" name="name_en" x-model="editItem.name_en" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الوصف</label>
                    <textarea name="description" x-model="editItem.description" rows="3" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors"></textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showEditModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200 transition-colors">إلغاء</button>
                    <button type="submit" class="flex-1 bg-navy hover:bg-navy-dark text-white py-3 rounded-xl font-bold transition-colors">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>

</div>
@push('scripts')
<script>
function sectionsManager() {
    return {
        showAddModal: false,
        showEditModal: false,
        editItem: { id: null, name_ar: '', name_en: '', description: '' },
        openEdit(id, name_ar, name_en, description) {
            this.editItem = { id, name_ar, name_en, description };
            this.showEditModal = true;
        }
    };
}
</script>
@endpush
@endsection
