<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->role) $query->where('role', $request->role);
        if ($request->status) $query->where('status', $request->status);
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->get()->makeHidden('password');
        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'المستخدم غير موجود'], 404);
        return response()->json($user->makeHidden('password'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $user = User::create($data);
        return response()->json($user->makeHidden('password'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        User::where('id', $id)->update($data);
        $user = User::find($id);
        return response()->json($user->makeHidden('password'));
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'المستخدم غير موجود'], 404);
        $userData = $user->makeHidden('password')->toArray();
        $user->delete();
        return response()->json($userData);
    }
}
