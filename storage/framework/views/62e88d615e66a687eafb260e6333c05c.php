
<?php $__env->startSection('title', 'INSEP PRO - الإعدادات'); ?>
<?php $__env->startSection('dashboard-content'); ?>
<?php $user = auth()->user(); ?>
<h1 class="text-2xl font-black text-navy mb-6">الإعدادات والملف الشخصي</h1>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <div class="bg-white rounded-2xl p-8 border border-gray-100 text-center">
        <div class="w-24 h-24 mx-auto mb-4 bg-gradient-to-br from-navy to-navy-light rounded-2xl flex items-center justify-center text-white font-black text-3xl shadow-xl"><?php echo e(mb_substr($user->name, 0, 1)); ?></div>
        <h2 class="text-xl font-black text-navy"><?php echo e($user->name); ?></h2>
        <p class="text-sm text-gray-500" style="font-family: 'Roboto', sans-serif"><?php echo e($user->email); ?></p>
        <span class="inline-block mt-3 px-4 py-1.5 rounded-xl text-xs font-bold bg-navy/10 text-navy"><?php echo e($user->role === 'admin' ? 'مدير النظام' : ($user->role === 'student' ? 'طالب' : 'مدرب')); ?></span>
    </div>

    
    <div class="lg:col-span-2 bg-white rounded-2xl p-8 border border-gray-100">
        <h3 class="text-lg font-bold text-navy mb-6">تعديل المعلومات الشخصية</h3>
        <form class="space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الاسم الكامل</label>
                    <input type="text" value="<?php echo e($user->name); ?>" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5">
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">البريد الإلكتروني</label>
                    <input type="email" value="<?php echo e($user->email); ?>" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5" dir="ltr">
                </div>
            </div>
            <div>
                <label class="text-sm font-bold text-navy mb-2 block">رقم الجوال</label>
                <input type="tel" value="<?php echo e($user->phone ?? ''); ?>" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5" dir="ltr">
            </div>
            <div>
                <label class="text-sm font-bold text-navy mb-2 block">كلمة المرور الجديدة (اختياري)</label>
                <input type="password" placeholder="اتركه فارغاً إذا لم ترد التغيير" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5" dir="ltr">
            </div>
            <button type="button" class="bg-navy hover:bg-navy-dark text-white px-8 py-3.5 rounded-xl font-bold transition-all hover:shadow-xl">حفظ التغييرات</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\insep\insep\resources\views/dashboard/settings.blade.php ENDPATH**/ ?>