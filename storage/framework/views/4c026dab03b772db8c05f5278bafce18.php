
<?php $__env->startSection('title', 'INSEP PRO - اتصل بنا'); ?>

<?php $__env->startSection('content'); ?>

<section class="bg-gradient-to-br from-navy via-navy-light to-navy-dark py-20 relative overflow-hidden">
    <div class="absolute inset-0 hero-pattern opacity-30"></div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <span class="inline-block bg-white/10 text-white px-4 py-1.5 rounded-full text-sm font-bold mb-4 border border-white/20">نسعد بتواصلكم</span>
        <h1 class="text-4xl md:text-5xl font-black text-white mb-4">اتصل بنا</h1>
        <p class="text-white/70 text-lg max-w-2xl mx-auto">نحن هنا لمساعدتك. تواصل معنا لأي استفسار أو اقتراح</p>
    </div>
</section>

<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="space-y-6">
                <div>
                    <h2 class="text-2xl font-black text-navy mb-6">معلومات التواصل</h2>
                    <p class="text-gray-600 mb-8">يمكنك التواصل معنا من خلال أي من الطرق التالية أو عبر النموذج المرفق</p>
                </div>
                <?php $__currentLoopData = [
                    ['title' => 'العنوان', 'info' => '١٣ الخليفة المأمون، روكسي، مصر الجديدة', 'sub' => 'محافظة القاهرة 11757، مصر', 'color' => 'bg-blue-50 text-blue-600'],
                    ['title' => 'الهاتف', 'info' => '+20 10 33330027', 'sub' => 'تواصل معنا عبر واتساب', 'color' => 'bg-green-50 text-green-600'],
                    ['title' => 'البريد الإلكتروني', 'info' => 'info@insep.net', 'sub' => 'support@insep.net', 'color' => 'bg-purple-50 text-purple-600'],
                    ['title' => 'ساعات العمل', 'info' => 'الأحد - الخميس: 9:00 ص - 5:00 م', 'sub' => 'الجمعة والسبت: إجازة', 'color' => 'bg-orange-50 text-orange-600'],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-start gap-4 bg-white rounded-2xl p-5 border border-gray-100 card-hover">
                    <div class="w-12 h-12 rounded-xl <?php echo e($item['color']); ?> flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-navy mb-1"><?php echo e($item['title']); ?></h3>
                        <p class="text-gray-600 text-sm"><?php echo e($item['info']); ?></p>
                        <p class="text-gray-400 text-sm"><?php echo e($item['sub']); ?></p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                    <h2 class="text-2xl font-black text-navy mb-2">أرسل رسالة</h2>
                    <p class="text-gray-500 mb-8">املأ النموذج التالي وسنرد عليك في أقرب وقت ممكن</p>

                    <?php if(session('success')): ?>
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 flex items-center gap-3 animate-slideDown">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-green-700 font-medium"><?php echo e(session('success')); ?></span>
                    </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('contact.submit')); ?>" class="space-y-5">
                        <?php echo csrf_field(); ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="text-sm font-bold text-navy mb-2 block">الاسم الكامل *</label>
                                <input name="name" type="text" placeholder="أدخل اسمك" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5 transition-colors" required value="<?php echo e(old('name')); ?>">
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div>
                                <label class="text-sm font-bold text-navy mb-2 block">البريد الإلكتروني *</label>
                                <input name="email" type="email" placeholder="example@email.com" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5 transition-colors" dir="ltr" required value="<?php echo e(old('email')); ?>">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="text-sm font-bold text-navy mb-2 block">رقم الجوال</label>
                                <input name="phone" type="tel" placeholder="+966 5X XXX XXXX" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5 transition-colors" dir="ltr" value="<?php echo e(old('phone')); ?>">
                            </div>
                            <div>
                                <label class="text-sm font-bold text-navy mb-2 block">الموضوع *</label>
                                <select name="subject" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5 transition-colors text-gray-600" required>
                                    <option value="">اختر الموضوع</option>
                                    <option>استفسار عام</option>
                                    <option>استفسار عن الدورات</option>
                                    <option>دعم فني</option>
                                    <option>شراكات وتعاون</option>
                                    <option>شكوى أو اقتراح</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-bold text-navy mb-2 block">الرسالة *</label>
                            <textarea name="message" rows="5" placeholder="اكتب رسالتك هنا..." class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5 transition-colors resize-none" required><?php echo e(old('message')); ?></textarea>
                        </div>
                        <button type="submit" class="bg-navy hover:bg-navy-dark text-white px-8 py-4 rounded-xl font-bold text-lg transition-all duration-300 flex items-center gap-2 hover:shadow-xl hover:shadow-navy/20">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                            إرسال الرسالة
                        </button>
                    </form>
                </div>
            </div>
        </div>

        
        <div class="mt-12 bg-gradient-to-br from-navy/5 to-navy/10 rounded-2xl h-80 flex items-center justify-center border border-gray-200">
            <div class="text-center">
                <svg class="w-12 h-12 text-navy/30 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <p class="text-gray-400 font-medium">خريطة الموقع</p>
                <p class="text-gray-300 text-sm">١٣ الخليفة المأمون، روكسي، مصر الجديدة، القاهرة</p>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\insep\backend-laravel\resources\views/pages/contact.blade.php ENDPATH**/ ?>