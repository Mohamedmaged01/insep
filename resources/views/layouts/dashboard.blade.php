@php
    $lang = app()->getLocale();
    $user = auth()->user();
    $isAdmin = $user && $user->role === 'admin';
    $isStudent = $user && $user->role === 'student';
    $isInstructor = $user && $user->role === 'instructor';
    $isAr = $lang === 'ar';
    $roleName = $isAdmin ? ($isAr ? 'مدير النظام' : 'System Admin') : ($isStudent ? ($isAr ? 'طالب' : 'Student') : ($isAr ? 'مدرب' : 'Trainer'));
    $userName = $user->name ?? ($isAr ? 'مستخدم' : 'User');
    $userEmail = $user->email ?? '';

    $adminMenu = [
        ['key' => 'home',         'label' => $isAr ? 'الرئيسية'           : 'Home',           'icon' => 'home',           'route' => 'dashboard'],
        ['key' => 'students',     'label' => $isAr ? 'إدارة الطلاب'       : 'Students',        'icon' => 'users',          'route' => 'dashboard.students'],
        ['key' => 'instructors',  'label' => $isAr ? 'إدارة المدربين'     : 'Trainers',        'icon' => 'graduation-cap', 'route' => 'dashboard.instructors'],
        ['key' => 'sections',     'label' => $isAr ? 'الشعب التدريبية'    : 'Sections',        'icon' => 'grid',           'route' => 'dashboard.sections'],
        ['key' => 'courses',      'label' => $isAr ? 'إدارة الدورات'      : 'Courses',         'icon' => 'book-open',      'route' => 'dashboard.courses'],
        ['key' => 'batches',      'label' => $isAr ? 'المجموعات التدريبية': 'Batches',         'icon' => 'clipboard-list', 'route' => 'dashboard.batches'],
        ['key' => 'certificates', 'label' => $isAr ? 'الشهادات'           : 'Certificates',    'icon' => 'award',          'route' => 'dashboard.certificates'],
        ['key' => 'finance',      'label' => $isAr ? 'المالية'            : 'Finance',         'icon' => 'dollar-sign',    'route' => 'dashboard.finance'],
        ['key' => 'notifications','label' => $isAr ? 'الإشعارات'          : 'Notifications',   'icon' => 'bell',           'route' => 'dashboard.notifications'],
        ['key' => 'reports',      'label' => $isAr ? 'التقارير'           : 'Reports',         'icon' => 'bar-chart-3',    'route' => 'dashboard.reports'],
        ['key' => 'gamification', 'label' => $isAr ? 'النقاط والشارات'   : 'Points & Badges', 'icon' => 'award',          'route' => 'dashboard.gamification'],
        ['key' => 'settings',     'label' => $isAr ? 'الإعدادات'          : 'Settings',        'icon' => 'settings',       'route' => 'dashboard.settings'],
        ['key' => 'cms',          'label' => $isAr ? 'محتوى الموقع'       : 'Site Content',    'icon' => 'layout',         'route' => 'dashboard.cms'],
    ];

    $studentMenu = [
        ['key' => 'home',         'label' => $isAr ? 'الرئيسية'     : 'Home',          'icon' => 'home',        'route' => 'dashboard'],
        ['key' => 'mycourses',    'label' => $isAr ? 'دوراتي'       : 'My Courses',    'icon' => 'book-open',   'route' => 'dashboard.mycourses'],
        ['key' => 'exams',        'label' => $isAr ? 'الاختبارات'   : 'Exams',         'icon' => 'file-text',   'route' => 'dashboard.exams'],
        ['key' => 'certificates', 'label' => $isAr ? 'شهاداتي'     : 'My Certificates','icon' => 'award',       'route' => 'dashboard.certificates'],
        ['key' => 'finance',      'label' => $isAr ? 'المالية'      : 'Finance',       'icon' => 'credit-card', 'route' => 'dashboard.finance'],
        ['key' => 'notifications','label' => $isAr ? 'الإشعارات'    : 'Notifications', 'icon' => 'bell',        'route' => 'dashboard.notifications'],
        ['key' => 'gamification', 'label' => $isAr ? 'نقاطي وشاراتي': 'My Points',    'icon' => 'award',       'route' => 'dashboard.gamification'],
        ['key' => 'settings',     'label' => $isAr ? 'الملف الشخصي' : 'Profile',       'icon' => 'settings',    'route' => 'dashboard.settings'],
    ];

    $instructorMenu = [
        ['key' => 'home',         'label' => $isAr ? 'الرئيسية'     : 'Home',          'icon' => 'home',           'route' => 'dashboard'],
        ['key' => 'mybatches',    'label' => $isAr ? 'مجموعاتي'    : 'My Batches',    'icon' => 'clipboard-list', 'route' => 'dashboard.batches'],
        ['key' => 'notifications','label' => $isAr ? 'الإشعارات'    : 'Notifications', 'icon' => 'bell',           'route' => 'dashboard.notifications'],
        ['key' => 'settings',     'label' => $isAr ? 'الملف الشخصي' : 'Profile',       'icon' => 'settings',       'route' => 'dashboard.settings'],
    ];

    $menuItems = $isAdmin ? $adminMenu : ($isStudent ? $studentMenu : $instructorMenu);
    $currentRoute = request()->route()->getName() ?? 'dashboard';
@endphp
<!DOCTYPE html>
<html lang="{{ $lang }}" dir="{{ $lang === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'INSEP PRO - لوحة التحكم')</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&family=Roboto:wght@400;500;700;900&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50" style="font-family: 'Tajawal', sans-serif" x-data="{ sidebarOpen: true, mobileSidebarOpen: false }">
    <div class="min-h-screen flex">
        {{-- Mobile Sidebar Overlay --}}
        <div x-show="mobileSidebarOpen" x-cloak @click="mobileSidebarOpen = false"
            class="fixed inset-0 bg-black/50 z-40 lg:hidden" x-transition.opacity></div>

        {{-- Sidebar --}}
        <aside
            :class="[sidebarOpen ? 'w-64' : 'w-20', mobileSidebarOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0']"
            class="fixed lg:static inset-y-0 right-0 z-50 bg-navy text-white transition-all duration-300 flex flex-col">
            {{-- Logo --}}
            <div class="p-4 border-b border-white/10 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden">
                    <img src="/insep-logo.png" alt="INSEP" class="w-full h-full object-contain">
                </div>
                <div x-show="sidebarOpen" x-cloak>
                    <h1 class="font-black text-sm" style="font-family: 'Roboto', sans-serif">INSEP PRO</h1>
                    <p class="text-[10px] text-white/50">{{ $roleName }}</p>
                </div>
                <button @click="mobileSidebarOpen = false" class="lg:hidden mr-auto">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Menu --}}
            <nav class="flex-1 py-4 px-2 space-y-1 overflow-y-auto">
                @foreach ($menuItems as $item)
                    <a href="{{ route($item['route']) }}"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200
                              {{ $currentRoute === $item['route'] ? 'bg-red-brand text-white shadow-lg shadow-red-brand/30' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            @switch($item['icon'])
                                @case('home')
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                @break

                                @case('users')
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                @break

                                @case('graduation-cap')
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                @break

                                @case('grid')
                                    <rect x="3" y="3" width="7" height="7" rx="1" />
                                    <rect x="14" y="3" width="7" height="7" rx="1" />
                                    <rect x="3" y="14" width="7" height="7" rx="1" />
                                    <rect x="14" y="14" width="7" height="7" rx="1" />
                                @break

                                @case('book-open')
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                @break

                                @case('clipboard-list')
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                @break

                                @case('user-check')
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z M16 11l2 2 4-4" />
                                @break

                                @case('video')
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                @break

                                @case('radio')
                                    <circle cx="12" cy="12" r="2" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.24 7.76a6 6 0 010 8.49m-8.48-.01a6 6 0 010-8.49m11.31-2.82a10 10 0 010 14.14m-14.14 0a10 10 0 010-14.14" />
                                @break

                                @case('file-text')
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                @break

                                @case('award')
                                    <circle cx="12" cy="8" r="7" />
                                    <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88" />
                                @break

                                @case('dollar-sign')
                                    <line x1="12" y1="1" x2="12" y2="23" />
                                    <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" />
                                @break

                                @case('credit-card')
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2" />
                                    <line x1="1" y1="10" x2="23" y2="10" />
                                @break

                                @case('bell')
                                    <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0" />
                                @break

                                @case('layers')
                                    <polygon points="12 2 2 7 12 12 22 7 12 2" />
                                    <polyline points="2 17 12 22 22 17" />
                                    <polyline points="2 12 12 17 22 12" />
                                @break

                                @case('bar-chart-3')
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 20V10M18 20V4M6 20v-4" />
                                @break

                                @case('settings')
                                    <circle cx="12" cy="12" r="3" />
                                    <path
                                        d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z" />
                                @break

                                @case('briefcase')
                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2" />
                                    <path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16" />
                                @break

                                @case('layout')
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                    <line x1="3" y1="9" x2="21" y2="9" />
                                    <line x1="9" y1="21" x2="9" y2="9" />
                                @break
                            @endswitch
                        </svg>
                        <div x-show="sidebarOpen" class="flex-1 flex justify-between items-center" x-cloak>
                            <span class="font-medium text-sm">{{ $item['label'] }}</span>
                        </div>
                    </a>
                @endforeach
            </nav>

            {{-- Bottom --}}
            <div class="p-3 border-t border-white/10 space-y-1">
                <a href="{{ route('home') }}"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/60 hover:bg-white/10 hover:text-white transition-all">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span x-show="sidebarOpen" class="font-medium text-sm" x-cloak>{{ $isAr ? 'الموقع الرئيسي' : 'Main Website' }}</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-red-brand-light hover:bg-red-brand/20 transition-all">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span x-show="sidebarOpen" class="font-medium text-sm" x-cloak>{{ $isAr ? 'تسجيل خروج' : 'Sign Out' }}</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-h-screen overflow-hidden">
            {{-- Top Bar --}}
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 flex-shrink-0">
                <div class="flex items-center gap-4">
                    <button @click="mobileSidebarOpen = true" class="lg:hidden">
                        <svg class="w-5 h-5 text-navy" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <button @click="sidebarOpen = !sidebarOpen" class="hidden lg:block">
                        <svg class="w-5 h-5 text-navy" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="relative hidden sm:block">
                        <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8" />
                            <path stroke-linecap="round" d="M21 21l-4.35-4.35" />
                        </svg>
                        <input type="text" placeholder="{{ $isAr ? 'بحث...' : 'Search...' }}"
                            class="bg-gray-50 border border-gray-200 rounded-xl pr-10 pl-4 py-2 text-sm w-64 focus:border-navy">
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    {{-- Language Toggle --}}
                    @php $currentLang = app()->getLocale(); @endphp
                    <a href="{{ route('dashboard.locale', $currentLang === 'ar' ? 'en' : 'ar') }}"
                        class="flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-bold text-gray-600 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                        {{ $currentLang === 'ar' ? 'EN' : 'عر' }}
                    </a>
                    @php
                        $unreadCount = \App\Models\Notification::where('user_id', $user->id)->where('is_read', false)->count();
                    @endphp
                    <a href="{{ route('dashboard.notifications') }}"
                        class="relative p-2 hover:bg-gray-100 rounded-xl transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0" />
                        </svg>
                        @if($unreadCount > 0)
                        <span class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center leading-none">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                        @endif
                    </a>
                    <div class="flex items-center gap-3 pr-3 border-r border-gray-200">
                        <div class="text-right">
                            <p class="text-sm font-bold text-navy">{{ $userName }}</p>
                            <p class="text-xs text-gray-500" style="font-family: 'Roboto', sans-serif">
                                {{ $userEmail }}</p>
                        </div>
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-navy to-navy-light rounded-xl flex items-center justify-center text-white font-bold text-sm">
                            {{ mb_substr($userName, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 p-6 overflow-y-auto">
                @yield('dashboard-content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
