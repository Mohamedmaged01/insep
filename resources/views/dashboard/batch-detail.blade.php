@extends('layouts.dashboard')
@section('title', 'INSEP PRO')
@section('dashboard-content')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
<div x-data="batchDetailManager()">

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-green-700 text-sm font-bold">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('dashboard.batches') }}" class="p-2 hover:bg-gray-100 rounded-xl transition-colors text-gray-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-navy">{{ $batch->name }}</h1>
            <p class="text-gray-500 text-sm">{{ $batch->course->title ?? '-' }} &bull; {{ $batch->instructor->name ?? '-' }}</p>
        </div>
        <div class="mr-auto">
            <span class="px-3 py-1.5 rounded-xl text-xs font-bold {{ $batch->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                {{ $batch->status === 'active' ? ($isAr ? 'نشطة' : 'Active') : ($isAr ? 'منتهية' : 'Completed') }}
            </span>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-2xl font-black text-navy">{{ $batch->enrollments->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'طلاب مسجلون' : 'Enrolled Students' }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-2xl font-black text-navy">{{ $batch->max_students }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'الحد الأقصى' : 'Max Students' }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-2xl font-black text-navy">{{ $resources->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'محاضرات' : 'Lectures' }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-2xl font-black text-navy">{{ $liveSessions->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'جلسات مباشرة' : 'Live Sessions' }}</p>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="flex border-b border-gray-100 overflow-x-auto">
            <button @click="tab = 'students'" :class="tab === 'students' ? 'border-b-2 border-navy text-navy bg-navy/5' : 'text-gray-500 hover:text-navy'" class="px-6 py-4 text-sm font-bold transition-colors whitespace-nowrap">{{ $isAr ? 'الطلاب' : 'Students' }} ({{ $batch->enrollments->count() }})</button>
            <button @click="tab = 'resources'" :class="tab === 'resources' ? 'border-b-2 border-navy text-navy bg-navy/5' : 'text-gray-500 hover:text-navy'" class="px-6 py-4 text-sm font-bold transition-colors whitespace-nowrap">{{ $isAr ? 'المحاضرات' : 'Lectures' }} ({{ $resources->count() }})</button>
            <button @click="tab = 'sessions'" :class="tab === 'sessions' ? 'border-b-2 border-navy text-navy bg-navy/5' : 'text-gray-500 hover:text-navy'" class="px-6 py-4 text-sm font-bold transition-colors whitespace-nowrap">{{ $isAr ? 'البث المباشر' : 'Live Sessions' }} ({{ $liveSessions->count() }})</button>
            <button @click="tab = 'attendance'" :class="tab === 'attendance' ? 'border-b-2 border-navy text-navy bg-navy/5' : 'text-gray-500 hover:text-navy'" class="px-6 py-4 text-sm font-bold transition-colors whitespace-nowrap">{{ $isAr ? 'الحضور' : 'Attendance' }}</button>
        </div>

        {{-- Students Tab --}}
        <div x-show="tab === 'students'" class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-navy">{{ $isAr ? 'الطلاب المسجلون' : 'Enrolled Students' }}</h3>
                <button @click="showEnrollModal = true" class="bg-navy text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-navy-dark transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    {{ $isAr ? 'إضافة طالب' : 'Add Student' }}
                </button>
            </div>
            @if($batch->enrollments->count() > 0)
            <div class="space-y-3">
                @foreach($batch->enrollments as $enr)
                @php $cert = $certificates[$enr->student_id] ?? null; @endphp
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-navy to-navy-light flex items-center justify-center text-white font-bold text-sm">{{ mb_substr($enr->student->name_ar ?? $enr->student->name ?? '?', 0, 1) }}</div>
                        <div>
                            <p class="font-bold text-navy text-sm">{{ $enr->student->name_ar ?? $enr->student->name ?? ($isAr ? 'محذوف' : 'Deleted') }}</p>
                            @if($enr->student->name_en)
                            <p class="text-xs text-gray-400">{{ $enr->student->name_en }}</p>
                            @endif
                            <p class="text-xs text-gray-500" style="font-family:'Roboto',sans-serif">{{ $enr->student->email ?? '' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $enr->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $enr->status === 'active' ? ($isAr ? 'نشط' : 'Active') : $enr->status }}</span>
                        {{-- Certificate button --}}
                        <button
                            @click="openCertModal({
                                studentName: '{{ addslashes($enr->student->name_ar ?? $enr->student->name ?? ($isAr ? 'محذوف' : 'Deleted')) }}',
                                studentNameEn: '{{ addslashes($enr->student->name_en ?? '') }}',
                                hasCert: {{ $cert ? 'true' : 'false' }},
                                serial: '{{ $cert?->serial_number ?? '' }}',
                                title: '{{ addslashes($cert?->title ?? '') }}',
                                grade: '{{ $cert?->grade ?? '' }}',
                                issueDate: '{{ $cert?->issue_date ?? '' }}',
                                status: '{{ $cert?->status ?? '' }}'
                            })"
                            class="p-1.5 rounded-lg transition-colors {{ $cert ? 'hover:bg-yellow-50 text-yellow-500' : 'hover:bg-gray-100 text-gray-300' }}"
                            title="{{ $isAr ? 'عرض الشهادة' : 'View Certificate' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="6"/><path stroke-linecap="round" d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
                        </button>
                        <form method="POST" action="{{ route('dashboard.batches.unenroll', [$batch->id, $enr->id]) }}" onsubmit="return confirm('{{ $isAr ? 'إزالة الطالب من المجموعة؟' : 'Remove student from batch?' }}')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 hover:bg-red-50 rounded-lg text-red-500 transition-colors" title="{{ $isAr ? 'إزالة' : 'Remove' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-center py-8 text-gray-400">{{ $isAr ? 'لا يوجد طلاب مسجلون في هذه المجموعة' : 'No students enrolled in this batch' }}</p>
            @endif
        </div>

        {{-- Resources Tab --}}
        <div x-show="tab === 'resources'" class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-navy">{{ $isAr ? 'المحاضرات والمحتوى' : 'Lectures & Content' }}</h3>
                <button @click="showResourceModal = true" class="bg-navy text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-navy-dark transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    {{ $isAr ? 'رفع ملف' : 'Upload File' }}
                </button>
            </div>
            @if($resources->count() > 0)
            <div class="space-y-3">
                @foreach($resources as $res)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-navy/10 flex items-center justify-center">
                            @if(in_array(strtolower($res->type ?? ''), ['video', 'mp4']))
                                <svg class="w-4 h-4 text-navy" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                            @else
                                <svg class="w-4 h-4 text-navy" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            @endif
                        </div>
                        <div>
                            <p class="font-bold text-navy text-sm">{{ $res->title }}</p>
                            <p class="text-xs text-gray-500">{{ $res->type ?? 'PDF' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        @if($res->file_url)
                        <a href="{{ $res->file_url }}" target="_blank" class="text-xs text-blue-500 hover:underline flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            {{ $isAr ? 'تحميل' : 'Download' }}
                        </a>
                        @endif
                        <form method="POST" action="{{ route('dashboard.resources.destroy', $res->id) }}" onsubmit="return confirm('{{ $isAr ? 'حذف هذا الملف؟' : 'Delete this file?' }}')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 hover:bg-red-50 rounded-lg text-red-400 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-center py-8 text-gray-400">{{ $isAr ? 'لا يوجد محتوى مرفق بهذه المجموعة' : 'No content attached to this batch' }}</p>
            @endif
        </div>

        {{-- Live Sessions Tab --}}
        <div x-show="tab === 'sessions'" class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-navy">{{ $isAr ? 'جلسات البث المباشر' : 'Live Sessions' }}</h3>
                <button @click="showSessionModal = true" class="bg-navy text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-navy-dark transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    {{ $isAr ? 'جلسة جديدة' : 'New Session' }}
                </button>
            </div>
            @if($liveSessions->count() > 0)
            <div class="space-y-3">
                @foreach($liveSessions as $sess)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div>
                        <p class="font-bold text-navy text-sm">{{ $sess->title }}</p>
                        <p class="text-xs text-gray-500" style="font-family:'Roboto',sans-serif">{{ $sess->scheduled_at ?? '-' }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $sess->status === 'live' ? 'bg-red-100 text-red-700' : ($sess->status === 'ended' ? 'bg-gray-100 text-gray-500' : 'bg-blue-100 text-blue-700') }}">
                            {{ $sess->status === 'live' ? ($isAr ? 'مباشر' : 'Live') : ($sess->status === 'ended' ? ($isAr ? 'انتهى' : 'Ended') : ($isAr ? 'قادم' : 'Upcoming')) }}
                        </span>
                        @if($sess->live_url)
                        <a href="{{ $sess->live_url }}" target="_blank" class="text-xs text-blue-500 hover:underline">{{ $isAr ? 'انضم ←' : 'Join ←' }}</a>
                        @endif
                        <form method="POST" action="{{ route('dashboard.live-sessions.destroy', $sess->id) }}" onsubmit="return confirm('{{ $isAr ? 'حذف الجلسة؟' : 'Delete this session?' }}')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 hover:bg-red-50 rounded-lg text-red-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-center py-8 text-gray-400">{{ $isAr ? 'لا يوجد جلسات بث لهذه المجموعة' : 'No live sessions for this batch' }}</p>
            @endif
        </div>

        {{-- Attendance Tab --}}
        <div x-show="tab === 'attendance'" class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-navy">{{ $isAr ? 'سجل الحضور' : 'Attendance Record' }}</h3>
                <a href="{{ route('dashboard.attendance.batch', $batch->id) }}" class="bg-navy text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-navy-dark transition-colors">{{ $isAr ? 'تسجيل الحضور' : 'Record Attendance' }}</a>
            </div>
            <p class="text-sm text-gray-500">{{ $isAr ? 'انقر على "تسجيل الحضور" لتسجيل حضور وغياب طلاب هذه المجموعة.' : 'Click "Record Attendance" to record attendance for students in this batch.' }}</p>
        </div>
    </div>

    {{-- Upload Resource Modal --}}
    <div x-show="showResourceModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showResourceModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-md relative z-10 shadow-2xl" x-data="{ uploadMode: 'file' }">
            <h2 class="text-xl font-black text-navy mb-4">{{ $isAr ? 'إضافة محتوى' : 'Add Content' }}</h2>

            {{-- Toggle --}}
            <div class="flex bg-gray-100 rounded-xl p-1 mb-5">
                <button type="button" @click="uploadMode = 'file'" :class="uploadMode === 'file' ? 'bg-white shadow text-navy' : 'text-gray-500'" class="flex-1 py-2 rounded-lg text-sm font-bold transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                    {{ $isAr ? 'رفع ملف' : 'Upload File' }}
                </button>
                <button type="button" @click="uploadMode = 'link'" :class="uploadMode === 'link' ? 'bg-white shadow text-navy' : 'text-gray-500'" class="flex-1 py-2 rounded-lg text-sm font-bold transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    {{ $isAr ? 'رابط فيديو' : 'Video Link' }}
                </button>
            </div>

            <form method="POST" action="{{ route('dashboard.resources.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="batch_id" value="{{ $batch->id }}">
                <input type="hidden" name="course_id" value="{{ $batch->course_id }}">
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'العنوان' : 'Title' }}</label>
                    <input type="text" name="title" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'نوع المحتوى' : 'Content Type' }}</label>
                    <select name="type" x-model="uploadMode === 'link' ? 'VideoLink' : undefined" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        <option value="PDF" x-show="uploadMode === 'file'">PDF</option>
                        <option value="Video" x-show="uploadMode === 'file'">{{ $isAr ? 'فيديو' : 'Video' }}</option>
                        <option value="Word" x-show="uploadMode === 'file'">Word</option>
                        <option value="Excel" x-show="uploadMode === 'file'">Excel</option>
                        <option value="PowerPoint" x-show="uploadMode === 'file'">PowerPoint</option>
                        <option value="Other" x-show="uploadMode === 'file'">{{ $isAr ? 'أخرى' : 'Other' }}</option>
                        <option value="VideoLink" x-show="uploadMode === 'link'" selected>{{ $isAr ? 'رابط فيديو' : 'Video Link' }}</option>
                        <option value="YouTube" x-show="uploadMode === 'link'">YouTube</option>
                        <option value="Vimeo" x-show="uploadMode === 'link'">Vimeo</option>
                        <option value="ExternalLink" x-show="uploadMode === 'link'">{{ $isAr ? 'رابط خارجي' : 'External Link' }}</option>
                    </select>
                </div>

                {{-- File upload --}}
                <div x-show="uploadMode === 'file'">
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الملف' : 'File' }}</label>
                    <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-navy hover:bg-navy/5 transition-colors">
                        <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                        <span class="text-sm text-gray-500" x-ref="fileLabel">{{ $isAr ? 'اضغط لاختيار ملف' : 'Click to select a file' }}</span>
                        <input type="file" name="file" class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.mp4,.mov,.avi,.zip,.rar" @change="$refs.fileLabel.textContent = $event.target.files[0]?.name || '{{ $isAr ? 'اضغط لاختيار ملف' : 'Click to select a file' }}'">
                    </label>
                </div>

                {{-- Link input --}}
                <div x-show="uploadMode === 'link'">
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'رابط الفيديو' : 'Video URL' }}</label>
                    <input type="url" name="file_url" placeholder="https://www.youtube.com/watch?v=..." class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors font-mono text-sm" dir="ltr" :required="uploadMode === 'link'">
                    <p class="text-xs text-gray-400 mt-1.5">{{ $isAr ? 'يدعم: YouTube، Vimeo، أو أي رابط فيديو مباشر' : 'Supports: YouTube, Vimeo, or any direct video link' }}</p>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showResourceModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200 transition-colors">{{ $isAr ? 'إلغاء' : 'Cancel' }}</button>
                    <button type="submit" class="flex-1 bg-navy hover:bg-navy-dark text-white py-3 rounded-xl font-bold transition-colors">{{ $isAr ? 'حفظ' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Enroll Student Modal --}}
    <div x-show="showEnrollModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showEnrollModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-md relative z-10 shadow-2xl">
            <h2 class="text-xl font-black text-navy mb-6">{{ $isAr ? 'إضافة طالب للمجموعة' : 'Add Student to Batch' }}</h2>
            <form method="POST" action="{{ route('dashboard.batches.enroll', $batch->id) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'اختر الطالب' : 'Select Student' }}</label>
                    <select name="student_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                        <option value="">{{ $isAr ? '-- اختر طالباً --' : '-- Select a Student --' }}</option>
                        @foreach($allStudents as $student)
                            @if(!in_array($student->id, $enrolledIds))
                            <option value="{{ $student->id }}">{{ $student->name }} - {{ $student->email }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showEnrollModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200 transition-colors">{{ $isAr ? 'إلغاء' : 'Cancel' }}</button>
                    <button type="submit" class="flex-1 bg-navy hover:bg-navy-dark text-white py-3 rounded-xl font-bold transition-colors">{{ $isAr ? 'إضافة' : 'Add' }}</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Certificate Modal --}}
    <div x-show="showCertModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showCertModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-md relative z-10 shadow-2xl">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-black text-navy">{{ $isAr ? 'شهادة الطالب' : 'Student Certificate' }}</h2>
                <button @click="showCertModal = false" class="p-2 hover:bg-gray-100 rounded-xl text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            {{-- Student name --}}
            <div class="flex items-center gap-3 mb-6 p-4 bg-gray-50 rounded-xl">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-navy to-navy-light flex items-center justify-center text-white font-bold" x-text="certData.studentName.charAt(0)"></div>
                <div>
                    <p class="font-black text-navy" x-text="certData.studentName"></p>
                    <p class="text-xs text-gray-500" x-show="certData.studentNameEn" x-text="certData.studentNameEn" style="font-family:'Roboto',sans-serif"></p>
                </div>
            </div>
            {{-- Has certificate --}}
            <template x-if="certData.hasCert">
                <div class="space-y-3">
                    <div class="border border-yellow-200 bg-yellow-50 rounded-xl p-5 text-center">
                        <svg class="w-10 h-10 text-yellow-500 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="8" r="6"/><path stroke-linecap="round" d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
                        <p class="font-black text-navy text-lg" x-text="certData.title || '{{ $isAr ? 'شهادة إتمام' : 'Completion Certificate' }}'"></p>
                        <p class="text-xs text-gray-500 mt-1" x-text="certData.serial" style="font-family:'Roboto',sans-serif"></p>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-gray-50 rounded-xl p-3">
                            <p class="text-xs text-gray-400 mb-1">{{ $isAr ? 'التقدير' : 'Grade' }}</p>
                            <p class="font-bold text-navy" x-text="certData.grade || '-'"></p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3">
                            <p class="text-xs text-gray-400 mb-1">{{ $isAr ? 'تاريخ الإصدار' : 'Issue Date' }}</p>
                            <p class="font-bold text-navy" x-text="certData.issueDate || '-'" style="font-family:'Roboto',sans-serif"></p>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3">
                        <p class="text-xs text-gray-400 mb-1">{{ $isAr ? 'الحالة' : 'Status' }}</p>
                        <span :class="certData.status === 'issued' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'" class="px-3 py-1 rounded-lg text-xs font-bold" x-text="certData.status === 'issued' ? '{{ $isAr ? 'صادرة' : 'Issued' }}' : (certData.status || '-')"></span>
                    </div>
                </div>
            </template>
            {{-- No certificate --}}
            <template x-if="!certData.hasCert">
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="8" r="6"/><path stroke-linecap="round" d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
                    <p class="text-gray-400 font-bold">{{ $isAr ? 'لا توجد شهادة لهذا الطالب في هذه الدورة' : 'No certificate for this student in this course' }}</p>
                </div>
            </template>
        </div>
    </div>

    {{-- Add Live Session Modal --}}
    <div x-show="showSessionModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showSessionModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-md relative z-10 shadow-2xl">
            <h2 class="text-xl font-black text-navy mb-6">{{ $isAr ? 'إضافة جلسة بث جديدة' : 'Add New Live Session' }}</h2>
            <form method="POST" action="{{ route('dashboard.live-sessions.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="batch_id" value="{{ $batch->id }}">
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'عنوان الجلسة' : 'Session Title' }}</label>
                    <input type="text" name="title" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'رابط البث' : 'Session Link' }}</label>
                    <input type="text" name="live_url" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الموعد' : 'Scheduled At' }}</label>
                    <input type="datetime-local" name="scheduled_at" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الحالة' : 'Status' }}</label>
                    <select name="status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        <option value="scheduled">{{ $isAr ? 'قادم' : 'Upcoming' }}</option>
                        <option value="live">{{ $isAr ? 'مباشر' : 'Live' }}</option>
                        <option value="ended">{{ $isAr ? 'انتهى' : 'Ended' }}</option>
                    </select>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showSessionModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200 transition-colors">{{ $isAr ? 'إلغاء' : 'Cancel' }}</button>
                    <button type="submit" class="flex-1 bg-navy hover:bg-navy-dark text-white py-3 rounded-xl font-bold transition-colors">{{ $isAr ? 'حفظ' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </div>

</div>
@push('scripts')
<script>
function batchDetailManager() {
    return {
        tab: 'students',
        showEnrollModal: false,
        showSessionModal: false,
        showResourceModal: false,
        showCertModal: false,
        certData: {},
        openCertModal(data) {
            this.certData = data;
            this.showCertModal = true;
        }
    };
}
</script>
@endpush
@endsection
