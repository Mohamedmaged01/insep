
<?php $__env->startSection('title', 'INSEP PRO - دوراتي'); ?>
<?php $__env->startSection('dashboard-content'); ?>
<h1 class="text-2xl font-black text-navy mb-6">دوراتي المسجلة</h1>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php $__empty_1 = true; $__currentLoopData = $enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 card-hover">
        <div class="h-32 bg-gradient-to-br from-navy to-navy-light relative overflow-hidden">
            <div class="absolute inset-0 hero-pattern opacity-30"></div>
            <div class="absolute bottom-3 right-3 text-white text-sm font-bold"><?php echo e($enr->course->title ?? 'دورة'); ?></div>
        </div>
        <div class="p-5">
            <p class="text-sm text-gray-500 mb-2">المجموعة: <?php echo e($enr->batch->name ?? '-'); ?></p>
            <span class="px-3 py-1 rounded-lg text-xs font-bold <?php echo e(($enr->status ?? 'active') === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'); ?>"><?php echo e(($enr->status ?? 'active') === 'active' ? 'نشط' : ($enr->status ?? '-')); ?></span>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="col-span-3 text-center py-12 text-gray-400">لم تسجل في أي دورة بعد</div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\insep\insep\resources\views/dashboard/mycourses.blade.php ENDPATH**/ ?>