@extends('layouts.dashboard')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
@section('title', $isAr ? 'إدارة المستخدمين' : 'User Management')

@section('dashboard-content')
<div class="p-6" x-data="{ showAdd: false, editUser: null, resetUser: null }">

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

    @php
        $roleBadges = [
            'super_admin' => ['label' => 'سوبر أدمن',   'color' => 'bg-yellow-100 text-yellow-800'],
            'admin'       => ['label' => 'مدير إداري',   'color' => 'bg-red-100 text-red-700'],
            'supervisor'  => ['label' => 'مشرف تدريب',  'color' => 'bg-indigo-100 text-indigo-700'],
            'instructor'  => ['label' => 'مدرب',          'color' => 'bg-blue-100 text-blue-700'],
            'student'     => ['label' => 'متدرب',         'color' => 'bg-green-100 text-green-700'],
            'finance'     => ['label' => 'محاسب',         'color' => 'bg-purple-100 text-purple-700'],
            'support'     => ['label' => 'دعم فني',       'color' => 'bg-orange-100 text-orange-700'],
        ];
        $isSuperAdmin = auth()->user()->isSuperAdmin();
    @endphp

    {{-- Stats bar --}}
    <div class="grid grid-cols-4 md:grid-cols-7 gap-3 mb-6">
        @foreach($roleBadges as $roleKey => $info)
        @if($roleKey !== 'super_admin' || $isSuperAdmin)
        <div class="bg-white rounded-xl p-4 border border-gray-100 text-center shadow-sm">
            <div class="text-2xl font-black text-navy">{{ $roleCounts[$roleKey] ?? 0 }}</div>
            <div class="text-xs font-medium text-gray-500 mt-1">{{ $info['label'] }}</div>
        </div>
        @endif
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
                        @if($isSuperAdmin)
                        <th class="text-right px-5 py-3.5 font-bold text-navy text-xs">{{ $isAr ? 'الهاتف' : 'Phone' }}</th>
                        @endif
                        <th class="text-right px-5 py-3.5 font-bold text-navy text-xs">{{ $isAr ? 'الدور' : 'Role' }}</th>
                        <th class="text-right px-5 py-3.5 font-bold text-navy text-xs">{{ $isAr ? 'الحالة' : 'Status' }}</th>
                        <th class="text-right px-5 py-3.5 font-bold text-navy text-xs">{{ $isAr ? 'تاريخ الإنشاء' : 'Created' }}</th>
                        <th class="text-right px-5 py-3.5 font-bold text-navy text-xs">{{ $isAr ? 'إجراءات' : 'Actions' }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $u)
                    @php
                        $isOwner = $u->email === $ownerEmail;
                        $isProtected = ($u->isSuperAdmin() && !$isSuperAdmin) || $isOwner;
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors {{ $isOwner ? 'bg-yellow-50/60' : ($u->isSuperAdmin() ? 'bg-yellow-50/40' : '') }}">
                        <td class="px-5 py-3.5 text-gray-400 font-mono text-xs">{{ $u->id }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg {{ $isOwner ? 'bg-yellow-500/30' : ($u->isSuperAdmin() ? 'bg-yellow-400/20' : 'bg-navy/10') }} flex items-center justify-center text-navy font-bold text-sm flex-shrink-0">
                                    @if($isOwner)
                                    <svg class="w-4 h-4 text-yellow-700" fill="currentColor" viewBox="0 0 24 24"><path d="M5 16L3 5l5.5 5L12 2l3.5 8L21 5l-2 11H5zm0 2h14v2H5v-2z"/></svg>
                                    @elseif($u->isSuperAdmin())
                                    <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1l3.09 6.26L22 9l-5 4.87L18.18 21 12 17.77 5.82 21 7 13.87 2 9l6.91-1.74L12 1z"/></svg>
                                    @else
                                    {{ mb_substr($u->name ?? '?', 0, 1) }}
                                    @endif
                                </div>
                                <div>
                                    <span class="font-semibold text-navy">{{ $u->name ?? '-' }}</span>
                                    @if($isOwner)
                                    <span class="block text-xs text-yellow-700 font-bold">{{ $isAr ? 'مالك النظام' : 'System Owner' }}</span>
                                    @elseif($u->isSuperAdmin())
                                    <span class="block text-xs text-yellow-600 font-bold">{{ $isAr ? 'سوبر أدمن' : 'Super Admin' }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-gray-500" dir="ltr">{{ $u->email }}</td>
                        @if($isSuperAdmin)
                        <td class="px-5 py-3.5 text-gray-500 text-xs" dir="ltr">{{ $u->phone ?? '—' }}</td>
                        @endif
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
                            @if($isProtected)
                            <span class="flex items-center gap-1 text-xs {{ $isOwner ? 'text-yellow-700 font-bold' : 'text-gray-400' }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                                {{ $isOwner ? ($isAr ? 'مالك النظام' : 'System Owner') : ($isAr ? 'محمي' : 'Protected') }}
                            </span>
                            @else
                            <div class="flex items-center gap-2">
                                <button @click="editUser = {{ $u->toJson() }}"
                                    class="p-1.5 hover:bg-navy/10 rounded-lg transition-colors text-navy" title="{{ $isAr ? 'تعديل' : 'Edit' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                @if($isSuperAdmin)
                                <button @click="resetUser = { id: {{ $u->id }}, name: '{{ addslashes($u->name) }}' }"
                                    class="p-1.5 hover:bg-yellow-50 rounded-lg transition-colors text-yellow-600" title="{{ $isAr ? 'تغيير كلمة المرور' : 'Reset Password' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                                </button>
                                @endif
                                @if($u->id !== auth()->id())
                                <form method="POST" action="{{ route('dashboard.users.destroy', $u->id) }}"
                                    onsubmit="return confirm('{{ $isAr ? 'هل أنت متأكد من حذف هذا المستخدم؟' : 'Are you sure?' }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 hover:bg-red-100 rounded-lg transition-colors text-red-500" title="{{ $isAr ? 'حذف' : 'Delete' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path stroke-linecap="round" stroke-linejoin="round" d="M19 6l-1 14H6L5 6m5 0V4h4v2"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="{{ $isSuperAdmin ? 8 : 7 }}" class="px-5 py-10 text-center text-gray-400">{{ $isAr ? 'لا يوجد مستخدمون' : 'No users found' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
        @endif
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
                            <label class="text-sm font-bold text-navy mb-1.5 block">{{ $isAr ? 'الهاتف' : 'Phone' }}</label>
                            <input type="text" name="phone" :value="editUser.phone" dir="ltr" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:border-navy transition-colors text-sm">
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

    {{-- Reset Password Modal (super_admin only) --}}
    @if(auth()->user()->isSuperAdmin())
    <div x-show="resetUser" x-cloak class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" @click.self="resetUser = null">
        <div class="bg-white rounded-2xl w-full max-w-sm shadow-2xl">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-black text-navy text-lg">{{ $isAr ? 'تغيير كلمة المرور' : 'Reset Password' }}</h3>
                <button @click="resetUser = null" class="text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <template x-if="resetUser">
                <form method="POST" :action="`/dashboard/users/${resetUser.id}/reset-password`" class="p-6 space-y-4">
                    @csrf
                    <p class="text-sm text-gray-500">
                        {{ $isAr ? 'تغيير كلمة مرور' : 'Change password for' }}:
                        <span class="font-bold text-navy" x-text="resetUser.name"></span>
                    </p>
                    <div>
                        <label class="text-sm font-bold text-navy mb-1.5 block">{{ $isAr ? 'كلمة المرور الجديدة' : 'New Password' }}</label>
                        <input type="password" name="password" required minlength="6" dir="ltr"
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:border-yellow-400 transition-colors text-sm"
                            placeholder="{{ $isAr ? 'أدخل كلمة المرور الجديدة' : 'Enter new password' }}">
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="submit" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white py-3 rounded-xl font-bold transition-all">{{ $isAr ? 'تغيير' : 'Change' }}</button>
                        <button type="button" @click="resetUser = null" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-xl font-bold transition-all">{{ $isAr ? 'إلغاء' : 'Cancel' }}</button>
                    </div>
                </form>
            </template>
        </div>
    </div>
    @endif

</div>
@endsection
