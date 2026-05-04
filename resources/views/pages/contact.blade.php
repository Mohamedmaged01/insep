@extends('layouts.app')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
@section('title', 'INSEP PRO - ' . ($isAr ? 'اتصل بنا' : 'Contact Us'))

@section('content')
{{-- Hero --}}
<section class="bg-gradient-to-br from-navy via-navy-light to-navy-dark py-20 relative overflow-hidden">
    <div class="absolute inset-0 hero-pattern opacity-30"></div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <span class="inline-block bg-white/10 text-white px-4 py-1.5 rounded-full text-sm font-bold mb-4 border border-white/20">{{ $isAr ? 'نسعد بتواصلكم' : 'We\'d Love to Hear From You' }}</span>
        <h1 class="text-4xl md:text-5xl font-black text-white mb-4">{{ $isAr ? 'اتصل بنا' : 'Contact Us' }}</h1>
        <p class="text-white/70 text-lg max-w-2xl mx-auto">{{ $isAr ? 'نحن هنا لمساعدتك. تواصل معنا لأي استفسار أو اقتراح' : 'We are here to help. Reach out to us for any inquiry or suggestion' }}</p>
    </div>
</section>

<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Contact Info --}}
            <div class="space-y-6">
                <div>
                    <h2 class="text-2xl font-black text-navy mb-6">{{ $isAr ? 'معلومات التواصل' : 'Contact Information' }}</h2>
                    <p class="text-gray-600 mb-8">{{ $isAr ? 'يمكنك التواصل معنا من خلال أي من الطرق التالية أو عبر النموذج المرفق' : 'You can reach us through any of the following methods or via the contact form' }}</p>
                </div>
                @php
                    $phone1   = $settings['contact_phone1']    ?? '+20 10 33330027';
                    $phone2   = $settings['contact_phone2']    ?? '';
                    $phone3   = $settings['contact_phone3']    ?? '';
                    $email    = $settings['contact_email']     ?? 'info@insep.net';
                    $addrAr   = $settings['contact_address_ar'] ?? '١٣ الخليفة المأمون، روكسي، مصر الجديدة';
                    $addrEn   = $settings['contact_address_en'] ?? '13 El-Khalifa El-Maamoun, Roxy, Heliopolis';
                    $phones   = array_filter([$phone1, $phone2, $phone3]);
                @endphp
                <div class="flex items-start gap-4 bg-white rounded-2xl p-5 border border-gray-100 card-hover">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-navy mb-1">{{ $isAr ? 'العنوان' : 'Address' }}</h3>
                        <p class="text-gray-600 text-sm">{{ $isAr ? $addrAr : $addrEn }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 bg-white rounded-2xl p-5 border border-gray-100 card-hover">
                    <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-navy mb-1">{{ $isAr ? 'الهاتف' : 'Phone' }}</h3>
                        @foreach($phones as $ph)
                        <p class="text-gray-600 text-sm">{{ $ph }}</p>
                        @endforeach
                    </div>
                </div>
                <div class="flex items-start gap-4 bg-white rounded-2xl p-5 border border-gray-100 card-hover">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-navy mb-1">{{ $isAr ? 'البريد الإلكتروني' : 'Email' }}</h3>
                        <p class="text-gray-600 text-sm">{{ $email }}</p>
                    </div>
                </div>
                <a href="https://wa.me/966555698722" target="_blank" rel="noopener"
                   class="flex items-start gap-4 bg-white rounded-2xl p-5 border border-gray-100 card-hover transition hover:border-green-200">
                    <div class="w-12 h-12 rounded-xl bg-green-50 text-green-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.52 3.48A11.93 11.93 0 0 0 12 0C5.37 0 0 5.37 0 12a11.93 11.93 0 0 0 1.64 6.08L0 24l6.08-1.6A12 12 0 0 0 12 24c6.63 0 12-5.37 12-12a11.93 11.93 0 0 0-3.48-8.52ZM12 21.94a9.94 9.94 0 0 1-5.06-1.38l-.36-.22-3.73.98.99-3.63-.24-.38A9.94 9.94 0 0 1 2.06 12C2.06 6.48 6.48 2.06 12 2.06S21.94 6.48 21.94 12 17.52 21.94 12 21.94Zm5.44-7.45c-.3-.15-1.77-.87-2.04-.97s-.47-.15-.67.15-.77.97-.94 1.17-.35.22-.65.07a8.14 8.14 0 0 1-2.39-1.48 9 9 0 0 1-1.65-2.06c-.17-.3 0-.46.13-.61s.3-.35.45-.52a2 2 0 0 0 .3-.5.55.55 0 0 0-.02-.52c-.07-.15-.67-1.62-.92-2.22s-.49-.5-.67-.51h-.57a1.1 1.1 0 0 0-.8.37 3.36 3.36 0 0 0-1.05 2.5 5.84 5.84 0 0 0 1.22 3.1c.15.2 2.1 3.2 5.08 4.49a17.2 17.2 0 0 0 1.7.63 4.08 4.08 0 0 0 1.87.12c.57-.09 1.77-.72 2.02-1.42s.25-1.3.17-1.42-.27-.2-.57-.35Z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-navy mb-1">{{ $isAr ? 'واتساب' : 'WhatsApp' }}</h3>
                        <p class="text-gray-600 text-sm" dir="ltr">+966 55 569 8722</p>
                        <p class="text-green-600 text-xs font-semibold mt-1">{{ $isAr ? 'راسلنا الآن ←' : 'Message us now →' }}</p>
                    </div>
                </a>
                <div class="flex items-start gap-4 bg-white rounded-2xl p-5 border border-gray-100 card-hover">
                    <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-navy mb-1">{{ $isAr ? 'ساعات العمل' : 'Working Hours' }}</h3>
                        <p class="text-gray-600 text-sm">{{ $isAr ? 'الأحد - الخميس: 9:00 ص - 5:00 م' : 'Sunday – Thursday: 9:00 AM – 5:00 PM' }}</p>
                        <p class="text-gray-400 text-sm">{{ $isAr ? 'الجمعة والسبت: إجازة' : 'Friday & Saturday: Closed' }}</p>
                    </div>
                </div>
            </div>

            {{-- Contact Form --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                    <h2 class="text-2xl font-black text-navy mb-2">{{ $isAr ? 'أرسل رسالة' : 'Send a Message' }}</h2>
                    <p class="text-gray-500 mb-8">{{ $isAr ? 'املأ النموذج التالي وسنرد عليك في أقرب وقت ممكن' : 'Fill in the form below and we will get back to you as soon as possible' }}</p>

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
                                <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الاسم الكامل *' : 'Full Name *' }}</label>
                                <input name="name" type="text" placeholder="{{ $isAr ? 'أدخل اسمك' : 'Enter your name' }}" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5 transition-colors" required value="{{ old('name') }}">
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'البريد الإلكتروني *' : 'Email Address *' }}</label>
                                <input name="email" type="email" placeholder="example@email.com" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5 transition-colors" dir="ltr" required value="{{ old('email') }}">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'رقم الجوال' : 'Phone Number' }}</label>
                                <div class="flex gap-2">
                                    <select name="phone_code" class="border-2 border-gray-200 rounded-xl px-2 py-3.5 focus:border-navy transition-colors text-sm font-bold text-gray-700 flex-shrink-0" dir="ltr">
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
                                    <input name="phone" type="tel" placeholder="1234567890" class="flex-1 border-2 border-gray-200 rounded-xl px-4 py-3.5 transition-colors" dir="ltr" value="{{ old('phone') }}">
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الموضوع *' : 'Subject *' }}</label>
                                <select name="subject" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5 transition-colors text-gray-600" required>
                                    <option value="">{{ $isAr ? 'اختر الموضوع' : 'Select a subject' }}</option>
                                    <option>{{ $isAr ? 'استفسار عام' : 'General Inquiry' }}</option>
                                    <option>{{ $isAr ? 'استفسار عن الدورات' : 'Course Inquiry' }}</option>
                                    <option>{{ $isAr ? 'دعم فني' : 'Technical Support' }}</option>
                                    <option>{{ $isAr ? 'شراكات وتعاون' : 'Partnership & Collaboration' }}</option>
                                    <option>{{ $isAr ? 'شكوى أو اقتراح' : 'Complaint or Suggestion' }}</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الرسالة *' : 'Message *' }}</label>
                            <textarea name="message" rows="5" placeholder="{{ $isAr ? 'اكتب رسالتك هنا...' : 'Write your message here...' }}" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5 transition-colors resize-none" required>{{ old('message') }}</textarea>
                        </div>
                        <button type="submit" class="bg-navy hover:bg-navy-dark text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all duration-300 flex items-center gap-2 hover:shadow-lg hover:shadow-navy/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                            {{ $isAr ? 'إرسال الرسالة' : 'Send Message' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Maps --}}
        <div class="mt-12 grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Location 1: Heliopolis --}}
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-navy" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <h3 class="font-bold text-navy text-sm">{{ $isAr ? '١٣ الخليفة المأمون، روكسي، مصر الجديدة' : '13 El-Khalifa El-Maamoun, Roxy, Heliopolis' }}</h3>
                </div>
                <div class="rounded-2xl overflow-hidden border border-gray-200 shadow-sm" style="height:320px">
                    <iframe
                        src="https://maps.google.com/maps?q=13+El+Khalifa+El+Maamoun+Roxy+Heliopolis+Cairo+Egypt&output=embed&z=16"
                        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

            {{-- Location 2: shared pin --}}
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-navy" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <h3 class="font-bold text-navy text-sm">{{ $isAr ? 'المملكة العربية السعودية' : 'Saudi Arabia (KSA)' }}</h3>
                </div>
                <div class="rounded-2xl overflow-hidden border border-gray-200 shadow-sm" style="height:320px">
                    <iframe
                        src="https://maps.google.com/maps?q=30.0902680,31.3112296&output=embed&z=17"
                        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <div class="mt-3 flex justify-end">
                    <a href="https://maps.app.goo.gl/DcpKg6Pydyt2Vvg28" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-navy text-white text-xs font-semibold hover:bg-navy/90 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $isAr ? 'فتح في خرائط جوجل' : 'Open in Google Maps' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
