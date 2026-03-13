
<?php $__env->startSection('title', 'INSEP PRO - الإشعارات'); ?>
<?php $__env->startSection('dashboard-content'); ?>
<h1 class="text-2xl font-black text-navy mb-6">الإشعارات</h1>
<div class="space-y-3">
    <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="bg-white rounded-2xl p-5 border border-gray-100 card-hover flex items-start gap-4">
        <div class="w-10 h-10 rounded-xl <?php echo e($notif->read_at ? 'bg-gray-100' : 'bg-red-brand/10'); ?> flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 <?php echo e($notif->read_at ? 'text-gray-400' : 'text-red-brand'); ?>" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
        </div>
        <div class="flex-1">
            <h3 class="font-bold text-navy text-sm <?php echo e($notif->read_at ? '' : 'text-red-brand'); ?>"><?php echo e($notif->title); ?></h3>
            <p class="text-sm text-gray-500 mt-1"><?php echo e($notif->body ?? $notif->message ?? ''); ?></p>
            <p class="text-xs text-gray-400 mt-2"><?php echo e($notif->created_at?->diffForHumans()); ?></p>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="text-center py-12 text-gray-400">لا يوجد إشعارات</div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\insep\insep\resources\views/dashboard/notifications.blade.php ENDPATH**/ ?>