
<?php $__env->startSection('title', 'INSEP PRO - المالية'); ?>
<?php $__env->startSection('dashboard-content'); ?>
<h1 class="text-2xl font-black text-navy mb-6">المالية</h1>
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 border border-gray-100">
        <p class="text-gray-500 text-sm mb-1">إجمالي الإيرادات</p>
        <p class="text-2xl font-black text-green-600" style="font-family: 'Roboto', sans-serif"><?php echo e(number_format($summary['income'])); ?> ج.م</p>
    </div>
    <div class="bg-white rounded-2xl p-6 border border-gray-100">
        <p class="text-gray-500 text-sm mb-1">إجمالي المصروفات</p>
        <p class="text-2xl font-black text-red-600" style="font-family: 'Roboto', sans-serif"><?php echo e(number_format($summary['expense'])); ?> ج.م</p>
    </div>
    <div class="bg-white rounded-2xl p-6 border border-gray-100">
        <p class="text-gray-500 text-sm mb-1">صافي الربح</p>
        <p class="text-2xl font-black text-navy" style="font-family: 'Roboto', sans-serif"><?php echo e(number_format($summary['income'] - $summary['expense'])); ?> ج.م</p>
    </div>
</div>
<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الوصف</th>
                <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">المبلغ</th>
                <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">النوع</th>
                <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">التاريخ</th>
            </tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                    <td class="px-6 py-4 font-bold text-navy text-sm"><?php echo e($tx->description ?? '-'); ?></td>
                    <td class="px-6 py-4 text-sm font-bold <?php echo e($tx->type === 'income' ? 'text-green-600' : 'text-red-600'); ?>" style="font-family: 'Roboto', sans-serif"><?php echo e($tx->type === 'income' ? '+' : '-'); ?><?php echo e(number_format($tx->amount)); ?></td>
                    <td class="px-6 py-4"><span class="px-3 py-1 rounded-lg text-xs font-bold <?php echo e($tx->type === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'); ?>"><?php echo e($tx->type === 'income' ? 'إيراد' : 'مصروف'); ?></span></td>
                    <td class="px-6 py-4 text-sm text-gray-500"><?php echo e($tx->created_at?->format('Y-m-d')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="4" class="text-center py-12 text-gray-400">لا يوجد معاملات مالية</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\insep\insep\resources\views/dashboard/finance.blade.php ENDPATH**/ ?>