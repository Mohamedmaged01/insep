<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name_ar', 'like', "%{$request->search}%")
                  ->orWhere('name_en', 'like', "%{$request->search}%");
            });
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    public function show($id)
    {
        $dept = Department::find($id);
        if (!$dept) return response()->json(['message' => 'القسم غير موجود'], 404);
        return response()->json($dept);
    }

    public function store(Request $request)
    {
        return response()->json(Department::create($request->all()));
    }

    public function update(Request $request, $id)
    {
        Department::where('id', $id)->update($request->all());
        return response()->json(Department::find($id));
    }

    public function destroy($id)
    {
        $dept = Department::find($id);
        if (!$dept) return response()->json(['message' => 'القسم غير موجود'], 404);
        $deptData = $dept->toArray();
        $dept->delete();
        return response()->json($deptData);
    }
}
