<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INSEP PRO - تسجيل الدخول</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="font-tajawal bg-gray-50">

<div x-data="loginPage()" class="min-h-screen flex">

    
    <div class="flex-1 flex items-center justify-center p-8 bg-white">
        <div class="w-full max-w-md">

            
            <a href="<?php echo e(url('/')); ?>" class="flex items-center gap-2 text-gray-500 hover:text-navy mb-8 transition-colors">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/></svg>
                <span>العودة للرئيسية</span>
            </a>

            
            <div class="flex items-center gap-3 mb-8">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg overflow-hidden">
                    <img src="<?php echo e(asset('insep-logo.png')); ?>" alt="INSEP" class="w-full h-full object-contain">
                </div>
                <div>
                    <h1 class="font-black text-navy text-xl" style="font-family: 'Roboto', sans-serif">INSEP PRO</h1>
                    <p class="text-xs text-gray-500">منصة علوم الرياضة</p>
                </div>
            </div>

            
            <h2 class="text-3xl font-black text-navy mb-2" x-text="isRegister ? 'إنشاء حساب جديد' : 'تسجيل الدخول'"></h2>
            <p class="text-gray-500 mb-8" x-text="isRegister ? 'أنشئ حسابك للوصول إلى جميع الدورات والخدمات' : 'أدخل بياناتك للوصول إلى حسابك'"></p>

            
            <?php if($errors->any()): ?>
            <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3 animate-slideDown">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <p class="text-red-700 text-sm font-medium"><?php echo e($errors->first()); ?></p>
            </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
            <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3 animate-slideDown">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <p class="text-red-700 text-sm font-medium"><?php echo e(session('error')); ?></p>
            </div>
            <?php endif; ?>

            
            <?php if(session('success')): ?>
            <div class="mb-5 bg-green-50 border border-green-200 rounded-xl p-4 flex items-start gap-3 animate-slideDown">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <p class="text-green-700 text-sm font-medium"><?php echo e(session('success')); ?></p>
            </div>
            <?php endif; ?>

            
            <div x-show="!isRegister" class="mb-6">
                <label class="text-sm font-bold text-navy mb-3 block">نوع الحساب</label>
                <div class="grid grid-cols-3 gap-3">
                    <template x-for="role in roles" :key="role.key">
                        <button type="button" @click="selectRole(role.key)"
                            :class="selectedRole === role.key ? 'border-navy bg-navy/5 text-navy' : 'border-gray-200 text-gray-400 hover:border-gray-300'"
                            class="p-3 rounded-xl border-2 transition-all duration-300 flex flex-col items-center gap-2">
                            <svg class="w-[22px] h-[22px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <template x-if="role.key === 'student'"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 3a4 4 0 100 8 4 4 0 000-8z"/></template>
                                <template x-if="role.key === 'instructor'"><g><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></g></template>
                                <template x-if="role.key === 'admin'"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></template>
                            </svg>
                            <span class="text-sm font-semibold" x-text="role.label"></span>
                        </button>
                    </template>
                </div>
            </div>

            
            <form x-show="!isRegister" method="POST" action="<?php echo e(url('/login')); ?>" class="space-y-5">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">البريد الإلكتروني</label>
                    <div class="relative">
                        <svg class="w-[18px] h-[18px] absolute right-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <input type="email" name="email" x-model="email" placeholder="example@email.com"
                            class="w-full border-2 border-gray-200 rounded-xl pr-12 pl-4 py-3.5 focus:border-navy transition-colors text-gray-700" dir="ltr" required>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">كلمة المرور</label>
                    <div class="relative">
                        <svg class="w-[18px] h-[18px] absolute right-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                        <input :type="showPassword ? 'text' : 'password'" name="password" x-model="password" placeholder="••••••••"
                            class="w-full border-2 border-gray-200 rounded-xl pr-12 pl-12 py-3.5 focus:border-navy transition-colors text-gray-700" dir="ltr" required>
                        <button type="button" @click="showPassword = !showPassword" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-navy">
                            <svg x-show="!showPassword" class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg x-show="showPassword" x-cloak class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-navy accent-[#1B2A4A]">
                        <span class="text-sm text-gray-600">تذكرني</span>
                    </label>
                    <a href="#" class="text-sm text-red-brand hover:underline font-medium">نسيت كلمة المرور؟</a>
                </div>
                <button type="submit" class="w-full bg-navy hover:bg-navy-dark text-white py-4 rounded-xl font-bold text-lg transition-all duration-300 hover:shadow-xl hover:shadow-navy/20">
                    تسجيل الدخول
                </button>
            </form>

            
            <form x-show="isRegister" x-cloak method="POST" action="<?php echo e(url('/register')); ?>" class="space-y-5">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">الاسم الكامل</label>
                    <div class="relative">
                        <svg class="w-[18px] h-[18px] absolute right-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 3a4 4 0 100 8 4 4 0 000-8z"/></svg>
                        <input type="text" name="name" placeholder="أدخل اسمك الكامل"
                            class="w-full border-2 border-gray-200 rounded-xl pr-12 pl-4 py-3.5 focus:border-navy transition-colors text-gray-700" required>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">رقم الجوال</label>
                    <div class="relative">
                        <svg class="w-[18px] h-[18px] absolute right-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                        <input type="tel" name="phone" placeholder="+966 5X XXX XXXX"
                            class="w-full border-2 border-gray-200 rounded-xl pr-12 pl-4 py-3.5 focus:border-navy transition-colors text-gray-700" dir="ltr">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">البريد الإلكتروني</label>
                    <div class="relative">
                        <svg class="w-[18px] h-[18px] absolute right-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <input type="email" name="email" placeholder="example@email.com"
                            class="w-full border-2 border-gray-200 rounded-xl pr-12 pl-4 py-3.5 focus:border-navy transition-colors text-gray-700" dir="ltr" required>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold text-navy mb-2 block">كلمة المرور</label>
                    <div class="relative">
                        <svg class="w-[18px] h-[18px] absolute right-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                        <input :type="showPassword ? 'text' : 'password'" name="password" placeholder="••••••••"
                            class="w-full border-2 border-gray-200 rounded-xl pr-12 pl-12 py-3.5 focus:border-navy transition-colors text-gray-700" dir="ltr" required>
                        <button type="button" @click="showPassword = !showPassword" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-navy">
                            <svg x-show="!showPassword" class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg x-show="showPassword" x-cloak class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                </div>
                <button type="submit" class="w-full bg-navy hover:bg-navy-dark text-white py-4 rounded-xl font-bold text-lg transition-all duration-300 hover:shadow-xl hover:shadow-navy/20">
                    إنشاء الحساب
                </button>
            </form>

            
            <div class="mt-6 text-center">
                <span class="text-gray-500" x-text="isRegister ? 'لديك حساب بالفعل؟' : 'ليس لديك حساب؟'"></span>
                <button @click="isRegister = !isRegister" class="text-red-brand font-bold hover:underline mr-1" x-text="isRegister ? 'تسجيل الدخول' : 'سجل الآن'"></button>
            </div>

            
            <div x-show="!isRegister" class="mt-6 p-5 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-200">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <p class="text-sm text-blue-800 font-bold">بيانات الدخول التجريبية</p>
                </div>
                <div class="space-y-2">
                    
                    <button type="button" @click="fillCredentials('admin')"
                        class="w-full flex items-center justify-between bg-white/80 hover:bg-white rounded-xl px-4 py-2.5 text-sm transition-all border border-blue-100 hover:border-blue-300 group">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-red-brand/10 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-red-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-navy block text-xs">المدير</span>
                                <span class="text-gray-400 text-[11px]" style="font-family: 'Roboto', sans-serif">admin@insep.net</span>
                            </div>
                        </div>
                        <span class="text-blue-600 text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity">تعبئة ←</span>
                    </button>
                    
                    <button type="button" @click="fillCredentials('instructor')"
                        class="w-full flex items-center justify-between bg-white/80 hover:bg-white rounded-xl px-4 py-2.5 text-sm transition-all border border-blue-100 hover:border-blue-300 group">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-navy/10 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-navy" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-navy block text-xs">المدرب</span>
                                <span class="text-gray-400 text-[11px]" style="font-family: 'Roboto', sans-serif">instructor@insep.net</span>
                            </div>
                        </div>
                        <span class="text-blue-600 text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity">تعبئة ←</span>
                    </button>
                    
                    <button type="button" @click="fillCredentials('student')"
                        class="w-full flex items-center justify-between bg-white/80 hover:bg-white rounded-xl px-4 py-2.5 text-sm transition-all border border-blue-100 hover:border-blue-300 group">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-green-500/10 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 3a4 4 0 100 8 4 4 0 000-8z"/></svg>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-navy block text-xs">الطالب</span>
                                <span class="text-gray-400 text-[11px]" style="font-family: 'Roboto', sans-serif">student@insep.net</span>
                            </div>
                        </div>
                        <span class="text-blue-600 text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity">تعبئة ←</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    
    <div class="hidden lg:flex flex-1 relative bg-gradient-to-br from-navy via-navy-light to-navy-dark items-center justify-center overflow-hidden">
        <div class="absolute inset-0 hero-pattern opacity-30"></div>
        <div class="absolute top-20 left-20 w-60 h-60 bg-red-brand/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-20 w-80 h-80 bg-white/5 rounded-full blur-3xl"></div>

        <div class="relative z-10 text-center p-12 max-w-lg">
            <div class="w-24 h-24 bg-white/10 backdrop-blur-sm rounded-3xl flex items-center justify-center mx-auto mb-8 border border-white/20 animate-float overflow-hidden">
                <img src="<?php echo e(asset('insep-logo.png')); ?>" alt="INSEP" class="w-20 h-20 object-contain">
            </div>
            <h2 class="text-4xl font-black text-white mb-4">INSEP PRO</h2>
            <p class="text-white/70 text-lg leading-relaxed mb-8">
                المنصة التعليمية والإدارية المتكاملة لعلوم الرياضة في الشرق الأوسط
            </p>
            <div class="grid grid-cols-3 gap-4">
                <?php $__currentLoopData = [['num' => '+20K', 'label' => 'متدرب'], ['num' => '+5K', 'label' => 'دورة'], ['num' => '+150', 'label' => 'مدرب']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/10">
                    <div class="text-2xl font-black text-white" style="font-family: 'Roboto', sans-serif"><?php echo e($stat['num']); ?></div>
                    <div class="text-white/60 text-sm"><?php echo e($stat['label']); ?></div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <div class="mt-10 inline-flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-xl px-5 py-3 border border-white/15">
                <svg class="w-[18px] h-[18px] text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <div class="text-right">
                    <p class="text-white text-sm font-bold">نظام آمن ومشفّر</p>
                    <p class="text-white/50 text-xs">حماية CSRF &amp; XSS &amp; SQL Injection</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function loginPage() {
    return {
        isRegister: <?php echo e(isset($startRegister) && $startRegister ? 'true' : 'false'); ?>,
        showPassword: false,
        selectedRole: 'admin',
        email: '',
        password: '',
        roles: [
            { key: 'student', label: 'طالب' },
            { key: 'instructor', label: 'مدرب' },
            { key: 'admin', label: 'مدير' },
        ],
        accounts: {
            admin: { email: 'admin@insep.net', password: '654321@' },
            instructor: { email: 'instructor@insep.net', password: 'instructor123' },
            student: { email: 'student@insep.net', password: 'student123' },
        },
        selectRole(key) {
            this.selectedRole = key;
        },
        fillCredentials(role) {
            const account = this.accounts[role];
            this.email = account.email;
            this.password = account.password;
            this.selectedRole = role;
        }
    };
}
</script>

</body>
</html>
<?php /**PATH H:\insep\insep\resources\views/auth/login.blade.php ENDPATH**/ ?>