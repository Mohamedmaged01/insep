@extends('layouts.dashboard')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
@section('title', $isAr ? 'إدارة المستخدمين' : 'User Management')

@section('dashboard-content')
<div class="p-6" x-data="{ showAdd: false, editUser: null, deleteId: null }">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-black text-navy">{{ $isAr ? 'إدارة المستخدمين' : 'User Management' }}</h1>
            <p class="text-gray-500 text-sm mt-1">{{ $isAr ? 'إدارة جميع المستخدمين وصلاحياتهم' : 'Manage all users and their roles' }}</p>
        </div>
        <button @click="showAdd = true"
            class="bg-navy hover:bg-navy-dark text-white px-5 py-2.5 rounded-xl font-bold transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            {{ $isAr ? 'إضافة مستخدم' : 'Add User' }}
        </button>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-5 flex items-center gap-3">
        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="text-green-700 font-medium">{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-5 flex items-center gap-3">
        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <span class="text-red-700 font-medium">{{ session('error') }}</span>
    </div>
    @endif

    {{-- Role filter tabs --}}
    @php
        $roleBadges = [
            'admin'      => ['label' => 'مدير إداري',  'color' => 'bg-red-100 text-red-700'],
            'instructor' => ['label' => 'مدرب',         'color' => 'bg-blue-100 text-blue-700'],
            'student'    => ['label' => 'متدرب',        'color' => 'bg-green-100 text-green-700'],
            'finance'    => ['label' => 'محاسب',        'color' => 'bg-purple-100 text-purple-700'],
            'support'    => ['label' => 'دعم فني',      'color' => 'bg-orange-100 text-orange-700'],
        ];
    @endphp

    {{-- Stats bar --}}
    <div class="grid grid-cols-5 gap-3 mb-6">
        @foreach($roleBadges as $roleKey => $info)
        <div class="bg-white rounded-xl p-4 border border-gray-100 text-center shadow-sm">
            <div class="text-2xl font-black text-navy">{{ $users->where('role', $roleKey)->count() }}</div>
            <div class="text-xs font-medium text-gray-500 mt-1">{{ $info['label'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-right px-5 py-3.5 font-bold text-navy text-xs">#</th>
                        <th class="text-right px-5 py-3.5 font-bold text-navy text-xs">{{ $isAr ? 'الاسم' : 'Name' }}</th>
                        <th class="text-right px-5 py-3.5 font-bold text-navy text-xs">{{ $isAr ? 'البريد' : 'Email' }}</th>
                        <th class="text-right px-5 py-3.5 font-bold text-navy text-xs">{{ $isAr ? 'الدور' : 'Role' }}</th>
                        <th class="text-right px-5 py-3.5 font-bold text-navy text-xs">{{ $isAr ? 'الحالة' : 'Status' }}</th>
                        <th class="text-right px-5 py-3.5 font-bold text-navy text-xs">{{ $isAr ? 'تاريخ الإنشاء' : 'Created' }}</th>
                        <th class="text-right px-5 py-3.5 font-bold text-navy text-xs">{{ $isAr ? 'إجراءات' : 'Actions' }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $u)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3.5 text-gray-400 font-mono text-xs">{{ $u->id }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-navy/10 flex items-center justify-center text-navy font-bold text-sm flex-shrink-0">
                                    {{ mb_substr($u->name ?? '?', 0, 1) }}
                                </div>
                                <span class="font-semibold text-navy">{{ $u->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-gray-500" dir="ltr">{{ $u->email }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $roleBadges[$u->role]['color'] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $roleBadges[$u->role]['label'] ?? $u->role }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $u->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $u->status === 'active' ? ($isAr ? 'نشط' : 'Active') : ($isAr ? 'معطل' : 'Inactive') }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-400 text-xs" dir="ltr">{{ $u->created_at?->format('Y-m-d') }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2">
                                <button @click="editUser = {{ $u->toJson() }}"
                                    class="p-1.5 hover:bg-navy/10 rounded-lg transition-colors text-navy">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                @if($u->id !== auth()->id())
                                <form method="POST" action="{{ route('dashboard.users.destroy', $u->id) }}"
                                    onsubmit="return confirm('{{ $isAr ? 'هل أنت متأكد من حذف هذا المستخدم؟' : 'Are you sure?' }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 hover:bg-red-100 rounded-lg transition-colors text-red-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path stroke-linecap="round" stroke-linejoin="round" d="M19 6l-1 14H6L5 6m5 0V4h4v2"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400">{{ $isAr ? 'لا يوجد مستخدمون' : 'No users found' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add Modal --}}
    <div x-show="showAdd" x-cloak class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" @click.self="showAdd = false">
        <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-black text-navy text-lg">{{ $isAr ? 'إضافة مستخدم جديد' : 'Add New User' }}</h3>
                <button @click="showAdd = false" class="text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form method="POST" action="{{ route('dashboard.users.store') }}" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-1.5 block">{{ $isAr ? 'الاسم (عربي)' : 'Name (Arabic)' }}</label>
                        <input type="text" name="name_ar" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:border-navy transition-colors text-sm">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-1.5 block">{{ $isAr ? 'الاسم (إنجليزي)' : 'Name (English)' }}</label>
                        <input type="text" name="name_en" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:border-navy transition-colors text-sm" dir="ltr">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-1.5 block">{{ $isAr ? 'البريد الإلكتروني' : 'Email' }}</label>
                    <input type="email" name="email" required dir="ltr" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:border-navy transition-colors text-sm">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-1.5 block">{{ $isAr ? 'كلمة المرور' : 'Password' }}</label>
                        <input type="password" name="password" required dir="ltr" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:border-navy transition-colors text-sm">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-1.5 block">{{ $isAr ? 'رقم الهاتف' : 'Phone' }}</label>
                        <input type="text" name="phone" dir="ltr" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:border-navy transition-colors text-sm">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-1.5 block">{{ $isAr ? 'الدور / الصلاحية' : 'Role' }}</label>
                    <select name="role" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:border-navy transition-colors text-sm bg-white">
                        @foreach($roles as $roleKey => $roleLabel)
                        <option value="{{ $roleKey }}">{{ $roleLabel }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 bg-navy hover:bg-navy-dark text-white py-3 rounded-xl font-bold transition-all">{{ $isAr ? 'إضافة' : 'Add' }}</button>
                    <button type="button" @click="showAdd = false" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-xl font-bold transition-all">{{ $isAr ? 'إلغاء' : 'Cancel' }}</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div x-show="editUser" x-cloak class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" @click.self="editUser = null">
        <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-black text-navy text-lg">{{ $isAr ? 'تعديل المستخدم' : 'Edit User' }}</h3>
                <button @click="editUser = null" class="text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <template x-if="editUser">
                <form method="POST" :action="`/dashboard/users/${editUser.id}`" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-bold text-navy mb-1.5 block">{{ $isAr ? 'الاسم (عربي)' : 'Name' }}</label>
                            <input type="text" name="name_ar" :value="editUser.name_ar" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:border-navy transition-colors text-sm">
                        </div>
                        <div>
                            <label class="text-sm font-bold text-navy mb-1.5 block">{{ $isAr ? 'الاسم (إنجليزي)' : 'English Name' }}</label>
                            <input type="text" name="name_en" :value="editUser.name_en" dir="ltr" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:border-navy transition-colors text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-1.5 block">{{ $isAr ? 'البريد الإلكتروني' : 'Email' }}</label>
                        <input type="email" name="email" :value="editUser.email" required dir="ltr" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:border-navy transition-colors text-sm">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-bold text-navy mb-1.5 block">{{ $isAr ? 'كلمة المرور الجديدة' : 'New Password' }}</label>
                            <input type="password" name="password" dir="ltr" placeholder="{{ $isAr ? 'اتركه فارغاً للإبقاء' : 'Leave blank to keep' }}" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:border-navy transition-colors text-sm">
                        </div>
                        <div>
                            <label class="text-sm font-bold text-navy mb-1.5 block">{{ $isAr ? 'الحالة' : 'Status' }}</label>
                            <select name="status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:border-navy transition-colors text-sm bg-white">
                                <option value="active" :selected="editUser.status === 'active'">{{ $isAr ? 'نشط' : 'Active' }}</option>
                                <option value="inactive" :selected="editUser.status === 'inactive'">{{ $isAr ? 'معطل' : 'Inactive' }}</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-1.5 block">{{ $isAr ? 'الدور / الصلاحية' : 'Role' }}</label>
                        <select name="role" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:border-navy transition-colors text-sm bg-white">
                            @foreach($roles as $roleKey => $roleLabel)
                            <option value="{{ $roleKey }}" :selected="editUser.role === '{{ $roleKey }}'">{{ $roleLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="submit" class="flex-1 bg-navy hover:bg-navy-dark text-white py-3 rounded-xl font-bold transition-all">{{ $isAr ? 'حفظ' : 'Save' }}</button>
                        <button type="button" @click="editUser = null" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-xl font-bold transition-all">{{ $isAr ? 'إلغاء' : 'Cancel' }}</button>
                    </div>
                </form>
            </template>
        </div>
    </div>
</div>
@endsection
