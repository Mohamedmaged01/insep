@extends('layouts.dashboard')
@section('title', 'INSEP PRO - ' . (app()->getLocale() === 'ar' ? 'الأخبار والمقالات' : 'News & Articles'))
@section('dashboard-content')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
<div x-data="newsManager()">

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-green-700 text-sm font-bold">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-navy">{{ $isAr ? 'الأخبار والمقالات' : 'News & Articles' }}</h1>
            <p class="text-gray-500 text-sm">{{ $isAr ? 'إجمالي' : 'Total' }} {{ $news->count() }} {{ $isAr ? 'مقال' : 'articles' }}</p>
        </div>
        <button @click="openCreate()" class="bg-red-brand hover:bg-red-brand-dark text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            {{ $isAr ? 'إضافة مقال' : 'Add Article' }}
        </button>
    </div>

    {{-- News Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($news as $item)
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm flex flex-col">
            <div class="relative h-44 bg-gray-100">
                @if($item->image)
                <img src="{{ str_starts_with($item->image, 'http') ? $item->image : asset('storage/' . ltrim($item->image, '/')) }}"
                     alt="{{ $item->title }}" class="w-full h-full object-cover"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($item->title) }}&background=1B2B4B&color=fff&size=400&bold=true'">
                @else
                <div class="w-full h-full flex items-center justify-center text-gray-300">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 002 2zm0 0a2 2 0 002-2V8a2 2 0 00-2-2h-2M9 9h6M9 13h6M9 17h3"/></svg>
                </div>
                @endif
                @if($item->video_url)
                <span class="absolute top-3 left-3 bg-red-brand text-white px-2.5 py-1 rounded-lg text-xs font-bold flex items-center gap-1 shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    {{ $isAr ? 'فيديو' : 'Video' }}
                </span>
                @endif
                @if($item->tag)
                <span class="absolute top-3 right-3 bg-navy/80 text-white px-2.5 py-1 rounded-lg text-xs font-bold shadow-sm">{{ $item->tag }}</span>
                @endif
            </div>
            <div class="p-5 flex flex-col flex-1">
                <h3 class="font-black text-navy text-base mb-2 line-clamp-2">{{ $item->title }}</h3>
                @if($item->description)
                <p class="text-gray-500 text-sm leading-relaxed mb-3 line-clamp-3">{{ $item->description }}</p>
                @endif
                <p class="text-gray-400 text-xs mb-4">{{ $item->date ?? optional($item->created_at)->format('Y-m-d') }}</p>
                <div class="flex gap-2 mt-auto pt-4 border-t border-gray-100">
                    <a href="{{ route('news.show', $item->id) }}" target="_blank"
                        class="px-4 py-2 bg-gray-50 text-gray-600 rounded-xl text-xs font-bold hover:bg-gray-100 transition-colors">
                        {{ $isAr ? 'عرض' : 'View' }}
                    </a>
                    <button @click='openEdit(@json($item))'
                        class="px-4 py-2 bg-yellow-50 text-yellow-600 rounded-xl text-xs font-bold hover:bg-yellow-100 transition-colors">
                        {{ $isAr ? 'تعديل' : 'Edit' }}
                    </button>
                    <form method="POST" action="{{ route('dashboard.news.destroy', $item->id) }}" onsubmit="return confirm('{{ $isAr ? 'حذف هذا المقال نهائياً؟' : 'Delete this article permanently?' }}')" class="ms-auto">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-50 text-red-500 rounded-xl text-xs font-bold hover:bg-red-100 transition-colors">
                            {{ $isAr ? 'حذف' : 'Delete' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-20 text-gray-400">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 002 2zm0 0a2 2 0 002-2V8a2 2 0 00-2-2h-2M9 9h6M9 13h6M9 17h3"/></svg>
            <p class="font-bold text-gray-400">{{ $isAr ? 'لا توجد مقالات بعد' : 'No articles yet' }}</p>
        </div>
        @endforelse
    </div>

    {{-- Add / Edit Modal --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-lg relative z-10 shadow-2xl max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl font-black text-navy mb-6" x-text="editing ? '{{ $isAr ? 'تعديل المقال' : 'Edit Article' }}' : '{{ $isAr ? 'إضافة مقال جديد' : 'New Article' }}'"></h2>
            <form method="POST" :action="editing ? ('/dashboard/news/' + form.id) : '{{ route('dashboard.news.store') }}'" class="space-y-4" enctype="multipart/form-data">
                @csrf
                <template x-if="editing"><input type="hidden" name="_method" value="PUT"></template>

                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'صورة المقال' : 'Article Image' }}</label>
                    <input type="file" name="image" accept="image/*" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-600 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-navy file:text-white file:text-xs file:font-bold">
                    <template x-if="form.image">
                        <img :src="form.image.startsWith('http') ? form.image : '/storage/' + form.image" class="mt-2 h-24 w-full object-cover rounded-xl" alt="">
                    </template>
                    <p class="text-xs text-gray-400 mt-1" x-show="editing">{{ $isAr ? 'اتركها فارغة للإبقاء على الصورة الحالية' : 'Leave blank to keep the current image' }}</p>
                </div>

                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'عنوان المقال' : 'Title' }} *</label>
                    <input type="text" name="title" x-model="form.title" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                </div>

                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'محتوى المقال' : 'Content' }}</label>
                    <textarea name="description" x-model="form.description" rows="5" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors text-sm"></textarea>
                </div>

                <div>
                    <label class="text-sm font-bold text-navy mb-2 block flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-red-brand" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        {{ $isAr ? 'رابط الفيديو (يوتيوب / Google Drive)' : 'Video Link (YouTube / Google Drive)' }}
                    </label>
                    <input type="url" name="video_url" x-model="form.video_url" dir="ltr" placeholder="https://www.youtube.com/watch?v=..." class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                    <p class="text-xs text-gray-400 mt-1">{{ $isAr ? 'سيُعرض الفيديو داخل صفحة المقال في الموقع.' : 'The video will play inside the article page on the site.' }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'التصنيف' : 'Tag' }}</label>
                        <input type="text" name="tag" x-model="form.tag" placeholder="{{ $isAr ? 'مثال: أخبار' : 'e.g. News' }}" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'التاريخ' : 'Date' }}</label>
                        <input type="date" name="date" x-model="form.date" dir="ltr" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200 transition-colors">{{ $isAr ? 'إلغاء' : 'Cancel' }}</button>
                    <button type="submit" class="flex-1 bg-navy hover:bg-navy-dark text-white py-3 rounded-xl font-bold transition-colors">{{ $isAr ? 'حفظ' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </div>

</div>
@push('scripts')
<script>
function newsManager() {
    return {
        showModal: false,
        editing: false,
        form: { id: null, title: '', description: '', video_url: '', tag: '', date: '', image: '' },
        openCreate() {
            this.editing = false;
            this.form = { id: null, title: '', description: '', video_url: '', tag: '', date: '', image: '' };
            this.showModal = true;
        },
        openEdit(item) {
            this.editing = true;
            this.form = {
                id: item.id,
                title: item.title || '',
                description: item.description || '',
                video_url: item.video_url || '',
                tag: item.tag || '',
                date: item.date || '',
                image: item.image || '',
            };
            this.showModal = true;
        }
    };
}
</script>
@endpush
@endsection
