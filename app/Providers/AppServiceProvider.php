<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::defaultView('pagination::tailwind');
        Paginator::defaultSimpleView('pagination::simple-tailwind');

        // Arabic password-reset email (forgot-password feature).
        ResetPassword::toMailUsing(function ($notifiable, string $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            $minutes = config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60);

            return (new MailMessage)
                ->subject('إعادة تعيين كلمة المرور - INSEP PRO')
                ->greeting('مرحباً')
                ->line('لقد تلقينا طلباً لإعادة تعيين كلمة المرور الخاصة بحسابك.')
                ->action('إعادة تعيين كلمة المرور', $url)
                ->line("صلاحية هذا الرابط تنتهي خلال {$minutes} دقيقة.")
                ->line('إذا لم تطلب إعادة التعيين، فلا داعي لاتخاذ أي إجراء.')
                ->salutation('تحياتنا، فريق INSEP PRO');
        });
    }
}
