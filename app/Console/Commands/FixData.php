<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Course;

class FixData extends Command
{
    protected $signature   = 'fix:data';
    protected $description = 'Fix admin account password and course images/categories';

    public function handle(): int
    {
        // ── 1. Admin account ────────────────────────────────────────────────
        $admin = User::where('email', 'admin@insep.net')->first();

        if ($admin) {
            $admin->update([
                'password' => Hash::make('654321@'),
                'role'     => 'admin',
                'status'   => 'active',
            ]);
            $this->info('✅ Admin password reset to: 654321@');
        } else {
            User::create([
                'name'     => 'مدير النظام',
                'email'    => 'admin@insep.net',
                'password' => Hash::make('654321@'),
                'role'     => 'admin',
                'phone'    => '+20 10 33330027',
                'status'   => 'active',
            ]);
            $this->info('✅ Admin account created (email: admin@insep.net  password: 654321@)');
        }

        // ── 2. Course images & categories ────────────────────────────────────
        $fixes = [
            'دبلوم التدريب الرياضي المتقدم'       => ['category' => 'التدريب الرياضي',   'image' => 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?auto=format&fit=crop&w=800&q=80'],
            'أساسيات العلاج الطبيعي الرياضي'      => ['category' => 'العلاج الطبيعي',    'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=800&q=80'],
            'التغذية الرياضية للمحترفين'           => ['category' => 'التغذية الرياضية', 'image' => 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?auto=format&fit=crop&w=800&q=80'],
            'إدارة الأندية والمنشآت الرياضية'     => ['category' => 'الإدارة الرياضية', 'image' => 'https://images.unsplash.com/photo-1476480862126-209bfaa8edc8?auto=format&fit=crop&w=800&q=80'],
            'علم وظائف الأعضاء في الرياضة'        => ['category' => 'التدريب الرياضي',   'image' => 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?auto=format&fit=crop&w=800&q=80'],
            'الإصابات الرياضية والتأهيل'           => ['category' => 'العلاج الطبيعي',    'image' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=800&q=80'],
            'التحليل الرياضي باستخدام التقنية'    => ['category' => 'التدريب الرياضي',   'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80'],
            'سيكولوجية الرياضة والتحفيز'           => ['category' => 'التدريب الرياضي',   'image' => 'https://images.unsplash.com/photo-1544367563-12123d8965cd?auto=format&fit=crop&w=800&q=80'],
            'المكملات الغذائية للرياضيين'         => ['category' => 'التغذية الرياضية', 'image' => 'https://images.unsplash.com/photo-1593095948071-474c5cc2989d?auto=format&fit=crop&w=800&q=80'],
        ];

        $updated = 0;
        foreach (Course::all() as $course) {
            if (isset($fixes[$course->title])) {
                $course->update($fixes[$course->title]);
                $updated++;
                $this->line("  ✔ Fixed: {$course->title}");
            }
        }

        // Fallback: fix ALL courses that still have wrong category
        $fallbackFixed = Course::where('category', 'كرة القدم')
            ->orWhere('category', '')
            ->orWhereNull('category')
            ->count();

        if ($fallbackFixed > 0) {
            $this->warn("⚠️  {$fallbackFixed} courses still have wrong category — updating with generic sports images.");
            $sportImages = [
                'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80',
            ];
            $i = 0;
            foreach (Course::where('category', 'كرة القدم')->orWhere('category', '')->orWhereNull('category')->get() as $c) {
                $c->update([
                    'category' => 'التدريب الرياضي',
                    'image'    => $sportImages[$i % count($sportImages)],
                ]);
                $i++;
                $updated++;
            }
        }

        $this->info("✅ Updated {$updated} course(s).");
        $this->newLine();
        $this->info('Done. Login at /login with admin@insep.net / 654321@');

        return self::SUCCESS;
    }
}
