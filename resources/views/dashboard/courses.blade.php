@extends('layouts.dashboard')
@section('title', 'INSEP PRO - إدارة الدورات')
@section('dashboard-content')
<div x-data="coursesManager()">

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-green-700 text-sm font-bold">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-navy">إدارة الدورات</h1>
            <p class="text-gray-500 text-sm">إجمالي {{ $courses->count() }} دورة</p>
        </div>
        <button @click="showAddModal = true" class="bg-red-brand hover:bg-red-brand-dark text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            إضافة دورة
        </button>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-2xl p-4 mb-6 border border-gray-100">
        <div class="relative">
            <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
            <input type="text" x-model="search" placeholder="بحث عن دورة..." class="w-full bg-gray-50 border border-gray-200 rounded-xl pr-10 pl-4 py-2.5 text-sm">
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">#</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الدورة</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الشعبة</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">التصنيف</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">المستوى</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">السعر</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الحالة</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">المسجلين</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $i => $course)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors"
                        x-show="!search || '{{ addslashes($course->title) }} {{ $course->category }}'.toLowerCase().includes(search.toLowerCase())">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $i + 1 }}</td>
                        <td class="px-6 py-4 font-bold text-navy text-sm">{{ $course->title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $course->section->name_ar ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $course->category ?? '-' }}</td>
                        <td class="px-6 py-4"><span class="px-3 py-1 rounded-lg text-xs font-bold bg-navy/10 text-navy">{{ $course->level ?? '-' }}</span></td>
                        <td class="px-6 py-4 text-sm font-bold text-red-brand" style="font-family:'Roboto',sans-serif">{{ $course->currency ?? 'USD' }} {{ $course->price }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-lg text-xs font-bold {{ ($course->status ?? 'active') === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ ($course->status ?? 'active') === 'active' ? 'نشطة' : 'مخفية' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $course->enrollments_count ?? 0 }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button @click="openEdit({{ $course->id }}, '{{ addslashes($course->title) }}', '{{ addslashes($course->description ?? '') }}', '{{ $course->category ?? '' }}', {{ $course->price ?? 0 }}, '{{ $course->currency ?? 'USD' }}', '{{ $course->duration ?? '' }}', '{{ $course->level ?? '' }}', '{{ $course->status ?? 'active' }}', {{ $course->section_id ?? 'null' }})"
                                    class="p-2 hover:bg-yellow-50 rounded-lg transition-colors text-yellow-500" title="تعديل">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>
                                <form method="POST" action="{{ route('dashboard.courses.destroy', $course->id) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذه الدورة؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 hover:bg-red-50 rounded-lg transition-colors text-red-500" title="حذف">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-12 text-gray-400">لا يوجد دورات بعد</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add Modal --}}
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showAddModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-lg relative z-10 shadow-2xl max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl font-black text-navy mb-6">إضافة دورة جديدة</h2>
            <form method="POST" action="{{ route('dashboard.courses.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">عنوان الدورة</label>
                    <input type="text" name="title" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الوصف</label>
                    <textarea name="description" rows="3" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors"></textarea>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الشعبة التدريبية</label>
                    <select name="section_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        <option value="">-- بدون شعبة --</option>
                        @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name_ar }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">التصنيف</label>
                        <input type="text" name="category" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">المستوى</label>
                        <select name="level" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="مبتدئ">مبتدئ</option>
                            <option value="متوسط">متوسط</option>
                            <option value="متقدم">متقدم</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">السعر والعملة</label>
                    <div class="flex gap-2">
                        <div class="flex rounded-xl border-2 border-gray-200 overflow-hidden focus-within:border-navy transition-colors flex-shrink-0">
                            @foreach([['USD','$ دولار'],['EGP','ج.م'],['SAR','ر.س']] as [$val,$lbl])
                            <label class="flex items-center gap-1.5 px-3 py-3 cursor-pointer text-sm font-bold transition-colors has-[:checked]:bg-navy has-[:checked]:text-white text-gray-500 hover:bg-gray-50 border-l border-gray-200 first:border-l-0">
                                <input type="radio" name="currency" value="{{ $val }}" {{ $val==='USD' ? 'checked' : '' }} class="sr-only">
                                {{ $lbl }}
                            </label>
                            @endforeach
                        </div>
                        <input type="number" name="price" min="0" step="0.01" value="0"
                            class="flex-1 border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">المدة</label>
                        <input type="text" name="duration" placeholder="مثال: 30 ساعة" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">الحالة</label>
                        <select name="status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="active">نشطة</option>
                            <option value="hidden">مخفية</option>
                        </select>
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
            <h2 class="text-xl font-black text-navy mb-6">تعديل الدورة</h2>
            <form method="POST" :action="'/dashboard/courses/' + editItem.id" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">عنوان الدورة</label>
                    <input type="text" name="title" x-model="editItem.title" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الوصف</label>
                    <textarea name="description" x-model="editItem.description" rows="3" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors"></textarea>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الشعبة التدريبية</label>
                    <select name="section_id" x-model="editItem.section_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        <option value="">-- بدون شعبة --</option>
                        @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name_ar }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">التصنيف</label>
                        <input type="text" name="category" x-model="editItem.category" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">المستوى</label>
                        <select name="level" x-model="editItem.level" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="مبتدئ">مبتدئ</option>
                            <option value="متوسط">متوسط</option>
                            <option value="متقدم">متقدم</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">السعر والعملة</label>
                    <div class="flex gap-2">
                        <div class="flex rounded-xl border-2 border-gray-200 overflow-hidden focus-within:border-navy transition-colors flex-shrink-0">
                            @foreach([['USD','$ دولار'],['EGP','ج.م'],['SAR','ر.س']] as [$val,$lbl])
                            <label class="flex items-center gap-1.5 px-3 py-3 cursor-pointer text-sm font-bold transition-colors text-gray-500 hover:bg-gray-50 border-l border-gray-200 first:border-l-0"
                                :class="editItem.currency === '{{ $val }}' ? 'bg-navy text-white' : ''">
                                <input type="radio" name="currency" value="{{ $val }}"
                                    x-model="editItem.currency" class="sr-only">
                                {{ $lbl }}
                            </label>
                            @endforeach
                        </div>
                        <input type="number" name="price" x-model="editItem.price" min="0" step="0.01"
                            class="flex-1 border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">المدة</label>
                        <input type="text" name="duration" x-model="editItem.duration" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                    </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الحالة</label>
                    <select name="status" x-model="editItem.status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        <option value="active">نشطة</option>
                        <option value="hidden">مخفية</option>
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
function coursesManager() {
    return {
        search: '',
        showAddModal: false,
        showEditModal: false,
        editItem: { id: null, title: '', description: '', category: '', price: 0, currency: 'USD', duration: '', level: '', status: 'active', section_id: '' },
        openEdit(id, title, description, category, price, currency, duration, level, status, section_id) {
            this.editItem = { id, title, description, category, price, currency: currency || 'USD', duration, level, status, section_id: section_id || '' };
            this.showEditModal = true;
        }
    };
}
</script>
@endpush
@endsection
