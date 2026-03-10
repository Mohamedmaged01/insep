<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة'], 401);
        }

        $token = JWTAuth::fromUser($user);
        $userData = $user->makeHidden('password')->toArray();

        return response()->json(['token' => $token, 'user' => $userData]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'student',
        ]);

        $token = JWTAuth::fromUser($user);
        $userData = $user->makeHidden('password')->toArray();

        return response()->json(['token' => $token, 'user' => $userData]);
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        if (!$user) return response()->json(['message' => 'Unauthenticated'], 401);

        return response()->json($user->makeHidden('password'));
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $data = $request->except(['password', 'role']);
        $user->update($data);

        return response()->json($user->fresh()->makeHidden('password'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'oldPassword' => 'required',
            'newPassword' => 'required|min:6',
        ]);

        $user = $request->user();

        if (!Hash::check($request->oldPassword, $user->password)) {
            return response()->json(['message' => 'كلمة المرور الحالية غير صحيحة'], 401);
        }

        $user->update(['password' => Hash::make($request->newPassword)]);

        return response()->json(['message' => 'تم تغيير كلمة المرور بنجاح']);
    }
}
