@extends('layouts.dashboard')
@section('title', 'INSEP PRO')
@section('dashboard-content')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
<div x-data="studentsManager()" @keydown.escape.window="showAddModal=false; showEditModal=false; showCertModal=false">

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-green-700 text-sm font-bold">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-navy">{{ $isAr ? 'إدارة الطلاب' : 'Manage Students' }}</h1>
            <p class="text-gray-500 text-sm">{{ $isAr ? 'إجمالي' : 'Total' }} {{ $students->total() }} {{ $isAr ? 'طالب' : 'students' }}</p>
        </div>
        <button @click="showAddModal = true" class="bg-red-brand hover:bg-red-brand-dark text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            {{ $isAr ? 'إضافة طالب' : 'Add Student' }}
        </button>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-2xl p-4 mb-6 border border-gray-100">
        <div class="relative flex-1">
            <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
            <input type="text" x-model="search" placeholder="{{ $isAr ? 'بحث عن طالب...' : 'Search for a student...' }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl pr-10 pl-4 py-2.5 text-sm">
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">#</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'الاسم بالعربي' : 'Arabic Name' }}</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'الاسم بالإنجليزي' : 'English Name' }}</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'البريد الإلكتروني' : 'Email' }}</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'الهاتف' : 'Phone' }}</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'الحالة' : 'Status' }}</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'تاريخ التسجيل' : 'Registration Date' }}</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'إجراءات' : 'Actions' }}</th>
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
                                {{ ($student->status ?? 'active') === 'active' ? ($isAr ? 'نشط' : 'Active') : ($isAr ? 'معلق' : 'Suspended') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $student->created_at?->format('Y-m-d') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button @click="openEdit({{ $student->id }}, '{{ addslashes($student->name_ar ?? $student->name) }}', '{{ addslashes($student->name_en ?? '') }}', '{{ $student->email }}', '{{ $student->phone ?? '' }}', '{{ $student->status ?? 'active' }}')"
                                    class="p-2 hover:bg-yellow-50 rounded-lg transition-colors text-yellow-500" title="{{ $isAr ? 'تعديل' : 'Edit' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>
                                @if(auth()->user()->isAdminOrAbove())
                                @php
                                    $enrollData = $student->enrollments->map(fn($e) => [
                                        'course_id'    => $e->course_id,
                                        'batch_id'     => $e->batch_id,
                                        'course_title' => $e->course?->title ?? '',
                                        'batch_name'   => $e->batch?->name ?? '',
                                    ])->values()->toJson();
                                @endphp
                                <button
                                    data-sid="{{ $student->id }}"
                                    data-sname="{{ addslashes($student->name_ar ?? $student->name) }}"
                                    data-enrollments="{{ htmlspecialchars($enrollData, ENT_QUOTES) }}"
                                    @click="openCert($el.dataset.sid, $el.dataset.sname, JSON.parse($el.dataset.enrollments))"
                                    class="p-2 hover:bg-green-50 rounded-lg transition-colors text-green-600" title="{{ $isAr ? 'إصدار شهادة' : 'Issue Certificate' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="6"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>
                                </button>
                                @endif
                                <form method="POST" action="{{ route('dashboard.students.destroy', $student->id) }}" onsubmit="return confirm('{{ $isAr ? 'هل أنت متأكد من حذف هذا الطالب؟' : 'Are you sure you want to delete this student?' }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 hover:bg-red-50 rounded-lg transition-colors text-red-500" title="{{ $isAr ? 'حذف' : 'Delete' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-12 text-gray-400">{{ $isAr ? 'لا يوجد طلاب مسجلين بعد' : 'No students registered yet' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($students->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $students->links() }}
        </div>
        @endif
    </div>

    {{-- Add Modal --}}
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showAddModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-lg relative z-10 shadow-2xl">
            <h2 class="text-xl font-black text-navy mb-6">{{ $isAr ? 'إضافة طالب جديد' : 'Add New Student' }}</h2>
            <form method="POST" action="{{ route('dashboard.students.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الاسم الكامل بالعربي' : 'Full Name in Arabic' }}</label>
                    <input type="text" name="name_ar" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الاسم الكامل بالإنجليزي' : 'Full Name in English' }}</label>
                    <input type="text" name="name_en" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'البريد الإلكتروني' : 'Email' }}</label>
                    <input type="email" name="email" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'رقم الهاتف' : 'Phone Number' }}</label>
                    <div class="flex gap-2">
                        <select name="phone_code" class="border-2 border-gray-200 rounded-xl px-2 py-3 focus:border-navy transition-colors text-sm font-bold text-gray-700 flex-shrink-0" dir="ltr">
                            <option value="+20">🇪🇬 +20</option>
                            <option value="+966">🇸🇦 +966</option>
                            <option value="+971">🇦🇪 +971</option>
                            <option value="+974">🇶🇦 +974</option>
                            <option value="+965">🇰🇼 +965</option>
                            <option value="+973">🇧🇭 +973</option>
                            <option value="+968">🇴🇲 +968</option>
                            <option value="+962">🇯🇴 +962</option>
                            <option value="+961">🇱🇧 +961</option>
                            <option value="+963">🇸🇾 +963</option>
                            <option value="+964">🇮🇶 +964</option>
                            <option value="+212">🇲🇦 +212</option>
                            <option value="+216">🇹🇳 +216</option>
                            <option value="+213">🇩🇿 +213</option>
                            <option value="+1">🇺🇸 +1</option>
                            <option value="+44">🇬🇧 +44</option>
                        </select>
                        <input type="text" name="phone" class="flex-1 border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr" placeholder="1234567890">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'كلمة المرور' : 'Password' }}</label>
                    <input type="password" name="password" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr" required>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showAddModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200 transition-colors">{{ $isAr ? 'إلغاء' : 'Cancel' }}</button>
                    <button type="submit" class="flex-1 bg-navy hover:bg-navy-dark text-white py-3 rounded-xl font-bold transition-colors">{{ $isAr ? 'حفظ' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Issue Certificate Modal --}}
    @if(auth()->user()->isAdminOrAbove())
    <div x-show="showCertModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showCertModal = false"></div>
        <div class="bg-white rounded-2xl p-6 w-full max-w-lg relative z-10 shadow-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-lg font-black text-navy">{{ $isAr ? 'إصدار شهادة' : 'Issue Certificate' }}</h2>
                <button @click="showCertModal = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('dashboard.certificates.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="student_id" :value="certStudent.id">
                {{-- Student (read-only display) --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">{{ $isAr ? 'المتدرب' : 'Student' }}</label>
                    <div class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-50 text-navy font-semibold" x-text="certStudent.name"></div>
                </div>
                {{-- Course dropdown — only enrolled courses --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">{{ $isAr ? 'الدورة *' : 'Course *' }}</label>
                    <select name="course_id" x-model="certStudent.selectedCourseId" required
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-navy">
                        <option value="">— {{ $isAr ? 'اختر الدورة' : 'Select Course' }} —</option>
                        <template x-if="certStudent.enrollments.length > 0">
                            <template x-for="enr in certStudent.enrollments" :key="enr.course_id">
                                <option :value="enr.course_id" x-text="enr.course_title"></option>
                            </template>
                        </template>
                        <template x-if="certStudent.enrollments.length === 0">
                            @foreach($courses as $c)
                            <option value="{{ $c->id }}">{{ $c->title }}</option>
                            @endforeach
                        </template>
                    </select>
                    <p x-show="certStudent.enrollments.length === 0" class="text-xs text-amber-600 mt-1">{{ $isAr ? 'هذا الطالب غير مسجل في أي دورة — يمكنك اختيار أي دورة' : 'Student has no enrollments — any course can be selected' }}</p>
                </div>
                {{-- Batch dropdown — filtered to selected course --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">{{ $isAr ? 'المجموعة (اختياري)' : 'Batch (optional)' }}</label>
                    <select name="batch_id" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-navy">
                        <option value="">—</option>
                        <template x-if="certStudent.enrollments.length > 0">
                            <template x-for="enr in certStudent.enrollments.filter(e => !certStudent.selectedCourseId || e.course_id == certStudent.selectedCourseId)" :key="enr.batch_id">
                                <option :value="enr.batch_id" x-text="enr.batch_name || '—'"></option>
                            </template>
                        </template>
                        <template x-if="certStudent.enrollments.length === 0">
                            @foreach($batches as $b)
                            <option value="{{ $b->id }}">{{ $b->name }} ({{ $b->course?->title }})</option>
                            @endforeach
                        </template>
                    </select>
                </div>
                {{-- Title & Grade --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">{{ $isAr ? 'عنوان الشهادة' : 'Certificate Title' }}</label>
                        <input name="title" placeholder="{{ $isAr ? 'شهادة إتمام الدورة' : 'Certificate of Completion' }}"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-navy">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">{{ $isAr ? 'التقدير' : 'Grade' }}</label>
                        <input name="grade" placeholder="{{ $isAr ? 'مثلاً: ممتاز' : 'e.g. Excellent' }}"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-navy">
                    </div>
                </div>
                {{-- Issue Date --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">{{ $isAr ? 'تاريخ الإصدار' : 'Issue Date' }}</label>
                    <input name="issue_date" type="date" value="{{ now()->toDateString() }}"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-navy">
                </div>
                {{-- Certificate file upload (PDF/image) --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-2">{{ $isAr ? 'ملف الشهادة (PDF)' : 'Certificate File (PDF)' }}</label>
                    <input name="certificate_file" type="file" accept=".pdf,.jpg,.png"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-navy">
                    <p class="text-xs text-gray-400 mt-1">{{ $isAr ? 'ارفع ملف شهادة جاهز. لم يعد يتم توليد الشهادات تلقائياً.' : 'Upload a ready certificate file. Certificates are no longer auto-generated.' }}</p>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showCertModal = false" class="px-4 py-2 rounded-xl border border-gray-200 text-sm text-gray-600 hover:bg-gray-50">{{ $isAr ? 'إلغاء' : 'Cancel' }}</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-green-600 text-white text-sm font-semibold hover:bg-green-700 transition">{{ $isAr ? 'إصدار الشهادة' : 'Issue Certificate' }}</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Edit Modal --}}
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showEditModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-lg relative z-10 shadow-2xl">
            <h2 class="text-xl font-black text-navy mb-6">{{ $isAr ? 'تعديل بيانات الطالب' : 'Edit Student Details' }}</h2>
            <form method="POST" :action="'/dashboard/students/' + editItem.id" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الاسم الكامل بالعربي' : 'Full Name in Arabic' }}</label>
                    <input type="text" name="name_ar" x-model="editItem.name_ar" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الاسم الكامل بالإنجليزي' : 'Full Name in English' }}</label>
                    <input type="text" name="name_en" x-model="editItem.name_en" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'البريد الإلكتروني' : 'Email' }}</label>
                    <input type="email" name="email" x-model="editItem.email" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'رقم الهاتف' : 'Phone Number' }}</label>
                    <div class="flex gap-2">
                        <select name="phone_code" class="border-2 border-gray-200 rounded-xl px-2 py-3 focus:border-navy transition-colors text-sm font-bold text-gray-700 flex-shrink-0" dir="ltr">
                            <option value="+20">🇪🇬 +20</option>
                            <option value="+966">🇸🇦 +966</option>
                            <option value="+971">🇦🇪 +971</option>
                            <option value="+974">🇶🇦 +974</option>
                            <option value="+965">🇰🇼 +965</option>
                            <option value="+973">🇧🇭 +973</option>
                            <option value="+968">🇴🇲 +968</option>
                            <option value="+962">🇯🇴 +962</option>
                            <option value="+961">🇱🇧 +961</option>
                            <option value="+963">🇸🇾 +963</option>
                            <option value="+964">🇮🇶 +964</option>
                            <option value="+212">🇲🇦 +212</option>
                            <option value="+216">🇹🇳 +216</option>
                            <option value="+213">🇩🇿 +213</option>
                            <option value="+1">🇺🇸 +1</option>
                            <option value="+44">🇬🇧 +44</option>
                        </select>
                        <input type="text" name="phone" x-model="editItem.phone" class="flex-1 border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr" placeholder="1234567890">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'كلمة مرور جديدة (اتركها فارغة إن لم ترد تغييرها)' : 'New Password (leave blank to keep unchanged)' }}</label>
                    <input type="password" name="password" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الحالة' : 'Status' }}</label>
                    <select name="status" x-model="editItem.status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        <option value="active">{{ $isAr ? 'نشط' : 'Active' }}</option>
                        <option value="suspended">{{ $isAr ? 'معلق' : 'Suspended' }}</option>
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
function studentsManager() {
    return {
        search: '',
        showAddModal: false,
        showEditModal: false,
        showCertModal: false,
        editItem: { id: null, name_ar: '', name_en: '', email: '', phone: '', status: 'active' },
        certStudent: { id: null, name: '', enrollments: [], selectedCourseId: '' },
        certUploadMode: 'generate',
        openEdit(id, name_ar, name_en, email, phone, status) {
            this.editItem = { id, name_ar, name_en, email, phone, status };
            this.showEditModal = true;
        },
        openCert(id, name, enrollments) {
            this.certStudent = { id, name, enrollments, selectedCourseId: enrollments.length === 1 ? enrollments[0].course_id : '' };
            this.certUploadMode = 'generate';
            this.showCertModal = true;
        }
    };
}
</script>
@endpush
@endsection
