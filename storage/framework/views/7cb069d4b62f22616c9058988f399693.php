
<?php $__env->startSection('title', 'INSEP PRO - الاختبارات'); ?>
<?php $__env->startSection('dashboard-content'); ?>
<h1 class="text-2xl font-black text-navy mb-6">الاختبارات</h1>
<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">العنوان</th>
                <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">الدورة</th>
                <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">النوع</th>
                <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">عدد الأسئلة</th>
                <th class="text-right text-xs font-bold text-gray-500 uppercase px-6 py-4">المدة</th>
            </tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                    <td class="px-6 py-4 font-bold text-navy text-sm"><?php echo e($exam->title); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo e($exam->course->title ?? '-'); ?></td>
                    <td class="px-6 py-4"><span class="px-3 py-1 rounded-lg text-xs font-bold bg-navy/10 text-navy"><?php echo e($exam->type ?? 'نهائي'); ?></span></td>
                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo e($exam->questions_count ?? 0); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo e($exam->duration ?? '-'); ?> دقيقة</td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="text-center py-12 text-gray-400">لا يوجد اختبارات بعد</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\insep\insep\resources\views/dashboard/exams.blade.php ENDPATH**/ ?>