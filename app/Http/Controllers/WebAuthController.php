<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\User;

class WebAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login', ['hideLayout' => true]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة',
        ])->withInput($request->only('email'));
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login', ['hideLayout' => true, 'startRegister' => true]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone' => 'nullable|string',
        ]);

        $user = User::create([
            'name'    => $request->name_en,
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'email'   => $request->email,
            'password' => Hash::make($request->password),
            'phone'   => $request->phone,
            'role'    => 'student',
            'status'  => 'active',
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    /*
    |------------------------------------------------------------------
    | Forgot / Reset password (self-service, secure reset link)
    |------------------------------------------------------------------
    */
    public function showForgotForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.forgot-password', ['hideLayout' => true]);
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Send the reset link (ignored silently if the email doesn't exist).
        Password::sendResetLink($request->only('email'));

        // Always show the same generic message to avoid account enumeration.
        return back()->with('success', 'إن وُجد حساب بهذا البريد، فقد أرسلنا إليه رابط إعادة تعيين كلمة المرور. تحقق من بريدك (ومجلد الرسائل غير المرغوبة).');
    }

    public function showResetForm(Request $request, string $token)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.reset-password', [
            'hideLayout' => true,
            'token'      => $token,
            'email'      => $request->query('email', ''),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'تم تغيير كلمة المرور بنجاح. يمكنك تسجيل الدخول الآن.');
        }

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'تعذّر إعادة تعيين كلمة المرور. قد يكون الرابط منتهي الصلاحية أو غير صالح، أعد المحاولة.');
    }

    public function showSetup()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.setup', ['hideLayout' => true]);
    }

    public function processSetup(Request $request)
    {
        $ownerEmail = env('OWNER_EMAIL', '');

        if (empty($ownerEmail)) {
            return back()->with('error', 'لم يتم تحديد بريد المالك في إعدادات النظام');
        }

        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($request->email !== $ownerEmail) {
            return back()->with('error', 'البريد الإلكتروني غير مخوّل لاستخدام هذه الصفحة')->withInput($request->only('email'));
        }

        $user = User::updateOrCreate(
            ['email' => $ownerEmail],
            [
                'name'     => 'Mohamed Maged',
                'name_ar'  => 'محمد ماجد',
                'name_en'  => 'Mohamed Maged',
                'password' => Hash::make($request->password),
                'role'     => 'super_admin',
                'status'   => 'active',
            ]
        );

        return redirect()->route('login')->with('success', 'تم تحديث كلمة المرور بنجاح. يمكنك تسجيل الدخول الآن.');
    }
}
