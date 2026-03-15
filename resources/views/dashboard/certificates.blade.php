@extends('layouts.dashboard')
@section('title', 'INSEP PRO - الشهادات')
@section('dashboard-content')
<h1 class="text-2xl font-black text-navy mb-6">الشهادات</h1>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($certificates as $cert)
    <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 card-hover">
        <div class="bg-gradient-to-l from-navy to-navy-light p-4 relative overflow-hidden">
            <div class="absolute inset-0 hero-pattern opacity-20"></div>
            <div class="relative z-10 flex items-center gap-3">
                <svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>
                <div class="text-white">
                    <h3 class="font-bold text-sm">{{ $cert->course->title ?? 'شهادة معتمدة' }}</h3>
                    <p class="text-white/60 text-xs" style="font-family:'Roboto',sans-serif">{{ $cert->serial_number ?? '-' }}</p>
                </div>
            </div>
        </div>
        <div class="p-4 space-y-2">
            @if($cert->student)<p class="text-sm"><span class="text-gray-400">المتدرب:</span> <span class="font-bold text-navy">{{ $cert->student->name }}</span></p>@endif
            <p class="text-sm"><span class="text-gray-400">تاريخ الإصدار:</span> <span class="text-navy">{{ $cert->issue_date ?? $cert->created_at?->format('Y-m-d') }}</span></p>
            @if($cert->grade)
            <p class="text-sm"><span class="text-gray-400">الدرجة:</span> <span class="font-bold text-navy">{{ $cert->grade }}</span></p>
            @endif
            <span class="inline-block px-3 py-1 rounded-lg text-xs font-bold {{ ($cert->status ?? 'active') === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ($cert->status ?? 'active') === 'active' ? 'سارية' : 'منتهية' }}</span>

            {{-- QR Code --}}
            @if($cert->serial_number)
            <div class="pt-3 border-t border-gray-50 flex flex-col items-center gap-2">
                <p class="text-xs text-gray-400 self-start">رمز التحقق (QR)</p>
                <canvas id="qr-{{ $cert->id }}" class="rounded-lg"></canvas>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="col-span-3 text-center py-12 text-gray-400">لا يوجد شهادات بعد</div>
    @endforelse
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var certs = @json($certificates->map(fn($c) => ['id' => $c->id, 'serial' => $c->serial_number]));
    certs.forEach(function (cert) {
        if (!cert.serial) return;
        var canvas = document.getElementById('qr-' + cert.id);
        if (!canvas) return;
        QRCode.toCanvas(canvas, cert.serial, { width: 100, margin: 1, color: { dark: '#1a2744', light: '#ffffff' } });
    });
});
</script>
@endpush
@endsection
