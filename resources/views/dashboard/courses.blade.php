@extends('layouts.dashboard')
@section('title', 'INSEP PRO')
@section('dashboard-content')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
<div x-data="coursesManager()">

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-green-700 text-sm font-bold">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-navy">{{ auth()->user()->role === 'instructor' ? ($isAr ? 'دوراتي' : 'My Courses') : ($isAr ? 'إدارة الدورات' : 'Manage Courses') }}</h1>
            <p class="text-gray-500 text-sm">{{ $isAr ? 'إجمالي' : 'Total' }} {{ $courses->count() }} {{ $isAr ? 'دورة' : 'courses' }}</p>
        </div>
        @if(auth()->user()->role !== 'instructor')
        <button @click="showAddModal = true" class="bg-red-brand hover:bg-red-brand-dark text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            {{ $isAr ? 'إضافة دورة' : 'Add Course' }}
        </button>
        @endif
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-2xl p-4 mb-6 border border-gray-100">
        <div class="relative">
            <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
            <input type="text" x-model="search" placeholder="{{ $isAr ? 'بحث عن دورة...' : 'Search for a course...' }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl pr-10 pl-4 py-2.5 text-sm">
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">#</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'الدورة' : 'Course' }}</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'الشعبة' : 'Section' }}</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'التصنيف' : 'Category' }}</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'المستوى' : 'Level' }}</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'السعر' : 'Price' }}</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'الحالة' : 'Status' }}</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'مميزة' : 'Featured' }}</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'المسجلين' : 'Enrolled' }}</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'إجراءات' : 'Actions' }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $i => $course)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors"
                        x-show="!search || '{{ addslashes($course->title) }} {{ $course->category }}'.toLowerCase().includes(search.toLowerCase())">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $i + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($course->image)
                                <img src="{{ str_starts_with($course->image, 'http') ? $course->image : asset('storage/' . ltrim($course->image, '/')) }}"
                                     class="w-10 h-10 rounded-xl object-cover flex-shrink-0 border border-gray-100" alt="">
                                @else
                                <div class="w-10 h-10 rounded-xl bg-navy/10 flex-shrink-0 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-navy/30" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                                @endif
                                <span class="font-bold text-navy text-sm">{{ $course->title }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $course->section->name_ar ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $course->category ?? '-' }}</td>
                        <td class="px-6 py-4"><span class="px-3 py-1 rounded-lg text-xs font-bold bg-navy/10 text-navy">{{ $course->level ?? '-' }}</span></td>
                        <td class="px-6 py-4 text-sm font-bold text-red-brand" style="font-family:'Roboto',sans-serif">{{ $course->currency ?? 'USD' }} {{ $course->price }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-lg text-xs font-bold {{ ($course->status ?? 'active') === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ ($course->status ?? 'active') === 'active' ? ($isAr ? 'نشطة' : 'Active') : ($isAr ? 'مخفية' : 'Hidden') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if(auth()->user()->role !== 'instructor')
                            <form method="POST" action="{{ route('dashboard.courses.toggle-featured', $course) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    title="{{ $course->is_featured ? ($isAr ? 'إلغاء التمييز' : 'Remove from featured') : ($isAr ? 'تمييز الدورة' : 'Mark as featured') }}"
                                    class="mx-auto transition-colors {{ $course->is_featured ? 'text-yellow-400 hover:text-gray-300' : 'text-gray-300 hover:text-yellow-400' }}">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                </button>
                            </form>
                            @else
                                @if($course->is_featured)
                                <svg class="w-5 h-5 text-yellow-400 mx-auto" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                @else
                                <span class="text-gray-300 text-xs">—</span>
                                @endif
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $course->enrollments_count ?? 0 }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                @if(auth()->user()->role !== 'instructor')
                                <button @click="openEdit({{ $course->id }}, {{ json_encode($course->title) }}, {{ json_encode($course->description ?? '') }}, {{ json_encode($course->category ?? '') }}, {{ $course->price ?? 0 }}, {{ json_encode($course->currency ?? 'USD') }}, {{ json_encode($course->duration ?? '') }}, {{ json_encode($course->level ?? '') }}, {{ json_encode($course->status ?? 'active') }}, {{ $course->section_id ?? 'null' }}, {{ json_encode($course->image ?? '') }}, {{ json_encode($course->content ?? '') }}, {{ json_encode($course->features ?? '') }}, {{ json_encode($course->accreditation ?? '') }}, {{ json_encode($course->job_opportunities ?? '') }}, {{ json_encode($course->promo_video ?? '') }}, {{ $course->is_featured ? 1 : 0 }})"
                                    class="p-2 hover:bg-yellow-50 rounded-lg transition-colors text-yellow-500" title="{{ $isAr ? 'تعديل' : 'Edit' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>
                                <form method="POST" action="{{ route('dashboard.courses.destroy', $course->id) }}" onsubmit="return confirm('{{ $isAr ? 'هل أنت متأكد من حذف هذه الدورة؟' : 'Are you sure you want to delete this course?' }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 hover:bg-red-50 rounded-lg transition-colors text-red-500" title="{{ $isAr ? 'حذف' : 'Delete' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                    </button>
                                </form>
                                @else
                                <a href="{{ route('dashboard.batches') }}" class="px-3 py-1.5 bg-navy/10 hover:bg-navy/20 text-navy rounded-lg text-xs font-bold transition-colors">{{ $isAr ? 'المجموعات' : 'Batches' }}</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="text-center py-12 text-gray-400">{{ $isAr ? 'لا يوجد دورات بعد' : 'No courses found yet' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add Modal --}}
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showAddModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-2xl relative z-10 shadow-2xl max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl font-black text-navy mb-6">{{ $isAr ? 'إضافة دورة جديدة' : 'Add New Course' }}</h2>
            <form method="POST" action="{{ route('dashboard.courses.store') }}" class="space-y-4" enctype="multipart/form-data">
                @csrf
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'صورة الدورة' : 'Course Image' }}</label>
                    <input type="file" name="image" accept="image/*" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:border-navy transition-colors text-sm text-gray-600 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-navy file:text-white file:text-xs file:font-bold">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'عنوان الدورة' : 'Course Title' }}</label>
                    <input type="text" name="title" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الوصف المختصر' : 'Short Description' }}</label>
                    <textarea name="description" rows="2" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors"></textarea>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'محتوى الدورة (وحدات وموضوعات)' : 'Course Content (modules & topics)' }}</label>
                    <textarea name="content" rows="4" placeholder="{{ $isAr ? 'مثال: الوحدة الأولى: المقدمة\nالوحدة الثانية: التطبيق العملي' : 'e.g. Module 1: Introduction\nModule 2: Practical Application' }}" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors text-sm"></textarea>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'مميزات الدورة (سطر لكل ميزة)' : 'Course Features (one per line)' }}</label>
                    <textarea name="features" rows="3" placeholder="{{ $isAr ? 'مثال: شهادة معتمدة دولياً\nمواد تدريبية متكاملة' : 'e.g. Internationally accredited certificate\nComprehensive training materials' }}" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors text-sm"></textarea>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الاعتماد والجهة المانحة' : 'Accreditation & Issuing Body' }}</label>
                    <textarea name="accreditation" rows="2" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors text-sm"></textarea>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'فرص العمل (سطر لكل فرصة)' : 'Job Opportunities (one per line)' }}</label>
                    <textarea name="job_opportunities" rows="3" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors text-sm"></textarea>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'رابط فيديو البرومو (يوتيوب)' : 'Promo Video URL (YouTube)' }}</label>
                    <input type="url" name="promo_video" placeholder="https://www.youtube.com/embed/..." class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors text-sm" dir="ltr">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الشعبة التدريبية' : 'Training Section' }}</label>
                    <select name="section_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        <option value="">{{ $isAr ? '-- بدون شعبة --' : '-- No Section --' }}</option>
                        @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name_ar }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'التصنيف' : 'Category' }}</label>
                        <input type="text" name="category" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'المستوى' : 'Level' }}</label>
                        <select name="level" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="مبتدئ">{{ $isAr ? 'مبتدئ' : 'Beginner' }}</option>
                            <option value="متوسط">{{ $isAr ? 'متوسط' : 'Intermediate' }}</option>
                            <option value="متقدم">{{ $isAr ? 'متقدم' : 'Advanced' }}</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'السعر والعملة' : 'Price & Currency' }}</label>
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
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'المدة' : 'Duration' }}</label>
                        <input type="text" name="duration" placeholder="{{ $isAr ? 'مثال: 30 ساعة' : 'e.g. 30 hours' }}" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الحالة' : 'Status' }}</label>
                        <select name="status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="active">{{ $isAr ? 'نشطة' : 'Active' }}</option>
                            <option value="hidden">{{ $isAr ? 'مخفية' : 'Hidden' }}</option>
                        </select>
                    </div>
                </div>
                <label class="flex items-center gap-3 cursor-pointer p-3 bg-yellow-50 rounded-xl border border-yellow-200">
                    <input type="checkbox" name="is_featured" value="1" class="w-4 h-4 accent-yellow-500">
                    <span class="text-sm font-bold text-yellow-700">{{ $isAr ? 'دورة مميزة (تظهر في الصفحة الرئيسية بشكل مميز)' : 'Featured Course (highlighted on homepage)' }}</span>
                </label>
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
        <div class="bg-white rounded-2xl p-8 w-full max-w-2xl relative z-10 shadow-2xl max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl font-black text-navy mb-6">{{ $isAr ? 'تعديل الدورة' : 'Edit Course' }}</h2>
            <form method="POST" :action="'/dashboard/courses/' + editItem.id" class="space-y-4" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'صورة الدورة (اتركها فارغة للإبقاء على الحالية)' : 'Course Image (leave blank to keep current)' }}</label>
                    <input type="file" name="image" accept="image/*" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:border-navy transition-colors text-sm text-gray-600 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-navy file:text-white file:text-xs file:font-bold">
                    <template x-if="editItem.image">
                        <img :src="editItem.image.startsWith('http') ? editItem.image : '/storage/' + editItem.image" class="mt-2 h-20 rounded-xl object-cover" alt="{{ $isAr ? 'صورة الدورة' : 'Course Image' }}">
                    </template>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'عنوان الدورة' : 'Course Title' }}</label>
                    <input type="text" name="title" x-model="editItem.title" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الوصف المختصر' : 'Short Description' }}</label>
                    <textarea name="description" x-model="editItem.description" rows="2" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors"></textarea>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'محتوى الدورة (وحدات وموضوعات)' : 'Course Content (modules & topics)' }}</label>
                    <textarea name="content" x-model="editItem.content" rows="4" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors text-sm"></textarea>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'مميزات الدورة (سطر لكل ميزة)' : 'Course Features (one per line)' }}</label>
                    <textarea name="features" x-model="editItem.features" rows="3" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors text-sm"></textarea>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الاعتماد والجهة المانحة' : 'Accreditation & Issuing Body' }}</label>
                    <textarea name="accreditation" x-model="editItem.accreditation" rows="2" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors text-sm"></textarea>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'فرص العمل (سطر لكل فرصة)' : 'Job Opportunities (one per line)' }}</label>
                    <textarea name="job_opportunities" x-model="editItem.job_opportunities" rows="3" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors text-sm"></textarea>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'رابط فيديو البرومو (يوتيوب)' : 'Promo Video URL (YouTube)' }}</label>
                    <input type="url" name="promo_video" x-model="editItem.promo_video" placeholder="https://www.youtube.com/embed/..." class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors text-sm" dir="ltr">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الشعبة التدريبية' : 'Training Section' }}</label>
                    <select name="section_id" x-model="editItem.section_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        <option value="">{{ $isAr ? '-- بدون شعبة --' : '-- No Section --' }}</option>
                        @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name_ar }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'التصنيف' : 'Category' }}</label>
                        <input type="text" name="category" x-model="editItem.category" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'المستوى' : 'Level' }}</label>
                        <select name="level" x-model="editItem.level" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="مبتدئ">{{ $isAr ? 'مبتدئ' : 'Beginner' }}</option>
                            <option value="متوسط">{{ $isAr ? 'متوسط' : 'Intermediate' }}</option>
                            <option value="متقدم">{{ $isAr ? 'متقدم' : 'Advanced' }}</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'السعر والعملة' : 'Price & Currency' }}</label>
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
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'المدة' : 'Duration' }}</label>
                        <input type="text" name="duration" x-model="editItem.duration" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الحالة' : 'Status' }}</label>
                        <select name="status" x-model="editItem.status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="active">{{ $isAr ? 'نشطة' : 'Active' }}</option>
                            <option value="hidden">{{ $isAr ? 'مخفية' : 'Hidden' }}</option>
                        </select>
                    </div>
                </div>
                <label class="flex items-center gap-3 cursor-pointer p-3 bg-yellow-50 rounded-xl border border-yellow-200">
                    <input type="checkbox" name="is_featured" value="1" :checked="editItem.is_featured" @change="editItem.is_featured = $event.target.checked" class="w-4 h-4 accent-yellow-500">
                    <span class="text-sm font-bold text-yellow-700">{{ $isAr ? 'دورة مميزة (تظهر في الصفحة الرئيسية بشكل مميز)' : 'Featured Course (highlighted on homepage)' }}</span>
                </label>
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
function coursesManager() {
    return {
        search: '',
        showAddModal: false,
        showEditModal: false,
        editItem: {
            id: null, title: '', description: '', content: '', features: '',
            accreditation: '', job_opportunities: '', promo_video: '',
            category: '', price: 0, currency: 'USD', duration: '', level: '',
            status: 'active', section_id: '', image: '', is_featured: false
        },
        openEdit(id, title, description, category, price, currency, duration, level, status, section_id, image, content, features, accreditation, job_opportunities, promo_video, is_featured) {
            this.editItem = {
                id, title, description, category, price,
                currency: currency || 'USD', duration, level, status,
                section_id: section_id || '', image: image || '',
                content: content || '', features: features || '',
                accreditation: accreditation || '', job_opportunities: job_opportunities || '',
                promo_video: promo_video || '', is_featured: !!is_featured
            };
            this.showEditModal = true;
        }
    };
}
</script>
@endpush
@endsection
