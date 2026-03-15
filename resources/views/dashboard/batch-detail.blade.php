@extends('layouts.dashboard')
@section('title', 'INSEP PRO - تفاصيل المجموعة')
@section('dashboard-content')
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
                {{ $batch->status === 'active' ? 'نشطة' : 'منتهية' }}
            </span>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-2xl font-black text-navy">{{ $batch->enrollments->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">طلاب مسجلون</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-2xl font-black text-navy">{{ $batch->max_students }}</p>
            <p class="text-xs text-gray-500 mt-1">الحد الأقصى</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-2xl font-black text-navy">{{ $resources->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">محاضرات</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
            <p class="text-2xl font-black text-navy">{{ $liveSessions->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">جلسات مباشرة</p>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="flex border-b border-gray-100 overflow-x-auto">
            <button @click="tab = 'students'" :class="tab === 'students' ? 'border-b-2 border-navy text-navy bg-navy/5' : 'text-gray-500 hover:text-navy'" class="px-6 py-4 text-sm font-bold transition-colors whitespace-nowrap">الطلاب ({{ $batch->enrollments->count() }})</button>
            <button @click="tab = 'resources'" :class="tab === 'resources' ? 'border-b-2 border-navy text-navy bg-navy/5' : 'text-gray-500 hover:text-navy'" class="px-6 py-4 text-sm font-bold transition-colors whitespace-nowrap">المحاضرات ({{ $resources->count() }})</button>
            <button @click="tab = 'sessions'" :class="tab === 'sessions' ? 'border-b-2 border-navy text-navy bg-navy/5' : 'text-gray-500 hover:text-navy'" class="px-6 py-4 text-sm font-bold transition-colors whitespace-nowrap">البث المباشر ({{ $liveSessions->count() }})</button>
            <button @click="tab = 'attendance'" :class="tab === 'attendance' ? 'border-b-2 border-navy text-navy bg-navy/5' : 'text-gray-500 hover:text-navy'" class="px-6 py-4 text-sm font-bold transition-colors whitespace-nowrap">الحضور</button>
        </div>

        {{-- Students Tab --}}
        <div x-show="tab === 'students'" class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-navy">الطلاب المسجلون</h3>
                <button @click="showEnrollModal = true" class="bg-navy text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-navy-dark transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    إضافة طالب
                </button>
            </div>
            @if($batch->enrollments->count() > 0)
            <div class="space-y-3">
                @foreach($batch->enrollments as $enr)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-navy to-navy-light flex items-center justify-center text-white font-bold text-sm">{{ mb_substr($enr->student->name ?? '?', 0, 1) }}</div>
                        <div>
                            <p class="font-bold text-navy text-sm">{{ $enr->student->name ?? 'محذوف' }}</p>
                            <p class="text-xs text-gray-500" style="font-family:'Roboto',sans-serif">{{ $enr->student->email ?? '' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $enr->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $enr->status === 'active' ? 'نشط' : $enr->status }}</span>
                        <form method="POST" action="{{ route('dashboard.batches.unenroll', [$batch->id, $enr->id]) }}" onsubmit="return confirm('إزالة الطالب من المجموعة؟')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 hover:bg-red-50 rounded-lg text-red-500 transition-colors" title="إزالة">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-center py-8 text-gray-400">لا يوجد طلاب مسجلون في هذه المجموعة</p>
            @endif
        </div>

        {{-- Resources Tab --}}
        <div x-show="tab === 'resources'" class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-navy">المحاضرات والمحتوى</h3>
                <a href="{{ route('dashboard.resources') }}" class="bg-navy text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-navy-dark transition-colors">إدارة المحاضرات</a>
            </div>
            @if($resources->count() > 0)
            <div class="space-y-3">
                @foreach($resources as $res)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-navy/10 flex items-center justify-center">
                            <svg class="w-4 h-4 text-navy" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-navy text-sm">{{ $res->title }}</p>
                            <p class="text-xs text-gray-500">{{ $res->type ?? 'PDF' }}</p>
                        </div>
                    </div>
                    @if($res->file_url)
                    <a href="{{ $res->file_url }}" target="_blank" class="text-xs text-blue-500 hover:underline">فتح ←</a>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <p class="text-center py-8 text-gray-400">لا يوجد محتوى مرفق بهذه المجموعة</p>
            @endif
        </div>

        {{-- Live Sessions Tab --}}
        <div x-show="tab === 'sessions'" class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-navy">جلسات البث المباشر</h3>
                <button @click="showSessionModal = true" class="bg-navy text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-navy-dark transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    جلسة جديدة
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
                            {{ $sess->status === 'live' ? 'مباشر' : ($sess->status === 'ended' ? 'انتهى' : 'قادم') }}
                        </span>
                        @if($sess->live_url)
                        <a href="{{ $sess->live_url }}" target="_blank" class="text-xs text-blue-500 hover:underline">انضم ←</a>
                        @endif
                        <form method="POST" action="{{ route('dashboard.live-sessions.destroy', $sess->id) }}" onsubmit="return confirm('حذف الجلسة؟')">
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
            <p class="text-center py-8 text-gray-400">لا يوجد جلسات بث لهذه المجموعة</p>
            @endif
        </div>

        {{-- Attendance Tab --}}
        <div x-show="tab === 'attendance'" class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-navy">سجل الحضور</h3>
                <a href="{{ route('dashboard.attendance.batch', $batch->id) }}" class="bg-navy text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-navy-dark transition-colors">تسجيل الحضور</a>
            </div>
            <p class="text-sm text-gray-500">انقر على "تسجيل الحضور" لتسجيل حضور وغياب طلاب هذه المجموعة.</p>
        </div>
    </div>

    {{-- Enroll Student Modal --}}
    <div x-show="showEnrollModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showEnrollModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-md relative z-10 shadow-2xl">
            <h2 class="text-xl font-black text-navy mb-6">إضافة طالب للمجموعة</h2>
            <form method="POST" action="{{ route('dashboard.batches.enroll', $batch->id) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">اختر الطالب</label>
                    <select name="student_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                        <option value="">-- اختر طالباً --</option>
                        @foreach($allStudents as $student)
                            @if(!in_array($student->id, $enrolledIds))
                            <option value="{{ $student->id }}">{{ $student->name }} - {{ $student->email }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showEnrollModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200 transition-colors">إلغاء</button>
                    <button type="submit" class="flex-1 bg-navy hover:bg-navy-dark text-white py-3 rounded-xl font-bold transition-colors">إضافة</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Add Live Session Modal --}}
    <div x-show="showSessionModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showSessionModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-md relative z-10 shadow-2xl">
            <h2 class="text-xl font-black text-navy mb-6">إضافة جلسة بث جديدة</h2>
            <form method="POST" action="{{ route('dashboard.live-sessions.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="batch_id" value="{{ $batch->id }}">
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">عنوان الجلسة</label>
                    <input type="text" name="title" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">رابط البث</label>
                    <input type="text" name="live_url" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الموعد</label>
                    <input type="datetime-local" name="scheduled_at" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الحالة</label>
                    <select name="status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        <option value="scheduled">قادم</option>
                        <option value="live">مباشر</option>
                        <option value="ended">انتهى</option>
                    </select>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showSessionModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200 transition-colors">إلغاء</button>
                    <button type="submit" class="flex-1 bg-navy hover:bg-navy-dark text-white py-3 rounded-xl font-bold transition-colors">حفظ</button>
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
    };
}
</script>
@endpush
@endsection
