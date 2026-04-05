@extends('layouts.dashboard')
@section('title', 'INSEP PRO')
@section('dashboard-content')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
<div x-data="gamificationManager()">

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-green-700 text-sm font-bold">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-navy">{{ $isAr ? 'النقاط والشارات' : 'Points & Badges' }}</h1>
            <p class="text-gray-500 text-sm">{{ $isAr ? 'نظام المكافآت والتحفيز' : 'Rewards & Motivation System' }}</p>
        </div>
        @if(auth()->user()->role !== 'student')
        <div class="flex gap-2">
            <button @click="showPointsModal = true" class="bg-navy hover:bg-navy-dark text-white px-4 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                {{ $isAr ? 'منح نقاط' : 'Grant Points' }}
            </button>
            <button @click="showBadgeModal = true" class="bg-red-brand hover:bg-red-brand-dark text-white px-4 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>
                {{ $isAr ? 'إضافة شارة' : 'Add Badge' }}
            </button>
        </div>
        @endif
    </div>

    {{-- My Stats (student only) --}}
    @if(auth()->user()->role === 'student')
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
        <div class="bg-gradient-to-br from-navy to-navy-light rounded-2xl p-6 text-white">
            <p class="text-white/70 text-sm mb-1">{{ $isAr ? 'نقاطي الإجمالية' : 'My Total Points' }}</p>
            <p class="text-4xl font-black" style="font-family:'Roboto',sans-serif">{{ $myPoints }}</p>
            <p class="text-white/50 text-xs mt-2">{{ $isAr ? 'استمر في التعلم لكسب المزيد من النقاط' : 'Keep learning to earn more points' }}</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-gray-100">
            <p class="text-gray-500 text-sm mb-3">{{ $isAr ? 'شاراتي المحققة' : 'My Earned Badges' }}</p>
            @if($myBadges->count() > 0)
            <div class="flex flex-wrap gap-2">
                @foreach($myBadges as $badge)
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-yellow-50 border border-yellow-200 rounded-xl text-sm font-bold text-yellow-700">
                    <span>{{ $badge->icon }}</span> {{ $badge->name_ar }}
                </span>
                @endforeach
            </div>
            @else
            <p class="text-gray-400 text-sm">{{ $isAr ? 'لم تحصل على شارات بعد — استمر في تحقيق الإنجازات!' : "You haven't earned any badges yet — keep achieving!" }}</p>
            @endif
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Leaderboard --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                <h3 class="font-bold text-navy">{{ $isAr ? 'لوحة المتصدرين' : 'Leaderboard' }}</h3>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($leaderboard as $i => $student)
                <div class="flex items-center gap-4 px-6 py-3 {{ auth()->id() === $student->id ? 'bg-navy/5' : '' }}">
                    <div class="w-8 text-center font-black text-lg {{ $i === 0 ? 'text-yellow-500' : ($i === 1 ? 'text-gray-400' : ($i === 2 ? 'text-amber-600' : 'text-gray-300')) }}">
                        {{ $i === 0 ? '🥇' : ($i === 1 ? '🥈' : ($i === 2 ? '🥉' : ($i + 1))) }}
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-navy to-navy-light flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                        {{ mb_substr($student->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-navy text-sm truncate">{{ $student->name }}</p>
                        <p class="text-xs text-gray-400 truncate" style="font-family:'Roboto',sans-serif">{{ $student->email }}</p>
                    </div>
                    <div class="text-left flex-shrink-0">
                        <p class="font-black text-navy text-lg" style="font-family:'Roboto',sans-serif">{{ number_format($student->total_points ?? 0) }}</p>
                        <p class="text-xs text-gray-400">{{ $isAr ? 'نقطة' : 'points' }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-12 text-gray-400">{{ $isAr ? 'لا يوجد نقاط مسجلة بعد' : 'No points recorded yet' }}</div>
                @endforelse
            </div>
        </div>

        {{-- Badges --}}
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-navy">{{ $isAr ? 'الشارات المتاحة' : 'Available Badges' }}</h3>
                <span class="text-xs text-gray-400">{{ $badges->count() }} {{ $isAr ? 'شارة' : 'badges' }}</span>
            </div>
            <div class="p-4 space-y-3">
                @forelse($badges as $badge)
                <div class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:border-navy/20 transition-colors">
                    <div class="text-2xl w-10 text-center">{{ $badge->icon }}</div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-navy text-sm">{{ $badge->name_ar }}</p>
                        <p class="text-xs text-gray-400">{{ $badge->min_points }} {{ $isAr ? 'نقطة للحصول عليها' : 'points to earn' }}</p>
                        @if($badge->description)
                        <p class="text-xs text-gray-500 mt-0.5">{{ $badge->description }}</p>
                        @endif
                    </div>
                    <div class="text-xs text-gray-400 flex-shrink-0">{{ $badge->users_count }} {{ $isAr ? 'طالب' : 'students' }}</div>
                    @if(auth()->user()->role === 'admin')
                    <form method="POST" action="{{ route('dashboard.gamification.badges.destroy', $badge->id) }}" onsubmit="return confirm('{{ $isAr ? 'حذف الشارة؟' : 'Delete this badge?' }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-1 text-red-400 hover:text-red-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                        </button>
                    </form>
                    @endif
                </div>
                @empty
                <p class="text-center py-8 text-gray-400 text-sm">{{ $isAr ? 'لا يوجد شارات بعد' : 'No badges found yet' }}</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Award Points Modal --}}
    @if(auth()->user()->role !== 'student')
    <div x-show="showPointsModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showPointsModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-md relative z-10 shadow-2xl">
            <h2 class="text-xl font-black text-navy mb-6">{{ $isAr ? 'منح نقاط لطالب' : 'Grant Points to Student' }}</h2>
            <form method="POST" action="{{ route('dashboard.gamification.points') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الطالب' : 'Student' }}</label>
                    <select name="student_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                        <option value="">{{ $isAr ? '-- اختر الطالب --' : '-- Select Student --' }}</option>
                        @foreach($students as $st)
                        <option value="{{ $st->id }}">{{ $st->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'عدد النقاط' : 'Points Amount' }}</label>
                    <input type="number" name="amount" min="1" value="10" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'السبب' : 'Reason' }}</label>
                    <input type="text" name="reason" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" placeholder="{{ $isAr ? 'مثال: إتمام الدورة، حضور ممتاز...' : 'e.g. Course completion, excellent attendance...' }}">
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showPointsModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200 transition-colors">{{ $isAr ? 'إلغاء' : 'Cancel' }}</button>
                    <button type="submit" class="flex-1 bg-navy hover:bg-navy-dark text-white py-3 rounded-xl font-bold transition-colors">{{ $isAr ? 'منح النقاط' : 'Grant Points' }}</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Add Badge Modal --}}
    <div x-show="showBadgeModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showBadgeModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-md relative z-10 shadow-2xl">
            <h2 class="text-xl font-black text-navy mb-6">{{ $isAr ? 'إضافة شارة جديدة' : 'Add New Badge' }}</h2>
            <form method="POST" action="{{ route('dashboard.gamification.badges') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'اسم الشارة (عربي)' : 'Badge Name (Arabic)' }}</label>
                    <input type="text" name="badge_name_ar" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'اسم الشارة (إنجليزي)' : 'Badge Name (English)' }}</label>
                    <input type="text" name="badge_name_en" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الأيقونة (إيموجي)' : 'Icon (Emoji)' }}</label>
                        <input type="text" name="icon" value="⭐" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors text-center text-2xl">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الحد الأدنى للنقاط' : 'Minimum Points' }}</label>
                        <input type="number" name="min_points" value="100" min="0" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الوصف (اختياري)' : 'Description (optional)' }}</label>
                    <input type="text" name="description" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showBadgeModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200 transition-colors">{{ $isAr ? 'إلغاء' : 'Cancel' }}</button>
                    <button type="submit" class="flex-1 bg-red-brand hover:bg-red-brand-dark text-white py-3 rounded-xl font-bold transition-colors">{{ $isAr ? 'إضافة الشارة' : 'Add Badge' }}</button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
@push('scripts')
<script>
function gamificationManager() {
    return {
        showPointsModal: false,
        showBadgeModal: false,
    };
}
</script>
@endpush
@endsection
