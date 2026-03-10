<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>" dir="<?php echo e(app()->getLocale() === 'ar' ? 'rtl' : 'ltr'); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'INSEP PRO - معهد علوم الرياضة'); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', 'INSEP PRO - المنصة التعليمية والإدارية المتكاملة لعلوم الرياضة في الشرق الأوسط'); ?>">
    <link
        href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&family=Roboto:wght@400;500;700;900&display=swap"
        rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>

<body class="min-h-screen flex flex-col" style="font-family: 'Tajawal', sans-serif">
    <?php $lang = app()->getLocale(); ?>

    <?php if(!isset($hideLayout) || !$hideLayout): ?>
        <?php echo $__env->make('partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

    <main class="flex-1">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php if(!isset($hideLayout) || !$hideLayout): ?>
        <?php echo $__env->make('partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH H:\insep\backend-laravel\resources\views/layouts/app.blade.php ENDPATH**/ ?>