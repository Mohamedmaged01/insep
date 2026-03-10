<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query();

        if ($request->category && $request->category !== 'الكل') {
            $query->where('category', $request->category);
        }
        if ($request->level && $request->level !== 'الكل') {
            $query->where('level', $request->level);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    public function show($id)
    {
        $course = Course::with('batches')->find($id);
        if (!$course) return response()->json(['message' => 'الدورة غير موجودة'], 404);
        return response()->json($course);
    }

    public function store(Request $request)
    {
        $course = Course::create($request->all());
        return response()->json($course);
    }

    public function update(Request $request, $id)
    {
        Course::where('id', $id)->update($request->all());
        return $this->show($id);
    }

    public function destroy($id)
    {
        $course = Course::with('batches')->find($id);
        if (!$course) return response()->json(['message' => 'الدورة غير موجودة'], 404);
        $courseData = $course->toArray();
        $course->delete();
        return response()->json($courseData);
    }
}
