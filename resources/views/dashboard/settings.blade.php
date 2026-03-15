@extends('layouts.dashboard')
@section('title', 'INSEP PRO - الإعدادات')
@section('dashboard-content')
@php $user = auth()->user(); @endphp

{{-- Flash --}}
@if(session('success'))
<div class="mb-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-green-700 text-sm font-bold">{{ session('success') }}</div>
@endif

<h1 class="text-2xl font-black text-navy mb-6">الإعدادات والملف الشخصي</h1>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Profile Card --}}
    <div class="bg-white rounded-2xl p-8 border border-gray-100 text-center">
        <div class="w-24 h-24 mx-auto mb-4 bg-gradient-to-br from-navy to-navy-light rounded-2xl flex items-center justify-center text-white font-black text-3xl shadow-xl">{{ mb_substr($user->name, 0, 1) }}</div>
        <h2 class="text-xl font-black text-navy">{{ $user->name }}</h2>
        <p class="text-sm text-gray-500 mt-1" style="font-family: 'Roboto', sans-serif">{{ $user->email }}</p>
        <p class="text-sm text-gray-500 mt-1" style="font-family: 'Roboto', sans-serif">{{ $user->phone ?? '-' }}</p>
        <span class="inline-block mt-3 px-4 py-1.5 rounded-xl text-xs font-bold bg-navy/10 text-navy">
            {{ $user->role === 'admin' ? 'مدير النظام' : ($user->role === 'student' ? 'طالب' : 'مدرب') }}
        </span>
        @if($user->specialty)
        <p class="text-xs text-gray-400 mt-2">{{ $user->specialty }}</p>
        @endif
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-xs text-gray-400">عضو منذ {{ \Carbon\Carbon::parse($user->created_at)->format('Y') }}</p>
        </div>
    </div>

    {{-- Edit Form --}}
    <div class="lg:col-span-2 bg-white rounded-2xl p-8 border border-gray-100">
        <h3 class="text-lg font-bold text-navy mb-6">تعديل المعلومات الشخصية</h3>
        <form method="POST" action="{{ route('dashboard.settings.update') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الاسم الكامل</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">البريد الإلكتروني</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5 focus:border-navy transition-colors" dir="ltr" required>
                </div>
            </div>
            <div>
                <label class="text-sm font-bold text-navy mb-2 block">رقم الجوال</label>
                <input type="tel" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5 focus:border-navy transition-colors" dir="ltr">
            </div>
            <div>
                <label class="text-sm font-bold text-navy mb-2 block">كلمة المرور الجديدة (اتركها فارغة إن لم ترد التغيير)</label>
                <input type="password" name="password" placeholder="كلمة المرور الجديدة" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5 focus:border-navy transition-colors" dir="ltr">
            </div>
            <button type="submit" class="bg-navy hover:bg-navy-dark text-white px-8 py-3.5 rounded-xl font-bold transition-all hover:shadow-xl">
                حفظ التغييرات
            </button>
        </form>
    </div>
</div>
@endsection
