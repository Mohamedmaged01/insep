@extends('layouts.dashboard')
@section('title', 'INSEP PRO - البث المباشر')
@section('dashboard-content')
<div x-data="liveSessionsManager()">

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-green-700 text-sm font-bold">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-navy">البث المباشر</h1>
            <p class="text-gray-500 text-sm">إجمالي {{ $sessions->count() }} جلسة</p>
        </div>
        @if(auth()->user()->role !== 'student')
        <button @click="showAddModal = true" class="bg-red-brand hover:bg-red-brand-dark text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            جلسة جديدة
        </button>
        @endif
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead><tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">العنوان</th>
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">المجموعة</th>
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الموعد</th>
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الحالة</th>
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الرابط</th>
                    @if(auth()->user()->role !== 'student')
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">إجراءات</th>
                    @endif
                </tr></thead>
                <tbody>
                    @forelse($sessions as $sess)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                        <td class="px-6 py-4 font-bold text-navy text-sm">{{ $sess->title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $sess->batch->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500" style="font-family: 'Roboto', sans-serif">{{ $sess->scheduled_at ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $sess->status === 'live' ? 'bg-red-100 text-red-700' : ($sess->status === 'ended' ? 'bg-gray-100 text-gray-500' : 'bg-blue-100 text-blue-700') }}">
                                {{ $sess->status === 'live' ? 'مباشر الآن' : ($sess->status === 'ended' ? 'انتهى' : 'قادم') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($sess->live_url)
                            <a href="{{ $sess->live_url }}" target="_blank" class="text-xs text-blue-500 hover:underline">انضم ←</a>
                            @else
                            <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>
                        @if(auth()->user()->role !== 'student')
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button @click="openEdit({{ $sess->id }}, '{{ addslashes($sess->title) }}', '{{ $sess->live_url }}', {{ $sess->batch_id }}, '{{ $sess->scheduled_at ?? '' }}', '{{ $sess->status }}')"
                                    class="p-2 hover:bg-yellow-50 rounded-lg transition-colors text-yellow-500" title="تعديل">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>
                                <form method="POST" action="{{ route('dashboard.live-sessions.destroy', $sess->id) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذه الجلسة؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 hover:bg-red-50 rounded-lg transition-colors text-red-500" title="حذف">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-12 text-gray-400">لا يوجد جلسات بث بعد</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add Modal --}}
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showAddModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-lg relative z-10 shadow-2xl">
            <h2 class="text-xl font-black text-navy mb-6">إضافة جلسة بث جديدة</h2>
            <form method="POST" action="{{ route('dashboard.live-sessions.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">عنوان الجلسة</label>
                    <input type="text" name="title" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">رابط البث</label>
                    <input type="text" name="live_url" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">المجموعة</label>
                    <select name="batch_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                        <option value="">-- اختر المجموعة --</option>
                        @foreach($batches as $batch)
                        <option value="{{ $batch->id }}">{{ $batch->name }} - {{ $batch->course->title ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
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
        <div class="bg-white rounded-2xl p-8 w-full max-w-lg relative z-10 shadow-2xl">
            <h2 class="text-xl font-black text-navy mb-6">تعديل جلسة البث</h2>
            <form method="POST" :action="'/dashboard/live-sessions/' + editItem.id" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">عنوان الجلسة</label>
                    <input type="text" name="title" x-model="editItem.title" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">رابط البث</label>
                    <input type="text" name="live_url" x-model="editItem.live_url" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">المجموعة</label>
                    <select name="batch_id" x-model="editItem.batch_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        @foreach($batches as $batch)
                        <option value="{{ $batch->id }}">{{ $batch->name }} - {{ $batch->course->title ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">الموعد</label>
                        <input type="datetime-local" name="scheduled_at" x-model="editItem.scheduled_at" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">الحالة</label>
                        <select name="status" x-model="editItem.status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="scheduled">قادم</option>
                            <option value="live">مباشر</option>
                            <option value="ended">انتهى</option>
                        </select>
                    </div>
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
function liveSessionsManager() {
    return {
        showAddModal: false,
        showEditModal: false,
        editItem: { id: null, title: '', live_url: '', batch_id: '', scheduled_at: '', status: 'scheduled' },
        openEdit(id, title, live_url, batch_id, scheduled_at, status) {
            this.editItem = { id, title, live_url, batch_id, scheduled_at, status };
            this.showEditModal = true;
        }
    };
}
</script>
@endpush
@endsection
