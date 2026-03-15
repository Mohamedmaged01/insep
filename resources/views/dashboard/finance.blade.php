@extends('layouts.dashboard')
@section('title', 'INSEP PRO - المالية')
@section('dashboard-content')
<div x-data="financeManager()">

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-green-700 text-sm font-bold">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-black text-navy">المالية</h1>
        @if(auth()->user()->role === 'admin')
        <button @click="showAddModal = true" class="bg-red-brand hover:bg-red-brand-dark text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            إضافة معاملة
        </button>
        @endif
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 border border-gray-100">
            <p class="text-gray-500 text-sm mb-1">إجمالي الإيرادات</p>
            <p class="text-2xl font-black text-green-600" style="font-family: 'Roboto', sans-serif">{{ number_format($summary['income']) }} ج.م</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-gray-100">
            <p class="text-gray-500 text-sm mb-1">إجمالي المصروفات</p>
            <p class="text-2xl font-black text-red-600" style="font-family: 'Roboto', sans-serif">{{ number_format($summary['expense']) }} ج.م</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-gray-100">
            <p class="text-gray-500 text-sm mb-1">صافي الربح</p>
            <p class="text-2xl font-black text-navy" style="font-family: 'Roboto', sans-serif">{{ number_format($summary['income'] - $summary['expense']) }} ج.م</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead><tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الوصف</th>
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">المبلغ</th>
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">النوع</th>
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">طريقة الدفع</th>
                    @if(auth()->user()->role === 'admin')
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الطالب</th>
                    @endif
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الحالة</th>
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">التاريخ</th>
                    @if(auth()->user()->role === 'admin')
                    <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">إجراءات</th>
                    @endif
                </tr></thead>
                <tbody>
                    @forelse($transactions as $tx)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                        <td class="px-6 py-4 font-bold text-navy text-sm">{{ $tx->description }}</td>
                        <td class="px-6 py-4 text-sm font-bold {{ $tx->type === 'income' ? 'text-green-600' : 'text-red-600' }}" style="font-family:'Roboto',sans-serif">
                            {{ $tx->type === 'income' ? '+' : '-' }}{{ number_format($tx->amount) }} ج.م
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $tx->type === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $tx->type === 'income' ? 'إيراد' : 'مصروف' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $tx->method ?? '-' }}</td>
                        @if(auth()->user()->role === 'admin')
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $tx->user->name ?? '-' }}</td>
                        @endif
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $tx->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $tx->status === 'completed' ? 'مكتمل' : 'معلق' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500" style="font-family:'Roboto',sans-serif">
                            {{ \Carbon\Carbon::parse($tx->created_at)->format('Y-m-d') }}
                        </td>
                        @if(auth()->user()->role === 'admin')
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button @click="openEdit({{ $tx->id }}, '{{ addslashes($tx->description) }}', {{ $tx->amount }}, '{{ $tx->type }}', '{{ $tx->method ?? '' }}', '{{ $tx->status }}')"
                                    class="p-2 hover:bg-yellow-50 rounded-lg transition-colors text-yellow-500" title="تعديل">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>
                                <form method="POST" action="{{ route('dashboard.finance.destroy', $tx->id) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذه المعاملة؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 hover:bg-red-50 rounded-lg transition-colors text-red-500" title="حذف">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-12 text-gray-400">لا يوجد معاملات مالية بعد</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add Modal --}}
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showAddModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-lg relative z-10 shadow-2xl max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl font-black text-navy mb-6">إضافة معاملة مالية</h2>
            <form method="POST" action="{{ route('dashboard.finance.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الوصف</label>
                    <input type="text" name="description" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">المبلغ (ج.م)</label>
                        <input type="number" name="amount" min="0" step="0.01" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr" required>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">النوع</label>
                        <select name="type" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="income">إيراد</option>
                            <option value="expense">مصروف</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">طريقة الدفع</label>
                        <select name="method" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="cash">نقدي</option>
                            <option value="bank">تحويل بنكي</option>
                            <option value="wallet">محفظة إلكترونية</option>
                            <option value="visa">فيزا</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">الحالة</label>
                        <select name="status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="completed">مكتمل</option>
                            <option value="pending">معلق</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الطالب (اختياري)</label>
                    <select name="user_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                        <option value="">-- بدون طالب --</option>
                        @foreach($students as $student)
                        <option value="{{ $student->id }}">{{ $student->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showAddModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200 transition-colors">إلغاء</button>
                    <button type="submit" class="flex-1 bg-navy hover:bg-navy-dark text-white py-3 rounded-xl font-bold transition-colors">حفظ</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showEditModal = false"></div>
        <div class="bg-white rounded-2xl p-8 w-full max-w-lg relative z-10 shadow-2xl">
            <h2 class="text-xl font-black text-navy mb-6">تعديل المعاملة المالية</h2>
            <form method="POST" :action="'/dashboard/finance/' + editItem.id" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الوصف</label>
                    <input type="text" name="description" x-model="editItem.description" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">المبلغ (ج.م)</label>
                        <input type="number" name="amount" x-model="editItem.amount" min="0" step="0.01" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors" dir="ltr" required>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">النوع</label>
                        <select name="type" x-model="editItem.type" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="income">إيراد</option>
                            <option value="expense">مصروف</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">طريقة الدفع</label>
                        <select name="method" x-model="editItem.method" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="cash">نقدي</option>
                            <option value="bank">تحويل بنكي</option>
                            <option value="wallet">محفظة إلكترونية</option>
                            <option value="visa">فيزا</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-navy mb-2 block">الحالة</label>
                        <select name="status" x-model="editItem.status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-navy transition-colors">
                            <option value="completed">مكتمل</option>
                            <option value="pending">معلق</option>
                        </select>
                    </div>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showEditModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200 transition-colors">إلغاء</button>
                    <button type="submit" class="flex-1 bg-navy hover:bg-navy-dark text-white py-3 rounded-xl font-bold transition-colors">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>

</div>
@push('scripts')
<script>
function financeManager() {
    return {
        showAddModal: false,
        showEditModal: false,
        editItem: { id: null, description: '', amount: 0, type: 'income', method: 'cash', status: 'completed' },
        openEdit(id, description, amount, type, method, status) {
            this.editItem = { id, description, amount, type, method, status };
            this.showEditModal = true;
        }
    };
}
</script>
@endpush
@endsection
