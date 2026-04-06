@extends('layouts.dashboard')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
@section('title', $isAr ? 'إدارة محتوى الموقع' : 'Site Content Management')

@section('content')
<div class="p-6 max-w-5xl mx-auto" x-data="{ tab: 'stats' }">
    <div class="mb-8">
        <h1 class="text-2xl font-black text-navy">{{ $isAr ? 'إدارة محتوى الموقع' : 'Site Content Management' }}</h1>
        <p class="text-gray-500 text-sm mt-1">{{ $isAr ? 'تحكم في جميع محتويات الصفحات العامة من هنا' : 'Control all public page content from here' }}</p>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 flex items-center gap-3">
        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="text-green-700 font-medium">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Tabs --}}
    <div class="flex flex-wrap gap-2 mb-8 border-b border-gray-200 pb-4">
        @foreach([
            ['key' => 'stats',   'ar' => 'الإحصائيات',    'en' => 'Statistics'],
            ['key' => 'social',  'ar' => 'التواصل الاجتماعي', 'en' => 'Social Links'],
            ['key' => 'contact', 'ar' => 'بيانات التواصل', 'en' => 'Contact Info'],
            ['key' => 'about',   'ar' => 'من نحن',         'en' => 'About Us'],
            ['key' => 'legal',   'ar' => 'الخصوصية والشروط','en' => 'Privacy & Terms'],
        ] as $t)
        <button @click="tab = '{{ $t['key'] }}'"
            :class="tab === '{{ $t['key'] }}' ? 'bg-navy text-white shadow' : 'bg-white text-navy border border-gray-200 hover:border-navy'"
            class="px-4 py-2 rounded-xl text-sm font-bold transition-all">
            {{ $isAr ? $t['ar'] : $t['en'] }}
        </button>
        @endforeach
    </div>

    <form method="POST" action="{{ route('dashboard.cms.update') }}">
        @csrf

        {{-- Statistics --}}
        <div x-show="tab === 'stats'" x-cloak>
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-navy mb-6">{{ $isAr ? 'إحصائيات الموقع' : 'Site Statistics' }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach([
                        ['key' => 'stat_students',     'ar' => 'عدد الطلاب المسجلين',   'en' => 'Registered Students'],
                        ['key' => 'stat_trainers',     'ar' => 'عدد المدربين المعتمدين', 'en' => 'Certified Trainers'],
                        ['key' => 'stat_courses',      'ar' => 'عدد الدورات التدريبية',  'en' => 'Training Courses'],
                        ['key' => 'stat_certificates', 'ar' => 'عدد الشهادات الصادرة',   'en' => 'Certificates Issued'],
                    ] as $field)
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? $field['ar'] : $field['en'] }}</label>
                        <input type="number" name="{{ $field['key'] }}" value="{{ $settings[$field['key']] ?? '' }}"
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Social Links --}}
        <div x-show="tab === 'social'" x-cloak>
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-navy mb-6">{{ $isAr ? 'روابط التواصل الاجتماعي' : 'Social Media Links' }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach([
                        ['key' => 'social_facebook',  'label' => 'Facebook'],
                        ['key' => 'social_instagram',  'label' => 'Instagram'],
                        ['key' => 'social_youtube',    'label' => 'YouTube'],
                        ['key' => 'social_linkedin',   'label' => 'LinkedIn'],
                        ['key' => 'social_twitter',    'label' => 'Twitter / X'],
                        ['key' => 'social_telegram',   'label' => 'Telegram'],
                    ] as $field)
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $field['label'] }}</label>
                        <input type="url" name="{{ $field['key'] }}" value="{{ $settings[$field['key']] ?? '' }}"
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr" placeholder="https://">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Contact --}}
        <div x-show="tab === 'contact'" x-cloak>
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-navy mb-6">{{ $isAr ? 'بيانات التواصل' : 'Contact Information' }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'رقم الهاتف 1' : 'Phone 1' }}</label>
                        <input type="text" name="contact_phone1" value="{{ $settings['contact_phone1'] ?? '' }}"
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'رقم الهاتف 2' : 'Phone 2' }}</label>
                        <input type="text" name="contact_phone2" value="{{ $settings['contact_phone2'] ?? '' }}"
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'رقم الهاتف 3' : 'Phone 3' }}</label>
                        <input type="text" name="contact_phone3" value="{{ $settings['contact_phone3'] ?? '' }}"
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'البريد الإلكتروني' : 'Email' }}</label>
                        <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? '' }}"
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'العنوان (عربي)' : 'Address (Arabic)' }}</label>
                        <input type="text" name="contact_address_ar" value="{{ $settings['contact_address_ar'] ?? '' }}"
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'العنوان (إنجليزي)' : 'Address (English)' }}</label>
                        <input type="text" name="contact_address_en" value="{{ $settings['contact_address_en'] ?? '' }}"
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                </div>
            </div>
        </div>

        {{-- About --}}
        <div x-show="tab === 'about'" x-cloak>
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-navy mb-6">{{ $isAr ? 'نص من نحن' : 'About Us Text' }}</h2>
                <div class="space-y-5">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'النص بالعربية' : 'Text in Arabic' }}</label>
                        <textarea name="about_ar" rows="6"
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors resize-none">{{ $settings['about_ar'] ?? '' }}</textarea>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'النص بالإنجليزية' : 'Text in English' }}</label>
                        <textarea name="about_en" rows="6" dir="ltr"
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors resize-none">{{ $settings['about_en'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Privacy & Terms --}}
        <div x-show="tab === 'legal'" x-cloak>
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-8">
                <div>
                    <h2 class="text-lg font-bold text-navy mb-6">{{ $isAr ? 'سياسة الخصوصية' : 'Privacy Policy' }}</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'بالعربية' : 'Arabic' }}</label>
                            <textarea name="privacy_ar" rows="5"
                                class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors resize-none">{{ $settings['privacy_ar'] ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'بالإنجليزية' : 'English' }}</label>
                            <textarea name="privacy_en" rows="5" dir="ltr"
                                class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors resize-none">{{ $settings['privacy_en'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-navy mb-6">{{ $isAr ? 'الشروط والأحكام' : 'Terms & Conditions' }}</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'بالعربية' : 'Arabic' }}</label>
                            <textarea name="terms_ar" rows="5"
                                class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors resize-none">{{ $settings['terms_ar'] ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'بالإنجليزية' : 'English' }}</label>
                            <textarea name="terms_en" rows="5" dir="ltr"
                                class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors resize-none">{{ $settings['terms_en'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="bg-navy hover:bg-navy-dark text-white px-8 py-3 rounded-xl font-bold transition-all duration-300 hover:shadow-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                {{ $isAr ? 'حفظ الإعدادات' : 'Save Settings' }}
            </button>
        </div>
    </form>
</div>
@endsection
