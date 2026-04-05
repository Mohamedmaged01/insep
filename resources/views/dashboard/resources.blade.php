@extends('layouts.dashboard')
@section('title', 'INSEP PRO')
@section('dashboard-content')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
<div x-data="resourcesManager()">

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-green-700 text-sm font-bold">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-navy">{{ $isAr ? 'المحاضرات المسجلة' : 'Recorded Lectures' }}</h1>
            <p class="text-gray-500 text-sm">{{ $isAr ? 'إجمالي' : 'Total' }} {{ $resources->count() }} {{ $isAr ? 'محتوى' : 'resources' }}</p>
        </div>
        <button @click="showAddModal = true" class="bg-red-brand hover:bg-red-brand-dark text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            {{ $isAr ? 'إضافة محتوى' : 'Add Resource' }}
        </button>
    </div>

    {{-- Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($resources as $res)
        <div class="bg-white rounded-2xl p-6 border border-gray-100 card-hover">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-xl bg-navy/10 flex items-center justify-center">
                    <svg class="w-6 h-6 text-navy" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </div>
                <div class="flex gap-2">
                    <button @click="openEdit({{ $res->id }}, '{{ addslashes($res->title) }}', '{{ $res->type ?? 'PDF' }}', '{{ $res->file_url ?? '' }}', {{ $res->course_id ?? 'null' }}, {{ $res->batch_id ?? 'null' }})"
                        class="p-2 hover:bg-yellow-50 rounded-lg transition-colors text-yellow-500" title="{{ $isAr ? 'تعديل' : 'Edit' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                    <form method="POST" action="{{ route('dashboard.resources.destroy', $res->id) }}" onsubmit="return confirm('{{ $isAr ? 'هل أنت متأكد من حذف هذا المحتوى؟' : 'Are you sure you want to delete this resource?' }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 hover:bg-red-50 rounded-lg transition-colors text-red-500" title="{{ $isAr ? 'حذف' : 'Delete' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                        </button>
                    </form>
                </div>
            </div>
            <h3 class="font-bold text-navy mb-1">{{ $res->title }}</h3>
            <p class="text-sm text-gray-500 mb-2">{{ $res->course->title ?? '-' }}</p>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold px-2 py-1 bg-navy/10 text-navy rounded-lg">{{ $res->type ?? ($isAr ? 'فيديو' : 'Video') }}</span>
                @if($res->file_url)
                <a href="{{ $res->file_url }}" target="_blank" class="text-xs text-blue-500 hover:underline">{{ $isAr ? 'رابط ←' : 'Link ←' }}</a>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12 text-gray-400">{{ $isAr ? 'لا يوجد محاضرات مسجلة بعد' : 'No recorded lectures found yet' }}</div>
        @endforelse
    </div>

    {{-- Add Modal --}}
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showAddModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-lg relative z-10 shadow-2xl">
            <h2 class="text-xl font-black text-navy mb-6">{{ $isAr ? 'إضافة محتوى جديد' : 'Add New Resource' }}</h2>
            <form method="POST" action="{{ route('dashboard.resources.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'العنوان' : 'Title' }}</label>
                    <input type="text" name="title" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'نوع المحتوى' : 'Resource Type' }}</label>
                    <select name="type" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        <option value="PDF">PDF</option>
                        <option value="Video">{{ $isAr ? 'فيديو' : 'Video' }}</option>
                        <option value="VideoLink">{{ $isAr ? 'رابط فيديو' : 'Video Link' }}</option>
                        <option value="Word">Word</option>
                        <option value="PowerPoint">PowerPoint</option>
                        <option value="Document">{{ $isAr ? 'مستند' : 'Document' }}</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الرابط أو مسار الملف' : 'Resource Link or File Path' }}</label>
                    <input type="text" name="file_url" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الدورة (اختياري)' : 'Course (optional)' }}</label>
                    <select name="course_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        <option value="">{{ $isAr ? '-- بدون دورة --' : '-- No Course --' }}</option>
                        @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'المجموعة (اختياري)' : 'Batch (optional)' }}</label>
                    <select name="batch_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        <option value="">{{ $isAr ? '-- بدون مجموعة --' : '-- No Batch --' }}</option>
                        @foreach($batches as $batch)
                        <option value="{{ $batch->id }}">{{ $batch->name }} - {{ $batch->course->title ?? '' }}</option>
                        @endforeach
                    </select>
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
        <div class="bg-white rounded-2xl p-8 w-full max-w-lg relative z-10 shadow-2xl">
            <h2 class="text-xl font-black text-navy mb-6">{{ $isAr ? 'تعديل المحتوى' : 'Edit Resource' }}</h2>
            <form method="POST" :action="'/dashboard/resources/' + editItem.id" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'العنوان' : 'Title' }}</label>
                    <input type="text" name="title" x-model="editItem.title" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'نوع المحتوى' : 'Resource Type' }}</label>
                    <select name="type" x-model="editItem.type" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        <option value="PDF">PDF</option>
                        <option value="Video">{{ $isAr ? 'فيديو' : 'Video' }}</option>
                        <option value="VideoLink">{{ $isAr ? 'رابط فيديو' : 'Video Link' }}</option>
                        <option value="Word">Word</option>
                        <option value="PowerPoint">PowerPoint</option>
                        <option value="Document">{{ $isAr ? 'مستند' : 'Document' }}</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الرابط أو مسار الملف' : 'Resource Link or File Path' }}</label>
                    <input type="text" name="file_url" x-model="editItem.file_url" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الدورة (اختياري)' : 'Course (optional)' }}</label>
                    <select name="course_id" x-model="editItem.course_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        <option value="">{{ $isAr ? '-- بدون دورة --' : '-- No Course --' }}</option>
                        @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'المجموعة (اختياري)' : 'Batch (optional)' }}</label>
                    <select name="batch_id" x-model="editItem.batch_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        <option value="">{{ $isAr ? '-- بدون مجموعة --' : '-- No Batch --' }}</option>
                        @foreach($batches as $batch)
                        <option value="{{ $batch->id }}">{{ $batch->name }} - {{ $batch->course->title ?? '' }}</option>
                        @endforeach
                    </select>
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
function resourcesManager() {
    return {
        showAddModal: false,
        showEditModal: false,
        editItem: { id: null, title: '', type: 'PDF', file_url: '', course_id: '', batch_id: '' },
        openEdit(id, title, type, file_url, course_id, batch_id) {
            this.editItem = { id, title, type, file_url, course_id: course_id || '', batch_id: batch_id || '' };
            this.showEditModal = true;
        }
    };
}
</script>
@endpush
@endsection
