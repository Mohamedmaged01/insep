@extends('layouts.app')
@section('title', 'INSEP PRO - اتصل بنا')

@section('content')
{{-- Hero --}}
<section class="bg-gradient-to-br from-navy via-navy-light to-navy-dark py-20 relative overflow-hidden">
    <div class="absolute inset-0 hero-pattern opacity-30"></div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <span class="inline-block bg-white/10 text-white px-4 py-1.5 rounded-full text-sm font-bold mb-4 border border-white/20">نسعد بتواصلكم</span>
        <h1 class="text-4xl md:text-5xl font-black text-white mb-4">اتصل بنا</h1>
        <p class="text-white/70 text-lg max-w-2xl mx-auto">نحن هنا لمساعدتك. تواصل معنا لأي استفسار أو اقتراح</p>
    </div>
</section>

<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Contact Info --}}
            <div class="space-y-6">
                <div>
                    <h2 class="text-2xl font-black text-navy mb-6">معلومات التواصل</h2>
                    <p class="text-gray-600 mb-8">يمكنك التواصل معنا من خلال أي من الطرق التالية أو عبر النموذج المرفق</p>
                </div>
                @foreach([
                    ['title' => 'العنوان', 'info' => '١٣ الخليفة المأمون، روكسي، مصر الجديدة', 'sub' => 'محافظة القاهرة 11757، مصر', 'color' => 'bg-blue-50 text-blue-600'],
                    ['title' => 'الهاتف', 'info' => '+20 10 33330027', 'sub' => 'تواصل معنا عبر واتساب', 'color' => 'bg-green-50 text-green-600'],
                    ['title' => 'البريد الإلكتروني', 'info' => 'info@insep.net', 'sub' => 'support@insep.net', 'color' => 'bg-purple-50 text-purple-600'],
                    ['title' => 'ساعات العمل', 'info' => 'الأحد - الخميس: 9:00 ص - 5:00 م', 'sub' => 'الجمعة والسبت: إجازة', 'color' => 'bg-orange-50 text-orange-600'],
                ] as $item)
                <div class="flex items-start gap-4 bg-white rounded-2xl p-5 border border-gray-100 card-hover">
                    <div class="w-12 h-12 rounded-xl {{ $item['color'] }} flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-navy mb-1">{{ $item['title'] }}</h3>
                        <p class="text-gray-600 text-sm">{{ $item['info'] }}</p>
                        <p class="text-gray-400 text-sm">{{ $item['sub'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Contact Form --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                    <h2 class="text-2xl font-black text-navy mb-2">أرسل رسالة</h2>
                    <p class="text-gray-500 mb-8">املأ النموذج التالي وسنرد عليك في أقرب وقت ممكن</p>

                    @if(session('success'))
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 flex items-center gap-3 animate-slideDown">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-green-700 font-medium">{{ session('success') }}</span>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('contact.submit') }}" class="space-y-5">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="text-sm font-bold text-navy mb-2 block">الاسم الكامل *</label>
                                <input name="name" type="text" placeholder="أدخل اسمك" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5 transition-colors" required value="{{ old('name') }}">
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-sm font-bold text-navy mb-2 block">البريد الإلكتروني *</label>
                                <input name="email" type="email" placeholder="example@email.com" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5 transition-colors" dir="ltr" required value="{{ old('email') }}">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="text-sm font-bold text-navy mb-2 block">رقم الجوال</label>
                                <input name="phone" type="tel" placeholder="+966 5X XXX XXXX" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5 transition-colors" dir="ltr" value="{{ old('phone') }}">
                            </div>
                            <div>
                                <label class="text-sm font-bold text-navy mb-2 block">الموضوع *</label>
                                <select name="subject" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5 transition-colors text-gray-600" required>
                                    <option value="">اختر الموضوع</option>
                                    <option>استفسار عام</option>
                                    <option>استفسار عن الدورات</option>
                                    <option>دعم فني</option>
                                    <option>شراكات وتعاون</option>
                                    <option>شكوى أو اقتراح</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-bold text-navy mb-2 block">الرسالة *</label>
                            <textarea name="message" rows="5" placeholder="اكتب رسالتك هنا..." class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5 transition-colors resize-none" required>{{ old('message') }}</textarea>
                        </div>
                        <button type="submit" class="bg-navy hover:bg-navy-dark text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all duration-300 flex items-center gap-2 hover:shadow-lg hover:shadow-navy/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                            إرسال الرسالة
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Map --}}
        <div class="mt-12 rounded-2xl overflow-hidden border border-gray-200 shadow-sm" style="height:380px">
            <iframe
                src="https://maps.google.com/maps?q=13+El+Khalifa+El+Maamoun+Roxy+Heliopolis+Cairo+Egypt&output=embed&z=16"
                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</section>
@endsection
