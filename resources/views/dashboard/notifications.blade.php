@extends('layouts.dashboard')
@section('title', 'INSEP PRO - الإشعارات')
@section('dashboard-content')
<div x-data="{ showSendModal: false }">

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-green-700 text-sm font-bold">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-navy">الإشعارات</h1>
            <p class="text-gray-500 text-sm">{{ $notifications->count() }} إشعار</p>
        </div>
        @if(auth()->user()->role !== 'student')
        <button @click="showSendModal = true" class="bg-red-brand hover:bg-red-brand-dark text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            إرسال إشعار
        </button>
        @endif
    </div>

    {{-- Notifications List --}}
    <div class="space-y-3">
        @forelse($notifications as $notif)
        <div class="bg-white rounded-2xl p-5 border {{ $notif->is_read ? 'border-gray-100' : 'border-navy/20' }} card-hover flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl {{ $notif->is_read ? 'bg-gray-100' : 'bg-navy/10' }} flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 {{ $notif->is_read ? 'text-gray-400' : 'text-navy' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
            </div>
            <div class="flex-1">
                @if(auth()->user()->role !== 'student')
                <p class="text-xs text-gray-400 mb-1">إلى: {{ $notif->user->name ?? '-' }}</p>
                @endif
                <p class="font-bold text-navy text-sm {{ $notif->is_read ? '' : 'text-navy' }}">{{ $notif->text }}</p>
                <div class="flex items-center gap-3 mt-2">
                    @if($notif->type)
                    <span class="text-xs font-bold px-2 py-0.5 bg-gray-100 text-gray-500 rounded-lg">{{ $notif->type }}</span>
                    @endif
                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</p>
                    @if(!$notif->is_read && auth()->user()->role === 'student')
                    <form method="POST" action="{{ route('dashboard.notifications.read', $notif->id) }}">
                        @csrf
                        <button type="submit" class="text-xs text-navy hover:underline font-bold">تحديد كمقروء</button>
                    </form>
                    @endif
                </div>
            </div>
            @if(!$notif->is_read)
            <div class="w-2 h-2 rounded-full bg-navy mt-2 flex-shrink-0"></div>
            @endif
        </div>
        @empty
        <div class="text-center py-12 text-gray-400">لا يوجد إشعارات</div>
        @endforelse
    </div>

    {{-- Send Notification Modal (Admin/Instructor only) --}}
    @if(auth()->user()->role !== 'student')
    <div x-show="showSendModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showSendModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-lg relative z-10 shadow-2xl">
            <h2 class="text-xl font-black text-navy mb-6">إرسال إشعار</h2>
            <form method="POST" action="{{ route('dashboard.notifications.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">نص الإشعار</label>
                    <textarea name="text" rows="3" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required placeholder="اكتب نص الإشعار هنا..."></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">نوع الإشعار</label>
                        <select name="type" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="general">عام</option>
                            <option value="attendance">حضور</option>
                            <option value="live">بث مباشر</option>
                            <option value="resource">محتوى جديد</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">إرسال إلى</label>
                        <select name="batch_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="">جميع الطلاب</option>
                            @foreach($batches as $batch)
                            <option value="{{ $batch->id }}">{{ $batch->name }} - {{ $batch->course->title ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showSendModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200 transition-colors">إلغاء</button>
                    <button type="submit" class="flex-1 bg-navy hover:bg-navy-dark text-white py-3 rounded-xl font-bold transition-colors">إرسال</button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
@endsection
