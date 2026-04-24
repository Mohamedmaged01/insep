@extends('layouts.dashboard')
@section('title', 'INSEP PRO')
@section('dashboard-content')
@php $lang = app()->getLocale(); $isAr = $lang === 'ar'; $role = auth()->user()->role; @endphp

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <h1 class="text-2xl font-black text-navy">{{ $isAr ? 'طلبات الشهادات' : 'Certificate Requests' }}</h1>
</div>

@if(session('success'))
<div class="mb-4 px-4 py-3 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="mb-4 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm">{{ session('error') }}</div>
@endif

{{-- Status filter tabs --}}
<div class="flex gap-2 mb-5 flex-wrap">
    @foreach([''=>($isAr?'الكل':'All'),'pending'=>($isAr?'معلق':'Pending'),'approved'=>($isAr?'موافق':'Approved'),'rejected'=>($isAr?'مرفوض':'Rejected')] as $val => $label)
    <a href="{{ request()->fullUrlWithQuery(['status' => $val]) }}"
       class="px-4 py-1.5 rounded-xl text-sm font-semibold transition {{ ($statusFilter ?? '') === $val ? 'bg-navy text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 text-gray-500 text-xs uppercase">
                    <th class="px-5 py-3 text-start font-semibold">{{ $isAr ? 'المتدرب' : 'Student' }}</th>
                    <th class="px-5 py-3 text-start font-semibold">{{ $isAr ? 'الدورة' : 'Course' }}</th>
                    <th class="px-5 py-3 text-start font-semibold">{{ $isAr ? 'المجموعة' : 'Batch' }}</th>
                    <th class="px-5 py-3 text-start font-semibold">{{ $isAr ? 'تاريخ الطلب' : 'Requested' }}</th>
                    <th class="px-5 py-3 text-start font-semibold">{{ $isAr ? 'ملاحظات' : 'Notes' }}</th>
                    <th class="px-5 py-3 text-start font-semibold">{{ $isAr ? 'الحالة' : 'Status' }}</th>
                    <th class="px-5 py-3 text-start font-semibold">{{ $isAr ? 'الإجراءات' : 'Actions' }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($requests as $req)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-5 py-3">
                        <div class="font-semibold text-navy text-sm">{{ $req->student?->name ?? '—' }}</div>
                        <div class="text-gray-400 text-xs">{{ $req->student?->email ?? '' }}</div>
                    </td>
                    <td class="px-5 py-3 text-gray-700 text-sm">{{ $req->course?->title ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $req->batch?->name ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $req->created_at?->format('Y-m-d') ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs max-w-xs truncate">{{ $req->notes ?? '—' }}</td>
                    <td class="px-5 py-3">
                        @php
                            $statusColors = [
                                'pending'  => 'bg-orange-100 text-orange-700',
                                'approved' => 'bg-green-100 text-green-700',
                                'rejected' => 'bg-red-100 text-red-700',
                            ];
                            $statusLabels = [
                                'pending'  => $isAr ? 'معلق'    : 'Pending',
                                'approved' => $isAr ? 'موافق'   : 'Approved',
                                'rejected' => $isAr ? 'مرفوض'   : 'Rejected',
                            ];
                        @endphp
                        <span class="px-2 py-0.5 rounded-lg text-xs font-semibold {{ $statusColors[$req->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $statusLabels[$req->status] ?? $req->status }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        @if($req->status === 'pending')
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('dashboard.certificate-requests.update', $req->id) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button class="px-3 py-1 rounded-lg bg-green-100 text-green-700 text-xs font-semibold hover:bg-green-200 transition">
                                    {{ $isAr ? 'موافقة' : 'Approve' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('dashboard.certificate-requests.update', $req->id) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button class="px-3 py-1 rounded-lg bg-red-100 text-red-600 text-xs font-semibold hover:bg-red-200 transition">
                                    {{ $isAr ? 'رفض' : 'Reject' }}
                                </button>
                            </form>
                        </div>
                        @else
                        <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                        {{ $isAr ? 'لا يوجد طلبات بعد' : 'No requests found' }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
