@extends('layouts.dashboard')
@section('title', 'INSEP PRO')
@section('dashboard-content')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; $role = auth()->user()->role; @endphp

{{-- ========== ADMIN / MANAGER TABLE VIEW ========== --}}
@if($role !== 'student')
<div x-data="{
    showIssueModal: false,
    showBulkModal: false,
    showImportModal: false,
    uploadMode: 'upload',
    copySerial(serial) {
        navigator.clipboard.writeText(serial);
    }
}" x-cloak>

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-black text-navy">{{ $isAr ? 'إدارة الشهادات' : 'Certificates' }}</h1>
        @if($pendingRequests > 0)
        <a href="{{ route('dashboard.certificate-requests') }}" class="text-xs text-orange-600 font-semibold mt-1 inline-flex items-center gap-1">
            <span class="w-5 h-5 bg-orange-500 text-white rounded-full text-center text-xs leading-5">{{ $pendingRequests }}</span>
            {{ $isAr ? 'طلب معلق' : 'pending request(s)' }}
        </a>
        @endif
    </div>
    @if($role === 'admin')
    <div class="flex flex-wrap gap-2">
        <button @click="showImportModal=true" class="flex items-center gap-2 px-4 py-2 rounded-xl border border-emerald-600 text-emerald-700 text-sm font-semibold hover:bg-emerald-50 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/></svg>
            {{ $isAr ? 'استيراد من Excel' : 'Import from Excel' }}
        </button>
        <button @click="showBulkModal=true" class="flex items-center gap-2 px-4 py-2 rounded-xl border border-navy text-navy text-sm font-semibold hover:bg-navy/5 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
            {{ $isAr ? 'رفع جماعي' : 'Bulk Upload' }}
        </button>
        <button @click="showIssueModal=true" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-navy text-white text-sm font-semibold hover:bg-navy/90 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ $isAr ? 'إصدار شهادة' : 'Issue Certificate' }}
        </button>
    </div>
    @endif
</div>

{{-- Flash --}}
@if(session('success'))
<div class="mb-4 px-4 py-3 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm">{{ session('success') }}</div>
@endif
@if(session('info'))
<div class="mb-4 px-4 py-3 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-sm">{{ session('info') }}</div>
@endif
@if(session('import_errors') && count(session('import_errors')) > 0)
<div class="mb-4 px-4 py-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm">
    <p class="font-semibold mb-1">{{ $isAr ? 'تحذيرات الاستيراد:' : 'Import warnings:' }}</p>
    <ul class="list-disc list-inside space-y-0.5 text-xs">
        @foreach(session('import_errors') as $err)
        <li>{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- Filters --}}
<form method="GET" class="bg-white rounded-2xl border border-gray-100 p-4 mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
    <input name="q" value="{{ request('q') }}" placeholder="{{ $isAr ? 'بحث باسم / بريد / رقم شهادة...' : 'Search name / email / serial...' }}"
        class="col-span-1 lg:col-span-2 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-navy">
    <select name="status" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-navy">
        <option value="">{{ $isAr ? 'كل الحالات' : 'All Statuses' }}</option>
        <option value="active"   @selected(request('status')==='active')>{{ $isAr ? 'سارية' : 'Valid' }}</option>
        <option value="revoked"  @selected(request('status')==='revoked')>{{ $isAr ? 'ملغاة' : 'Revoked' }}</option>
    </select>
    <select name="type" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-navy">
        <option value="">{{ $isAr ? 'كل الأنواع' : 'All Types' }}</option>
        <option value="auto"   @selected(request('type')==='auto')>{{ $isAr ? 'تلقائي' : 'Auto' }}</option>
        <option value="manual" @selected(request('type')==='manual')>{{ $isAr ? 'يدوي' : 'Manual' }}</option>
        <option value="bulk"   @selected(request('type')==='bulk')>{{ $isAr ? 'جماعي' : 'Bulk' }}</option>
        <option value="import" @selected(request('type')==='import')>{{ $isAr ? 'مستورد' : 'Imported' }}</option>
    </select>
    <button type="submit" class="bg-navy text-white rounded-xl px-4 py-2 text-sm font-semibold hover:bg-navy/90 transition">{{ $isAr ? 'بحث' : 'Search' }}</button>
</form>

{{-- Table --}}
<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 text-gray-500 text-xs uppercase">
                    <th class="px-5 py-3 text-start font-semibold">{{ $isAr ? 'المتدرب' : 'Student' }}</th>
                    <th class="px-5 py-3 text-start font-semibold">{{ $isAr ? 'الدورة' : 'Course' }}</th>
                    <th class="px-5 py-3 text-start font-semibold">{{ $isAr ? 'المجموعة' : 'Batch' }}</th>
                    <th class="px-5 py-3 text-start font-semibold">{{ $isAr ? 'رقم الشهادة' : 'Serial No.' }}</th>
                    <th class="px-5 py-3 text-start font-semibold">{{ $isAr ? 'النوع' : 'Type' }}</th>
                    <th class="px-5 py-3 text-start font-semibold">{{ $isAr ? 'التاريخ' : 'Date' }}</th>
                    <th class="px-5 py-3 text-start font-semibold">{{ $isAr ? 'الحالة' : 'Status' }}</th>
                    <th class="px-5 py-3 text-start font-semibold">{{ $isAr ? 'الإجراءات' : 'Actions' }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($certificates as $cert)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-5 py-3">
                        <div class="font-semibold text-navy text-sm">{{ $cert->student?->name ?? '—' }}</div>
                        <div class="text-gray-400 text-xs">{{ $cert->student?->email ?? '' }}</div>
                    </td>
                    <td class="px-5 py-3 text-gray-700 text-sm">{{ $cert->course?->title ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $cert->batch?->name ?? '—' }}</td>
                    <td class="px-5 py-3">
                        <span class="font-mono text-xs text-gray-600">{{ $cert->serial_number }}</span>
                        <button onclick="navigator.clipboard.writeText('{{ $cert->serial_number }}')" title="Copy" class="ml-1 text-gray-300 hover:text-navy transition">
                            <svg class="w-3.5 h-3.5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </button>
                    </td>
                    <td class="px-5 py-3">
                        @php $typeColors = ['auto'=>'bg-blue-100 text-blue-700','manual'=>'bg-purple-100 text-purple-700','bulk'=>'bg-teal-100 text-teal-700','import'=>'bg-amber-100 text-amber-700']; @endphp
                        <span class="px-2 py-0.5 rounded-lg text-xs font-semibold {{ $typeColors[$cert->type ?? 'manual'] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ ['auto'=>($isAr?'تلقائي':'Auto'),'manual'=>($isAr?'يدوي':'Manual'),'bulk'=>($isAr?'جماعي':'Bulk'),'import'=>($isAr?'مستورد':'Imported')][$cert->type ?? 'manual'] ?? $cert->type }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $cert->issue_date ?? $cert->created_at?->format('Y-m-d') }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-0.5 rounded-lg text-xs font-semibold {{ ($cert->status ?? 'active') === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ($cert->status ?? 'active') === 'active' ? ($isAr?'سارية':'Valid') : ($isAr?'ملغاة':'Revoked') }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('dashboard.certificates.download', $cert->id) }}"
                               class="text-xs text-navy font-semibold hover:underline flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                {{ $isAr ? 'تنزيل' : 'Download' }}
                            </a>
                            @if($role === 'admin' && ($cert->status ?? 'active') === 'active')
                            <form method="POST" action="{{ route('dashboard.certificates.destroy', $cert->id) }}"
                                  onsubmit="return confirm('{{ $isAr ? 'إلغاء هذه الشهادة؟' : 'Revoke this certificate?' }}')">
                                @csrf @method('DELETE')
                                <button class="text-xs text-red-500 hover:underline">{{ $isAr ? 'إلغاء' : 'Revoke' }}</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-5 py-12 text-center text-gray-400">{{ $isAr ? 'لا يوجد شهادات بعد' : 'No certificates found' }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ====== ISSUE CERTIFICATE MODAL ====== --}}
@if($role === 'admin')
<div x-show="showIssueModal" class="fixed inset-0 z-50 flex items-center justify-center p-4"
     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showIssueModal=false"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 z-10 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-black text-navy">{{ $isAr ? 'إصدار شهادة جديدة' : 'Issue New Certificate' }}</h2>
            <button @click="showIssueModal=false" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('dashboard.certificates.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">{{ $isAr ? 'المتدرب *' : 'Student *' }}</label>
                <select name="student_id" required class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-navy">
                    <option value="">— {{ $isAr ? 'اختر المتدرب' : 'Select Student' }} —</option>
                    @foreach($students as $s)
                    <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->email }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">{{ $isAr ? 'الدورة *' : 'Course *' }}</label>
                <select name="course_id" required class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-navy">
                    <option value="">— {{ $isAr ? 'اختر الدورة' : 'Select Course' }} —</option>
                    @foreach($courses as $c)
                    <option value="{{ $c->id }}">{{ $c->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">{{ $isAr ? 'المجموعة (اختياري)' : 'Batch (optional)' }}</label>
                <select name="batch_id" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-navy">
                    <option value="">—</option>
                    @foreach($batches as $b)
                    <option value="{{ $b->id }}">{{ $b->name }} ({{ $b->course?->title }})</option>
                    @endforeach
                </select>
            </div>
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
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">{{ $isAr ? 'تاريخ الإصدار' : 'Issue Date' }}</label>
                <input name="issue_date" type="date" value="{{ now()->toDateString() }}"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-navy">
            </div>
            {{-- Upload mode toggle --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-2">{{ $isAr ? 'ملف الشهادة' : 'Certificate File' }}</label>
                <div class="flex gap-2 mb-3">
                    <button type="button" @click="uploadMode='upload'"
                        :class="uploadMode==='upload' ? 'bg-navy text-white' : 'bg-gray-100 text-gray-600'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                        {{ $isAr ? 'رفع PDF' : 'Upload PDF' }}
                    </button>
                    <button type="button" @click="uploadMode='generate'"
                        :class="uploadMode==='generate' ? 'bg-navy text-white' : 'bg-gray-100 text-gray-600'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                        {{ $isAr ? 'توليد تلقائي' : 'Auto Generate' }}
                    </button>
                </div>
                <div x-show="uploadMode==='upload'">
                    <input name="certificate_file" type="file" accept=".pdf,.jpg,.png"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-navy">
                </div>
                <div x-show="uploadMode==='generate'" class="text-xs text-gray-400 py-2">
                    <input type="hidden" name="generate_pdf" value="1">
                    {{ $isAr ? 'سيتم توليد شهادة PDF تلقائياً من البيانات المدخلة.' : 'A PDF certificate will be auto-generated from the entered data.' }}
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" @click="showIssueModal=false" class="px-4 py-2 rounded-xl border border-gray-200 text-sm text-gray-600 hover:bg-gray-50">{{ $isAr ? 'إلغاء' : 'Cancel' }}</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-navy text-white text-sm font-semibold hover:bg-navy/90">{{ $isAr ? 'إصدار' : 'Issue' }}</button>
            </div>
        </form>
    </div>
</div>

{{-- ====== IMPORT FROM EXCEL MODAL ====== --}}
<div x-show="showImportModal" class="fixed inset-0 z-50 flex items-center justify-center p-4"
     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showImportModal=false"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 z-10">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-black text-navy">{{ $isAr ? 'استيراد شهادات من Excel' : 'Import Certificates from Excel' }}</h2>
            <button @click="showImportModal=false" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Step 1: download template --}}
        <div class="mb-5 p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
            <p class="text-sm font-semibold text-emerald-800 mb-1">{{ $isAr ? 'الخطوة 1: نزّل النموذج' : 'Step 1: Download the template' }}</p>
            <p class="text-xs text-emerald-700 mb-3">{{ $isAr ? 'افتح الملف وأدخل بيانات الشهادات القديمة في ورقة "Certificates". احذف صفوف المثال قبل الحفظ.' : 'Open the file and enter your certificate data in the "Certificates" sheet. Delete the example rows before saving.' }}</p>
            <a href="{{ route('dashboard.certificates.template') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                {{ $isAr ? 'تنزيل نموذج Excel' : 'Download Excel Template' }}
            </a>
        </div>

        {{-- Step 2: upload filled file --}}
        <div class="mb-5 p-4 bg-blue-50 border border-blue-200 rounded-xl text-xs text-blue-700 space-y-1">
            <p class="font-semibold text-sm">{{ $isAr ? 'الأعمدة المطلوبة:' : 'Required columns:' }}</p>
            <p><span class="font-mono bg-white px-1 rounded">student_email</span> {{ $isAr ? '— البريد الإلكتروني المسجّل للطالب' : '— registered email of the student' }}</p>
            <p><span class="font-mono bg-white px-1 rounded">course_id</span> {{ $isAr ? '— الرقم التعريفي للدورة' : '— numeric course ID' }}</p>
            <p class="text-blue-500">{{ $isAr ? 'باقي الأعمدة اختيارية — كل صف ينتج PDF تلقائياً.' : 'Remaining columns are optional — each row auto-generates a PDF.' }}</p>
        </div>

        <form method="POST" action="{{ route('dashboard.certificates.import') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">{{ $isAr ? 'ملف Excel المعبّأ (.xlsx) *' : 'Filled Excel file (.xlsx) *' }}</label>
                <input name="excel_file" type="file" accept=".xlsx,.xls" required
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-navy">
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" @click="showImportModal=false" class="px-4 py-2 rounded-xl border border-gray-200 text-sm text-gray-600 hover:bg-gray-50">{{ $isAr ? 'إلغاء' : 'Cancel' }}</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">{{ $isAr ? 'استيراد وإنشاء PDF' : 'Import & Generate PDFs' }}</button>
            </div>
        </form>
    </div>
</div>

{{-- ====== BULK UPLOAD MODAL ====== --}}
<div x-show="showBulkModal" class="fixed inset-0 z-50 flex items-center justify-center p-4"
     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showBulkModal=false"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 z-10">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-black text-navy">{{ $isAr ? 'رفع جماعي للشهادات' : 'Bulk Upload Certificates' }}</h2>
            <button @click="showBulkModal=false" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="mb-4 p-3 bg-blue-50 rounded-xl text-xs text-blue-700 space-y-1">
            <p class="font-semibold">{{ $isAr ? 'تنسيق ملف Excel المطلوب:' : 'Required Excel columns:' }}</p>
            <p class="font-mono">user_email | course_id | batch_id | certificate_code | file_name</p>
            <p>{{ $isAr ? 'ملف ZIP يحتوي على ملفات PDF بأسماء تطابق عمود file_name' : 'ZIP file containing PDFs with names matching the file_name column' }}</p>
        </div>
        <form method="POST" action="{{ route('dashboard.certificates.bulk') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">{{ $isAr ? 'ملف Excel (.xlsx) *' : 'Excel File (.xlsx) *' }}</label>
                <input name="excel_file" type="file" accept=".xlsx,.xls" required
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-navy">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">{{ $isAr ? 'ملف ZIP للشهادات *' : 'ZIP File with PDFs *' }}</label>
                <input name="zip_file" type="file" accept=".zip" required
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-navy">
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" @click="showBulkModal=false" class="px-4 py-2 rounded-xl border border-gray-200 text-sm text-gray-600 hover:bg-gray-50">{{ $isAr ? 'إلغاء' : 'Cancel' }}</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-navy text-white text-sm font-semibold hover:bg-navy/90">{{ $isAr ? 'رفع واستيراد' : 'Upload & Import' }}</button>
            </div>
        </form>
    </div>
</div>
@endif

</div>{{-- end x-data --}}

{{-- ========== STUDENT CARD VIEW ========== --}}
@else
<div x-data="{ showRequestModal: false }">
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-black text-navy">{{ $isAr ? 'شهاداتي' : 'My Certificates' }}</h1>
    <button @click="showRequestModal=true" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-navy text-white text-sm font-semibold hover:bg-navy/90 transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        {{ $isAr ? 'طلب شهادة' : 'Request Certificate' }}
    </button>
</div>

@if(session('success'))
<div class="mb-4 px-4 py-3 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm">{{ session('success') }}</div>
@endif
@if(session('info'))
<div class="mb-4 px-4 py-3 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-sm">{{ session('info') }}</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($certificates as $cert)
    <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 card-hover">
        <div class="bg-gradient-to-l from-navy to-navy-light p-4 relative overflow-hidden">
            <div class="absolute inset-0 hero-pattern opacity-20"></div>
            <div class="relative z-10 flex items-center gap-3">
                <svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>
                <div class="text-white">
                    <h3 class="font-bold text-sm">{{ $cert->course?->title ?? ($isAr ? 'شهادة معتمدة' : 'Certified') }}</h3>
                    <p class="text-white/60 text-xs font-mono">{{ $cert->serial_number }}</p>
                </div>
            </div>
        </div>
        <div class="p-4 space-y-2">
            <p class="text-sm"><span class="text-gray-400">{{ $isAr ? 'تاريخ الإصدار:' : 'Issue Date:' }}</span> <span class="text-navy">{{ $cert->issue_date ?? $cert->created_at?->format('Y-m-d') }}</span></p>
            @if($cert->grade)
            <p class="text-sm"><span class="text-gray-400">{{ $isAr ? 'التقدير:' : 'Grade:' }}</span> <span class="font-bold text-navy">{{ $cert->grade }}</span></p>
            @endif
            <span class="inline-block px-3 py-1 rounded-lg text-xs font-bold {{ ($cert->status ?? 'active') === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ ($cert->status ?? 'active') === 'active' ? ($isAr ? 'سارية' : 'Valid') : ($isAr ? 'ملغاة' : 'Revoked') }}
            </span>
            {{-- QR --}}
            @if($cert->serial_number)
            <div class="pt-3 border-t border-gray-50 flex flex-col items-center gap-2">
                <p class="text-xs text-gray-400 self-start">{{ $isAr ? 'رمز التحقق' : 'Verification QR' }}</p>
                <canvas id="qr-{{ $cert->id }}" class="rounded-lg"></canvas>
            </div>
            @endif
            {{-- Actions --}}
            <div class="pt-2 flex gap-2">
                <a href="{{ route('dashboard.certificates.download', $cert->id) }}"
                   class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-navy text-white text-xs font-semibold hover:bg-navy/90 transition">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    {{ $isAr ? 'تنزيل' : 'Download' }}
                </a>
                <button onclick="navigator.clipboard.writeText('{{ $cert->serial_number }}')"
                        class="px-3 py-2 rounded-xl border border-gray-200 text-gray-500 text-xs hover:bg-gray-50 transition" title="{{ $isAr ? 'نسخ الرقم' : 'Copy serial' }}">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-3 text-center py-12 text-gray-400">{{ $isAr ? 'لا يوجد شهادات بعد' : 'No certificates yet' }}</div>
    @endforelse
</div>

{{-- Request Certificate Modal --}}
<div x-show="showRequestModal" class="fixed inset-0 z-50 flex items-center justify-center p-4"
     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showRequestModal=false"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 z-10">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-black text-navy">{{ $isAr ? 'طلب شهادة' : 'Request a Certificate' }}</h2>
            <button @click="showRequestModal=false" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('dashboard.certificate-requests.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">{{ $isAr ? 'الدورة *' : 'Course *' }}</label>
                @php $myEnrollments = auth()->user()->enrollments()->with(['course','batch'])->get(); @endphp
                <select name="course_id" required class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-navy">
                    <option value="">— {{ $isAr ? 'اختر الدورة' : 'Select Course' }} —</option>
                    @foreach($myEnrollments as $enr)
                    <option value="{{ $enr->course_id }}" data-batch="{{ $enr->batch_id }}">{{ $enr->course?->title }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="batch_id" id="req_batch_id">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">{{ $isAr ? 'ملاحظات (اختياري)' : 'Notes (optional)' }}</label>
                <textarea name="notes" rows="2" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-navy resize-none"></textarea>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" @click="showRequestModal=false" class="px-4 py-2 rounded-xl border border-gray-200 text-sm text-gray-600">{{ $isAr ? 'إلغاء' : 'Cancel' }}</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-navy text-white text-sm font-semibold hover:bg-navy/90">{{ $isAr ? 'إرسال الطلب' : 'Submit Request' }}</button>
            </div>
        </form>
    </div>
</div>
</div>
@endif

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var certs = @json($certificates->map(fn($c) => ['id' => $c->id, 'serial' => $c->serial_number]));
    certs.forEach(function (cert) {
        if (!cert.serial) return;
        var canvas = document.getElementById('qr-' + cert.id);
        if (!canvas) return;
        QRCode.toCanvas(canvas, cert.serial, { width: 90, margin: 1, color: { dark: '#1a2744', light: '#ffffff' } });
    });
    // Auto-fill batch_id from enrollment selection
    var courseSelect = document.querySelector('select[name="course_id"]');
    var batchInput   = document.getElementById('req_batch_id');
    if (courseSelect && batchInput) {
        courseSelect.addEventListener('change', function() {
            var opt = courseSelect.options[courseSelect.selectedIndex];
            batchInput.value = opt.dataset.batch || '';
        });
    }
});
</script>
@endpush
@endsection
