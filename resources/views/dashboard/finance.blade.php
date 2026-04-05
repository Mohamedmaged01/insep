@extends('layouts.dashboard')
@section('title', 'INSEP PRO')
@section('dashboard-content')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; @endphp
<div x-data="financeManager()">

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-green-700 text-sm font-bold">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-black text-navy">{{ $isAr ? 'المالية' : 'Finance' }}</h1>
        @if(auth()->user()->role === 'admin')
        <div class="flex gap-2">
            <button @click="showAddModal = true" class="bg-red-brand hover:bg-red-brand-dark text-white px-4 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                {{ $isAr ? 'معاملة جديدة' : 'Add Transaction' }}
            </button>
            <button @click="showInstallmentModal = true" class="bg-navy hover:bg-navy-dark text-white px-4 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                {{ $isAr ? 'خطة تقسيط' : 'Installment Plan' }}
            </button>
        </div>
        @endif
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 border border-gray-100">
            <p class="text-gray-500 text-sm mb-1">{{ $isAr ? 'إجمالي الإيرادات' : 'Total Revenue' }}</p>
            <p class="text-2xl font-black text-green-600" style="font-family: 'Roboto', sans-serif">{{ number_format($summary['income']) }} {{ $isAr ? 'ج.م' : 'EGP' }}</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-gray-100">
            <p class="text-gray-500 text-sm mb-1">{{ $isAr ? 'إجمالي المصروفات' : 'Total Expenses' }}</p>
            <p class="text-2xl font-black text-red-600" style="font-family: 'Roboto', sans-serif">{{ number_format($summary['expense']) }} {{ $isAr ? 'ج.م' : 'EGP' }}</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-gray-100">
            <p class="text-gray-500 text-sm mb-1">{{ $isAr ? 'صافي الربح' : 'Net Profit' }}</p>
            <p class="text-2xl font-black text-navy" style="font-family: 'Roboto', sans-serif">{{ number_format($summary['income'] - $summary['expense']) }} {{ $isAr ? 'ج.م' : 'EGP' }}</p>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-1 mb-4 bg-gray-100 rounded-xl p-1 w-fit">
        <button @click="tab = 'transactions'" :class="tab === 'transactions' ? 'bg-white shadow text-navy' : 'text-gray-500'" class="px-5 py-2 rounded-lg text-sm font-bold transition-all">{{ $isAr ? 'المعاملات' : 'Transactions' }}</button>
        <button @click="tab = 'installments'" :class="tab === 'installments' ? 'bg-white shadow text-navy' : 'text-gray-500'" class="px-5 py-2 rounded-lg text-sm font-bold transition-all">{{ $isAr ? 'التقسيط' : 'Installments' }}</button>
    </div>

    {{-- Transactions Tab --}}
    <div x-show="tab === 'transactions'">
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead><tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'الوصف' : 'Description' }}</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'المبلغ' : 'Amount' }}</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'النوع' : 'Type' }}</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'طريقة الدفع' : 'Payment Method' }}</th>
                        @if(auth()->user()->role === 'admin')
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'الطالب' : 'Student' }}</th>
                        @endif
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'الحالة' : 'Status' }}</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'إثبات الدفع' : 'Payment Proof' }}</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'التاريخ' : 'Date' }}</th>
                        @if(auth()->user()->role === 'admin')
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'إجراءات' : 'Actions' }}</th>
                        @endif
                    </tr></thead>
                    <tbody>
                        @forelse($transactions as $tx)
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                            <td class="px-6 py-4 font-bold text-navy text-sm">{{ $tx->description }}</td>
                            <td class="px-6 py-4 text-sm font-bold {{ $tx->type === 'income' ? 'text-green-600' : 'text-red-600' }}" style="font-family:'Roboto',sans-serif">
                                {{ $tx->type === 'income' ? '+' : '-' }}{{ number_format($tx->amount) }} {{ $isAr ? 'ج.م' : 'EGP' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $tx->type === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $tx->type === 'income' ? ($isAr ? 'إيراد' : 'Income') : ($isAr ? 'مصروف' : 'Expense') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $tx->method ?? '-' }}</td>
                            @if(auth()->user()->role === 'admin')
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $tx->user->name ?? '-' }}</td>
                            @endif
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $tx->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $tx->status === 'completed' ? ($isAr ? 'مكتمل' : 'Completed') : ($isAr ? 'معلق' : 'Pending') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($tx->payment_proof)
                                <a href="{{ asset('storage/' . $tx->payment_proof) }}" target="_blank" class="text-xs text-blue-500 hover:underline flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M13 12H3"/></svg>
                                    {{ $isAr ? 'عرض' : 'View' }}
                                </a>
                                @else
                                <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500" style="font-family:'Roboto',sans-serif">
                                {{ \Carbon\Carbon::parse($tx->created_at)->format('Y-m-d') }}
                            </td>
                            @if(auth()->user()->role === 'admin')
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <button @click="openEdit({{ $tx->id }}, '{{ addslashes($tx->description) }}', {{ $tx->amount }}, '{{ $tx->currency ?? 'EGP' }}', '{{ $tx->type }}', '{{ $tx->method ?? '' }}', '{{ $tx->status }}')"
                                        class="p-2 hover:bg-yellow-50 rounded-lg transition-colors text-yellow-500" title="{{ $isAr ? 'تعديل' : 'Edit' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </button>
                                    <form method="POST" action="{{ route('dashboard.finance.destroy', $tx->id) }}" onsubmit="return confirm('{{ $isAr ? 'هل أنت متأكد من حذف هذه المعاملة؟' : 'Are you sure you want to delete this transaction?' }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 hover:bg-red-50 rounded-lg transition-colors text-red-500" title="{{ $isAr ? 'حذف' : 'Delete' }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr><td colspan="9" class="text-center py-12 text-gray-400">{{ $isAr ? 'لا يوجد معاملات مالية بعد' : 'No financial transactions yet' }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Installments Tab --}}
    <div x-show="tab === 'installments'">
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead><tr class="bg-gray-50 border-b border-gray-100">
                        @if(auth()->user()->role === 'admin')
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'الطالب' : 'Student' }}</th>
                        @endif
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'الدورة / المجموعة' : 'Course / Batch' }}</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'الإجمالي' : 'Total Amount' }}</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'المدفوع' : 'Paid' }}</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'المتبقي' : 'Remaining' }}</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'تاريخ الاستحقاق' : 'Due Date' }}</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'الحالة' : 'Status' }}</th>
                        @if(auth()->user()->role === 'admin')
                        <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">{{ $isAr ? 'إجراءات' : 'Actions' }}</th>
                        @endif
                    </tr></thead>
                    <tbody>
                        @forelse($installments as $inst)
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                            @if(auth()->user()->role === 'admin')
                            <td class="px-6 py-4 font-bold text-navy text-sm">{{ $inst->student->name ?? '-' }}</td>
                            @endif
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $inst->course->title ?? ($inst->batch->name ?? '-') }}
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-navy" style="font-family:'Roboto',sans-serif">{{ number_format($inst->total_amount) }} {{ $isAr ? 'ج.م' : 'EGP' }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-green-600" style="font-family:'Roboto',sans-serif">{{ number_format($inst->paid_amount) }} {{ $isAr ? 'ج.م' : 'EGP' }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-red-600" style="font-family:'Roboto',sans-serif">{{ number_format($inst->total_amount - $inst->paid_amount) }} {{ $isAr ? 'ج.م' : 'EGP' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500" style="font-family:'Roboto',sans-serif">{{ $inst->due_date ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-lg text-xs font-bold
                                    {{ $inst->status === 'paid' ? 'bg-green-100 text-green-700' : ($inst->status === 'partial' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ $inst->status === 'paid' ? ($isAr ? 'مدفوع' : 'Paid') : ($inst->status === 'partial' ? ($isAr ? 'جزئي' : 'Partial') : ($isAr ? 'معلق' : 'Pending')) }}
                                </span>
                            </td>
                            @if(auth()->user()->role === 'admin')
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <button @click="openInstallmentEdit({{ $inst->id }}, {{ $inst->total_amount }}, {{ $inst->paid_amount }}, '{{ $inst->due_date ?? '' }}', '{{ $inst->status }}', '{{ addslashes($inst->notes ?? '') }}')"
                                        class="p-2 hover:bg-yellow-50 rounded-lg transition-colors text-yellow-500" title="{{ $isAr ? 'تعديل' : 'Edit' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </button>
                                    <form method="POST" action="{{ route('dashboard.installments.destroy', $inst->id) }}" onsubmit="return confirm('{{ $isAr ? 'حذف خطة التقسيط؟' : 'Delete installment plan?' }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 hover:bg-red-50 rounded-lg transition-colors text-red-500" title="{{ $isAr ? 'حذف' : 'Delete' }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center py-12 text-gray-400">{{ $isAr ? 'لا يوجد خطط تقسيط بعد' : 'No installment plans yet' }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Add Transaction Modal --}}
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showAddModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-lg relative z-10 shadow-2xl max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl font-black text-navy mb-6">{{ $isAr ? 'إضافة معاملة مالية' : 'Add Financial Transaction' }}</h2>
            <form method="POST" action="{{ route('dashboard.finance.store') }}" class="space-y-4" enctype="multipart/form-data">
                @csrf
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الوصف' : 'Description' }}</label>
                    <input type="text" name="description" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'العملة والمبلغ' : 'Currency & Amount' }}</label>
                    <div class="flex gap-2">
                        <div class="flex rounded-xl border-2 border-gray-200 overflow-hidden flex-shrink-0">
                            @foreach([['USD','$ دولار'],['EGP','ج.م'],['SAR','ر.س']] as [$val,$lbl])
                            <label class="px-3 py-3 text-sm font-bold cursor-pointer transition-colors has-[:checked]:bg-navy has-[:checked]:text-white text-gray-600 hover:bg-gray-50">
                                <input type="radio" name="currency" value="{{ $val }}" {{ $val==='EGP' ? 'checked' : '' }} class="sr-only">
                                {{ $lbl }}
                            </label>
                            @endforeach
                        </div>
                        <input type="number" name="amount" min="0" step="0.01" placeholder="0.00" class="flex-1 border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr" required>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'النوع' : 'Type' }}</label>
                    <select name="type" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        <option value="income">{{ $isAr ? 'إيراد' : 'Income' }}</option>
                        <option value="expense">{{ $isAr ? 'مصروف' : 'Expense' }}</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'طريقة الدفع' : 'Payment Method' }}</label>
                        <select name="method" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="cash">{{ $isAr ? 'نقدي' : 'Cash' }}</option>
                            <option value="bank">{{ $isAr ? 'تحويل بنكي' : 'Bank Transfer' }}</option>
                            <option value="wallet">{{ $isAr ? 'محفظة إلكترونية' : 'Digital Wallet' }}</option>
                            <option value="visa">{{ $isAr ? 'فيزا' : 'Visa' }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الحالة' : 'Status' }}</label>
                        <select name="status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="completed">{{ $isAr ? 'مكتمل' : 'Completed' }}</option>
                            <option value="pending">{{ $isAr ? 'معلق' : 'Pending' }}</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الطالب (اختياري)' : 'Student (optional)' }}</label>
                    <select name="user_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        <option value="">{{ $isAr ? '-- بدون طالب --' : '-- No Student --' }}</option>
                        @foreach($students as $student)
                        <option value="{{ $student->id }}">{{ $student->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'إثبات الدفع (صورة/PDF) — اختياري' : 'Payment Proof (image/PDF) — optional' }}</label>
                    <input type="file" name="payment_proof" accept="image/*,.pdf" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors text-sm" dir="ltr">
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showAddModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200 transition-colors">{{ $isAr ? 'إلغاء' : 'Cancel' }}</button>
                    <button type="submit" class="flex-1 bg-navy hover:bg-navy-dark text-white py-3 rounded-xl font-bold transition-colors">{{ $isAr ? 'حفظ' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Transaction Modal --}}
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showEditModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-lg relative z-10 shadow-2xl max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl font-black text-navy mb-6">{{ $isAr ? 'تعديل المعاملة المالية' : 'Edit Financial Transaction' }}</h2>
            <form method="POST" :action="'/dashboard/finance/' + editItem.id" class="space-y-4" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الوصف' : 'Description' }}</label>
                    <input type="text" name="description" x-model="editItem.description" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'العملة والمبلغ' : 'Currency & Amount' }}</label>
                    <div class="flex gap-2">
                        <div class="flex rounded-xl border-2 border-gray-200 overflow-hidden flex-shrink-0">
                            @foreach([['USD','$ دولار'],['EGP','ج.م'],['SAR','ر.س']] as [$val,$lbl])
                            <label class="px-3 py-3 text-sm font-bold cursor-pointer transition-colors text-gray-600 hover:bg-gray-50"
                                   :class="editItem.currency === '{{ $val }}' ? 'bg-navy text-white' : ''">
                                <input type="radio" name="currency" value="{{ $val }}" x-model="editItem.currency" class="sr-only">
                                {{ $lbl }}
                            </label>
                            @endforeach
                        </div>
                        <input type="number" name="amount" x-model="editItem.amount" min="0" step="0.01" class="flex-1 border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr" required>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'النوع' : 'Type' }}</label>
                    <select name="type" x-model="editItem.type" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        <option value="income">{{ $isAr ? 'إيراد' : 'Income' }}</option>
                        <option value="expense">{{ $isAr ? 'مصروف' : 'Expense' }}</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'طريقة الدفع' : 'Payment Method' }}</label>
                        <select name="method" x-model="editItem.method" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="cash">{{ $isAr ? 'نقدي' : 'Cash' }}</option>
                            <option value="bank">{{ $isAr ? 'تحويل بنكي' : 'Bank Transfer' }}</option>
                            <option value="wallet">{{ $isAr ? 'محفظة إلكترونية' : 'Digital Wallet' }}</option>
                            <option value="visa">{{ $isAr ? 'فيزا' : 'Visa' }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الحالة' : 'Status' }}</label>
                        <select name="status" x-model="editItem.status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="completed">{{ $isAr ? 'مكتمل' : 'Completed' }}</option>
                            <option value="pending">{{ $isAr ? 'معلق' : 'Pending' }}</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'تحديث إثبات الدفع (اتركه فارغاً للإبقاء على الحالي)' : 'Update Payment Proof (leave blank to keep current)' }}</label>
                    <input type="file" name="payment_proof" accept="image/*,.pdf" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors text-sm" dir="ltr">
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showEditModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200 transition-colors">{{ $isAr ? 'إلغاء' : 'Cancel' }}</button>
                    <button type="submit" class="flex-1 bg-navy hover:bg-navy-dark text-white py-3 rounded-xl font-bold transition-colors">{{ $isAr ? 'حفظ التعديلات' : 'Save Changes' }}</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Add Installment Modal --}}
    @if(auth()->user()->role === 'admin')
    <div x-show="showInstallmentModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showInstallmentModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-lg relative z-10 shadow-2xl max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl font-black text-navy mb-6">{{ $isAr ? 'إضافة خطة تقسيط' : 'Add Installment Plan' }}</h2>
            <form method="POST" action="{{ route('dashboard.installments.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الطالب' : 'Student' }}</label>
                    <select name="student_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                        <option value="">{{ $isAr ? '-- اختر الطالب --' : '-- Select Student --' }}</option>
                        @foreach($students as $student)
                        <option value="{{ $student->id }}">{{ $student->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الدورة' : 'Course' }}</label>
                    <select name="course_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        <option value="">{{ $isAr ? '-- اختر الدورة --' : '-- Select Course --' }}</option>
                        @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'المبلغ الإجمالي (ج.م)' : 'Total Amount (EGP)' }}</label>
                        <input type="number" name="total_amount" min="0" step="0.01" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr" required>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'المدفوع حتى الآن' : 'Paid So Far' }}</label>
                        <input type="number" name="paid_amount" value="0" min="0" step="0.01" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'تاريخ الاستحقاق' : 'Due Date' }}</label>
                        <input type="date" name="due_date" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الحالة' : 'Status' }}</label>
                        <select name="status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="pending">{{ $isAr ? 'معلق' : 'Pending' }}</option>
                            <option value="partial">{{ $isAr ? 'جزئي' : 'Partial' }}</option>
                            <option value="paid">{{ $isAr ? 'مدفوع' : 'Paid' }}</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'ملاحظات (اختياري)' : 'Notes (optional)' }}</label>
                    <textarea name="notes" rows="2" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors"></textarea>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showInstallmentModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200 transition-colors">{{ $isAr ? 'إلغاء' : 'Cancel' }}</button>
                    <button type="submit" class="flex-1 bg-navy hover:bg-navy-dark text-white py-3 rounded-xl font-bold transition-colors">{{ $isAr ? 'حفظ' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Installment Modal --}}
    <div x-show="showInstallmentEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showInstallmentEditModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-lg relative z-10 shadow-2xl">
            <h2 class="text-xl font-black text-navy mb-6">{{ $isAr ? 'تعديل خطة التقسيط' : 'Edit Installment Plan' }}</h2>
            <form method="POST" :action="'/dashboard/installments/' + editInst.id" class="space-y-4">
                @csrf @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'المبلغ الإجمالي (ج.م)' : 'Total Amount (EGP)' }}</label>
                        <input type="number" name="total_amount" x-model="editInst.total_amount" min="0" step="0.01" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'المدفوع' : 'Paid' }}</label>
                        <input type="number" name="paid_amount" x-model="editInst.paid_amount" min="0" step="0.01" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'تاريخ الاستحقاق' : 'Due Date' }}</label>
                        <input type="date" name="due_date" x-model="editInst.due_date" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'الحالة' : 'Status' }}</label>
                        <select name="status" x-model="editInst.status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="pending">{{ $isAr ? 'معلق' : 'Pending' }}</option>
                            <option value="partial">{{ $isAr ? 'جزئي' : 'Partial' }}</option>
                            <option value="paid">{{ $isAr ? 'مدفوع' : 'Paid' }}</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">{{ $isAr ? 'ملاحظات' : 'Notes' }}</label>
                    <textarea name="notes" x-model="editInst.notes" rows="2" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors"></textarea>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showInstallmentEditModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200 transition-colors">{{ $isAr ? 'إلغاء' : 'Cancel' }}</button>
                    <button type="submit" class="flex-1 bg-navy hover:bg-navy-dark text-white py-3 rounded-xl font-bold transition-colors">{{ $isAr ? 'حفظ التعديلات' : 'Save Changes' }}</button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
@push('scripts')
<script>
function financeManager() {
    return {
        tab: 'transactions',
        showAddModal: false,
        showEditModal: false,
        showInstallmentModal: false,
        showInstallmentEditModal: false,
        editItem: { id: null, description: '', amount: 0, currency: 'EGP', type: 'income', method: 'cash', status: 'completed' },
        editInst: { id: null, total_amount: 0, paid_amount: 0, due_date: '', status: 'pending', notes: '' },
        openEdit(id, description, amount, currency, type, method, status) {
            this.editItem = { id, description, amount, currency, type, method, status };
            this.showEditModal = true;
        },
        openInstallmentEdit(id, total_amount, paid_amount, due_date, status, notes) {
            this.editInst = { id, total_amount, paid_amount, due_date, status, notes };
            this.showInstallmentEditModal = true;
        }
    };
}
</script>
@endpush
@endsection
