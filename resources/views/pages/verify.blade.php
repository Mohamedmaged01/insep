@extends('layouts.app')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
@section('title', 'INSEP PRO - ' . ($isAr ? 'التحقق من الشهادات' : 'Certificate Verification'))

@section('content')
{{-- Hero --}}
<section class="bg-gradient-to-br from-navy via-navy-light to-navy-dark py-20 relative overflow-hidden">
    <div class="absolute inset-0 hero-pattern opacity-30"></div>
    <div class="container mx-auto px-4 relative z-10 text-center" x-data="verifyApp()">
        <div class="w-20 h-20 mx-auto mb-6 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/20 animate-float">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <h1 class="text-4xl md:text-5xl font-black text-white mb-4">{{ $isAr ? 'التحقق من الشهادات' : 'Certificate Verification' }}</h1>
        <p class="text-white/70 text-lg max-w-2xl mx-auto mb-8">{{ $isAr ? 'تحقق من صحة أي شهادة صادرة من معهد INSEP باستخدام الرقم التسلسلي أو رمز QR' : 'Verify the authenticity of any certificate issued by INSEP Institute using the serial number or QR code' }}</p>

        <form @submit.prevent="search()" class="max-w-xl mx-auto">
            <div class="relative">
                <svg class="w-5 h-5 absolute right-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
                <input type="text" x-model="query" placeholder="{{ $isAr ? 'أدخل الرقم التسلسلي مثل: INSEP-2025-001234' : 'Enter the serial number e.g. INSEP-2025-001234' }}" class="w-full bg-white rounded-xl pr-12 pl-32 py-4 text-lg shadow-xl" dir="ltr">
                <button type="submit" :disabled="searching" class="absolute left-2 top-1/2 -translate-y-1/2 bg-navy hover:bg-navy-dark text-white px-6 py-2.5 rounded-lg font-bold transition-all disabled:opacity-50">
                    <span x-show="!searching">{{ $isAr ? 'تحقق' : 'Verify' }}</span>
                    <span x-show="searching" x-cloak>{{ $isAr ? 'جاري البحث...' : 'Searching...' }}</span>
                </button>
            </div>
        </form>
        <p class="text-white/50 text-sm mt-4">{{ $isAr ? 'جرب: INSEP-2025-001234' : 'Try: INSEP-2025-001234' }}</p>
    </div>
</section>

{{-- Results --}}
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4 max-w-3xl" x-data="verifyApp()">
        {{-- Found --}}
        <template x-if="result === 'found'">
            <div class="animate-fadeInUp">
                <div class="bg-green-50 border border-green-200 rounded-2xl p-6 mb-8 flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-green-800">{{ $isAr ? 'شهادة موثقة ✓' : 'Verified Certificate ✓' }}</h3>
                        <p class="text-green-600 text-sm">{{ $isAr ? 'تم التحقق من صحة الشهادة بنجاح' : 'Certificate authenticity verified successfully' }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
                    <div class="bg-gradient-to-l from-navy to-navy-light p-6 relative overflow-hidden">
                        <div class="absolute inset-0 hero-pattern opacity-20"></div>
                        <div class="relative z-10 flex items-center gap-4">
                            <div class="w-16 h-16 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/20">
                                <svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>
                            </div>
                            <div class="text-white">
                                <h2 class="text-xl font-black" x-text="certData?.courseName || '{{ $isAr ? 'شهادة معتمدة' : 'Accredited Certificate' }}'"></h2>
                                <p class="text-white/70 text-sm">{{ $isAr ? 'شهادة إتمام البرنامج التدريبي' : 'Training Program Completion Certificate' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <template x-for="detail in [
                                {label: '{{ $isAr ? 'الرقم التسلسلي' : 'Serial Number' }}',    value: certData?.serialNumber},
                                {label: '{{ $isAr ? 'اسم المتدرب' : 'Trainee Name' }}',        value: certData?.studentName},
                                {label: '{{ $isAr ? 'البرنامج التدريبي' : 'Training Program' }}', value: certData?.courseName},
                                {label: '{{ $isAr ? 'تاريخ الإصدار' : 'Issue Date' }}',         value: certData?.issueDate},
                                {label: '{{ $isAr ? 'الدرجة' : 'Grade' }}',                      value: certData?.grade},
                                {label: '{{ $isAr ? 'حالة الشهادة' : 'Certificate Status' }}',   value: certData?.status === 'active' ? '{{ $isAr ? 'سارية المفعول' : 'Active' }}' : '{{ $isAr ? 'غير سارية' : 'Inactive' }}'},
                            ]">
                                <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl">
                                    <div>
                                        <p class="text-xs text-gray-400 font-medium mb-1" x-text="detail.label"></p>
                                        <p class="font-bold text-navy" x-text="detail.value || '-'"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        {{-- Not Found --}}
        <template x-if="result === 'not-found'">
            <div class="animate-fadeInUp text-center py-12">
                <div class="w-20 h-20 mx-auto mb-6 bg-red-50 rounded-2xl flex items-center justify-center">
                    <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                </div>
                <h3 class="text-2xl font-bold text-red-600 mb-3">{{ $isAr ? 'لم يتم العثور على الشهادة' : 'Certificate Not Found' }}</h3>
                <p class="text-gray-500 max-w-md mx-auto">{{ $isAr ? 'الرقم التسلسلي المدخل غير موجود في نظامنا. تأكد من صحة الرقم وحاول مرة أخرى.' : 'The entered serial number was not found in our system. Please verify the number and try again.' }}</p>
            </div>
        </template>

        {{-- Empty State --}}
        <template x-if="!result && !searching">
            <div class="text-center py-12">
                <div class="w-20 h-20 mx-auto mb-6 bg-navy/5 rounded-2xl flex items-center justify-center">
                    <svg class="w-10 h-10 text-navy/30" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-400 mb-3">{{ $isAr ? 'ابحث عن شهادة' : 'Search for a Certificate' }}</h3>
                <p class="text-gray-400 max-w-md mx-auto">{{ $isAr ? 'أدخل الرقم التسلسلي للشهادة أو امسح رمز QR الموجود على الشهادة للتحقق من صحتها' : 'Enter the certificate serial number or scan the QR code on the certificate to verify its authenticity' }}</p>
                <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6 text-{{ $isAr ? 'right' : 'left' }}">
                    @foreach([
                        [
                            'step' => '1',
                            'title_ar' => 'أدخل الرقم التسلسلي', 'title_en' => 'Enter the Serial Number',
                            'desc_ar'  => 'أدخل الرقم المطبوع على الشهادة في حقل البحث أعلاه',
                            'desc_en'  => 'Enter the number printed on the certificate in the search field above',
                        ],
                        [
                            'step' => '2',
                            'title_ar' => 'اضغط تحقق',           'title_en' => 'Click Verify',
                            'desc_ar'  => 'سيقوم النظام بالبحث في قاعدة البيانات والتحقق من الشهادة',
                            'desc_en'  => 'The system will search the database and verify the certificate',
                        ],
                        [
                            'step' => '3',
                            'title_ar' => 'عرض النتيجة',          'title_en' => 'View the Result',
                            'desc_ar'  => 'ستظهر لك بيانات الشهادة كاملة في حالة وجودها في النظام',
                            'desc_en'  => 'Full certificate details will be displayed if found in the system',
                        ],
                    ] as $s)
                    <div class="bg-white rounded-2xl p-6 border border-gray-100">
                        <div class="w-10 h-10 rounded-xl bg-navy text-white flex items-center justify-center font-bold mb-4" style="font-family: 'Roboto', sans-serif">{{ $s['step'] }}</div>
                        <h4 class="font-bold text-navy mb-2">{{ $isAr ? $s['title_ar'] : $s['title_en'] }}</h4>
                        <p class="text-gray-500 text-sm">{{ $isAr ? $s['desc_ar'] : $s['desc_en'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </template>
    </div>
</section>

@push('scripts')
<script>
function verifyApp() {
    return {
        query: '',
        result: null,
        searching: false,
        certData: null,
        async search() {
            if (!this.query.trim()) return;
            this.searching = true;
            this.result = null;
            try {
                const res = await fetch('/api/certificates/verify/' + encodeURIComponent(this.query.trim()));
                const data = await res.json();
                if (data.found) {
                    this.result = 'found';
                    this.certData = data.certificate;
                } else {
                    this.result = 'not-found';
                }
            } catch {
                this.result = 'not-found';
            }
            this.searching = false;
        }
    };
}
</script>
@endpush
@endsection
