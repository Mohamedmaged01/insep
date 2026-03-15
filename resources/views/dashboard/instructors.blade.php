@extends('layouts.dashboard')
@section('title', 'INSEP PRO - إدارة المدربين')
@section('dashboard-content')
<div x-data="instructorsManager()">

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-green-700 text-sm font-bold">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-navy">إدارة المدربين</h1>
            <p class="text-gray-500 text-sm">إجمالي {{ $instructors->count() }} مدرب</p>
        </div>
        <button @click="showAddModal = true" class="bg-red-brand hover:bg-red-brand-dark text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            إضافة مدرب
        </button>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-2xl p-4 mb-6 border border-gray-100">
        <div class="relative">
            <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
            <input type="text" x-model="search" placeholder="بحث عن مدرب..." class="w-full bg-gray-50 border border-gray-200 rounded-xl pr-10 pl-4 py-2.5 text-sm">
        </div>
    </div>

    {{-- Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($instructors as $inst)
        <div class="bg-white rounded-2xl p-6 border border-gray-100 card-hover text-center"
             x-show="!search || '{{ addslashes($inst->name) }} {{ $inst->email }}'.toLowerCase().includes(search.toLowerCase())">
            <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-navy to-navy-light rounded-2xl flex items-center justify-center text-white font-black text-xl">{{ mb_substr($inst->name, 0, 1) }}</div>
            <h3 class="text-lg font-bold text-navy mb-1">{{ $inst->name }}</h3>
            <p class="text-sm text-gray-500 mb-1" style="font-family:'Roboto',sans-serif">{{ $inst->email }}</p>
            <p class="text-xs text-gray-400 mb-1">{{ $inst->phone ?? '-' }}</p>
            @if($inst->specialty)<p class="text-xs text-navy/70 mb-2">{{ $inst->specialty }}</p>@endif
            <span class="inline-block px-3 py-1 rounded-lg text-xs font-bold {{ ($inst->status ?? 'active') === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ ($inst->status ?? 'active') === 'active' ? 'نشط' : 'معلق' }}
            </span>
            <div class="flex justify-center gap-3 mt-4">
                <button @click="openEdit({{ $inst->id }}, '{{ addslashes($inst->name) }}', '{{ $inst->email }}', '{{ $inst->phone ?? '' }}', '{{ addslashes($inst->specialty ?? '') }}', '{{ $inst->status ?? 'active' }}')"
                    class="p-2 hover:bg-yellow-50 rounded-lg transition-colors text-yellow-500" title="تعديل">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </button>
                <form method="POST" action="{{ route('dashboard.instructors.destroy', $inst->id) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا المدرب؟')">
                    @csrf @method('DELETE')
                    <button type="submit" class="p-2 hover:bg-red-50 rounded-lg transition-colors text-red-500" title="حذف">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12 text-gray-400">لا يوجد مدربين مسجلين بعد</div>
        @endforelse
    </div>

    {{-- Add Modal --}}
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showAddModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-lg relative z-10 shadow-2xl">
            <h2 class="text-xl font-black text-navy mb-6">إضافة مدرب جديد</h2>
            <form method="POST" action="{{ route('dashboard.instructors.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الاسم الكامل</label>
                    <input type="text" name="name" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
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
                    <label class="text-sm font-bold text-navy mb-2 block">التخصص</label>
                    <input type="text" name="specialty" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
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
            <h2 class="text-xl font-black text-navy mb-6">تعديل بيانات المدرب</h2>
            <form method="POST" :action="'/dashboard/instructors/' + editItem.id" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الاسم الكامل</label>
                    <input type="text" name="name" x-model="editItem.name" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
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
                    <label class="text-sm font-bold text-navy mb-2 block">التخصص</label>
                    <input type="text" name="specialty" x-model="editItem.specialty" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
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
function instructorsManager() {
    return {
        search: '',
        showAddModal: false,
        showEditModal: false,
        editItem: { id: null, name: '', email: '', phone: '', specialty: '', status: 'active' },
        openEdit(id, name, email, phone, specialty, status) {
            this.editItem = { id, name, email, phone, specialty, status };
            this.showEditModal = true;
        }
    };
}
</script>
@endpush
@endsection
