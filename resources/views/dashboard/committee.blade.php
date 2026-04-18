@extends('layouts.dashboard')
@section('title', 'INSEP PRO - اللجنة العلمية')
@section('dashboard-content')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
<div x-data="committeeManager()">

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-green-700 text-sm font-bold">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-navy">{{ $isAr ? 'اللجنة العلمية للمعهد' : 'Scientific Committee' }}</h1>
            <p class="text-gray-500 text-sm">{{ $isAr ? 'إجمالي' : 'Total' }} {{ $members->count() }} {{ $isAr ? 'عضو' : 'members' }}</p>
        </div>
        <button @click="showAddModal = true" class="bg-red-brand hover:bg-red-brand-dark text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            {{ $isAr ? 'إضافة عضو' : 'Add Member' }}
        </button>
    </div>

    {{-- Members Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($members as $member)
        <div class="bg-white rounded-2xl border border-gray-100 p-6 flex flex-col items-center text-center shadow-sm">
            @if($member->image)
            <img src="{{ str_starts_with($member->image, 'http') ? $member->image : asset('storage/' . ltrim($member->image, '/')) }}"
                 alt="{{ $member->name }}"
                 class="w-24 h-24 rounded-full object-cover mb-4 border-4 border-navy/10"
                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=1B2B4B&color=fff&size=96'">
            @else
            <div class="w-24 h-24 rounded-full bg-navy flex items-center justify-center mb-4 text-white text-2xl font-black">
                {{ mb_substr($member->name, 0, 1) }}
            </div>
            @endif
            <h3 class="font-black text-navy text-lg mb-1">{{ $member->name }}</h3>
            @if($member->title)
            <p class="text-red-brand font-bold text-sm mb-1">{{ $member->title }}</p>
            @endif
            @if($member->specialization)
            <p class="text-gray-500 text-xs mb-3">{{ $member->specialization }}</p>
            @endif
            @if($member->bio)
            <p class="text-gray-600 text-sm leading-relaxed mb-4 line-clamp-3">{{ $member->bio }}</p>
            @endif
            <div class="flex gap-2 mt-auto pt-4 border-t border-gray-100 w-full justify-center">
                <button @click="openEdit({{ $member->id }}, {{ json_encode($member->name) }}, {{ json_encode($member->title ?? '') }}, {{ json_encode($member->specialization ?? '') }}, {{ json_encode($member->bio ?? '') }}, {{ json_encode($member->image ?? '') }}, {{ $member->order }})"
                    class="px-4 py-2 bg-yellow-50 text-yellow-600 rounded-xl text-xs font-bold hover:bg-yellow-100 transition-colors">
                    {{ $isAr ? 'تعديل' : 'Edit' }}
                </button>
                <form method="POST" action="{{ route('dashboard.committee.destroy', $member->id) }}" onsubmit="return confirm('{{ $isAr ? 'هل أنت متأكد؟' : 'Are you sure?' }}')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-50 text-red-500 rounded-xl text-xs font-bold hover:bg-red-100 transition-colors">
                        {{ $isAr ? 'حذف' : 'Delete' }}
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-20 text-gray-400">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
            <p class="font-bold text-gray-400">{{ $isAr ? 'لا يوجد أعضاء بعد' : 'No members yet' }}</p>
        </div>
        @endforelse
    </div>

    {{-- Add Modal --}}
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showAddModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-lg relative z-10 shadow-2xl max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl font-black text-navy mb-6">{{ $isAr ? 'إضافة عضو جديد' : 'Add New Member' }}</h2>
            <form method="POST" action="{{ route('dashboard.committee.store') }}" class="space-y-4" enctype="multipart/form-data">
                @csrf
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'صورة العضو' : 'Member Photo' }}</label>
                    <input type="file" name="image" accept="image/*" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-600 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-navy file:text-white file:text-xs file:font-bold">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الاسم الكامل' : 'Full Name' }} *</label>
                    <input type="text" name="name" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'المنصب / اللقب' : 'Title / Position' }}</label>
                    <input type="text" name="title" placeholder="{{ $isAr ? 'مثال: أستاذ دكتور' : 'e.g. Professor' }}" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'التخصص' : 'Specialization' }}</label>
                    <input type="text" name="specialization" placeholder="{{ $isAr ? 'مثال: علم وظائف الأعضاء الرياضي' : 'e.g. Sports Physiology' }}" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'نبذة مختصرة' : 'Short Bio' }}</label>
                    <textarea name="bio" rows="3" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors text-sm"></textarea>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'ترتيب الظهور' : 'Display Order' }}</label>
                    <input type="number" name="order" value="0" min="0" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
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
            <h2 class="text-xl font-black text-navy mb-6">{{ $isAr ? 'تعديل بيانات العضو' : 'Edit Member' }}</h2>
            <form method="POST" :action="'/dashboard/committee/' + editItem.id" class="space-y-4" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'صورة جديدة (اتركها فارغة للإبقاء على الحالية)' : 'New Photo (leave blank to keep current)' }}</label>
                    <input type="file" name="image" accept="image/*" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-600 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-navy file:text-white file:text-xs file:font-bold">
                    <template x-if="editItem.image">
                        <img :src="editItem.image.startsWith('http') ? editItem.image : '/storage/' + editItem.image" class="mt-2 h-20 w-20 rounded-full object-cover" alt="">
                    </template>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الاسم الكامل' : 'Full Name' }} *</label>
                    <input type="text" name="name" x-model="editItem.name" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'المنصب / اللقب' : 'Title / Position' }}</label>
                    <input type="text" name="title" x-model="editItem.title" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'التخصص' : 'Specialization' }}</label>
                    <input type="text" name="specialization" x-model="editItem.specialization" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'نبذة مختصرة' : 'Short Bio' }}</label>
                    <textarea name="bio" x-model="editItem.bio" rows="3" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors text-sm"></textarea>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'ترتيب الظهور' : 'Display Order' }}</label>
                    <input type="number" name="order" x-model="editItem.order" min="0" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showEditModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200 transition-colors">{{ $isAr ? 'إلغاء' : 'Cancel' }}</button>
                    <button type="submit" class="flex-1 bg-navy hover:bg-navy-dark text-white py-3 rounded-xl font-bold transition-colors">{{ $isAr ? 'حفظ التعديلات' : 'Save Changes' }}</button>
                </div>
            </form>
        </div>
    </div>

</div>
@push('scripts')
<script>
function committeeManager() {
    return {
        showAddModal: false,
        showEditModal: false,
        editItem: { id: null, name: '', title: '', specialization: '', bio: '', image: '', order: 0 },
        openEdit(id, name, title, specialization, bio, image, order) {
            this.editItem = { id, name, title, specialization, bio, image: image || '', order: order || 0 };
            this.showEditModal = true;
        }
    };
}
</script>
@endpush
@endsection
