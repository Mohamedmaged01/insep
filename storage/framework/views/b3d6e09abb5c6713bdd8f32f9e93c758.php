
<?php $__env->startSection('title', 'INSEP PRO - لوحة التحكم'); ?>

<?php $__env->startSection('dashboard-content'); ?>
<?php $user = auth()->user(); ?>


<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="bg-white rounded-2xl p-6 border border-gray-100 card-hover">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br <?php echo e($stat['color']); ?> flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <?php switch($stat['icon']):
                        case ('users'): ?> <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/> <?php break; ?>
                        <?php case ('book'): ?> <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/> <?php break; ?>
                        <?php case ('dollar'): ?> <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/> <?php break; ?>
                        <?php case ('award'): ?> <circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/> <?php break; ?>
                        <?php case ('check'): ?> <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/> <?php break; ?>
                        <?php case ('star'): ?> <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/> <?php break; ?>
                        <?php case ('clipboard'): ?> <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/> <?php break; ?>
                        <?php case ('clock'): ?> <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/> <?php break; ?>
                    <?php endswitch; ?>
                </svg>
            </div>
        </div>
        <p class="text-gray-500 text-sm mb-1"><?php echo e($stat['label']); ?></p>
        <p class="text-2xl font-black text-navy" style="font-family: 'Roboto', sans-serif"><?php echo e(is_numeric($stat['value']) ? number_format($stat['value']) : $stat['value']); ?></p>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-2 bg-gradient-to-br from-navy to-navy-light rounded-2xl p-8 text-white relative overflow-hidden">
        <div class="absolute inset-0 hero-pattern opacity-20"></div>
        <div class="relative z-10">
            <h2 class="text-2xl font-black mb-2">مرحباً <?php echo e($user->name); ?> 👋</h2>
            <p class="text-white/70 mb-6">مرحباً بك في لوحة التحكم الخاصة بك. لديك نظرة عامة على أهم الأرقام والأنشطة.</p>
            <div class="flex flex-wrap gap-3">
                <?php if($user->role === 'admin'): ?>
                <a href="<?php echo e(route('dashboard.students')); ?>" class="bg-white/10 hover:bg-white/20 px-4 py-2 rounded-xl text-sm font-bold transition-colors border border-white/20">إدارة الطلاب</a>
                <a href="<?php echo e(route('dashboard.courses')); ?>" class="bg-white/10 hover:bg-white/20 px-4 py-2 rounded-xl text-sm font-bold transition-colors border border-white/20">إدارة الدورات</a>
                <a href="<?php echo e(route('dashboard.reports')); ?>" class="bg-red-brand hover:bg-red-brand-dark px-4 py-2 rounded-xl text-sm font-bold transition-colors">التقارير</a>
                <?php elseif($user->role === 'student'): ?>
                <a href="<?php echo e(route('dashboard.mycourses')); ?>" class="bg-white/10 hover:bg-white/20 px-4 py-2 rounded-xl text-sm font-bold transition-colors border border-white/20">دوراتي</a>
                <a href="<?php echo e(route('dashboard.certificates')); ?>" class="bg-white/10 hover:bg-white/20 px-4 py-2 rounded-xl text-sm font-bold transition-colors border border-white/20">شهاداتي</a>
                <?php else: ?>
                <a href="<?php echo e(route('dashboard.batches')); ?>" class="bg-white/10 hover:bg-white/20 px-4 py-2 rounded-xl text-sm font-bold transition-colors border border-white/20">مجموعاتي</a>
                <a href="<?php echo e(route('dashboard.attendance')); ?>" class="bg-white/10 hover:bg-white/20 px-4 py-2 rounded-xl text-sm font-bold transition-colors border border-white/20">الحضور والغياب</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl p-6 border border-gray-100">
        <h3 class="font-bold text-navy mb-4">روابط سريعة</h3>
        <div class="space-y-3">
            <a href="<?php echo e(route('dashboard.settings')); ?>" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 rounded-xl bg-navy/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-navy" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/></svg>
                </div>
                <div>
                    <p class="font-bold text-navy text-sm">الإعدادات</p>
                    <p class="text-xs text-gray-500">تعديل الملف الشخصي</p>
                </div>
            </a>
            <a href="<?php echo e(route('dashboard.notifications')); ?>" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 rounded-xl bg-red-brand/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
                </div>
                <div>
                    <p class="font-bold text-navy text-sm">الإشعارات</p>
                    <p class="text-xs text-gray-500">عرض آخر الإشعارات</p>
                </div>
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\insep\insep\resources\views/dashboard/home.blade.php ENDPATH**/ ?>