<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function index(Request $request)
    {
        $user  = $request->user();
        $query = Batch::with(['course', 'instructor']);

        if ($user->role === 'instructor') {
            $query->where('instructor_id', $user->id);
        } elseif ($user->role === 'student') {
            $batchIds = Enrollment::where('student_id', $user->id)->pluck('batch_id');
            $query->whereIn('id', $batchIds);
        }

        if ($request->courseId) {
            $query->where('course_id', $request->courseId);
        }

        return response()->json($query->orderBy('start_date', 'desc')->get());
    }

    public function show(Request $request, $id)
    {
        $user  = $request->user();
        $batch = Batch::with(['course', 'instructor', 'enrollments', 'enrollments.student'])->find($id);

        if (!$batch) return response()->json(['message' => 'المجموعة غير موجودة'], 404);

        if ($user->role === 'student') {
            abort_if(
                !Enrollment::where('student_id', $user->id)->where('batch_id', $id)->exists(),
                403
            );
            // Students must not see other students' personal data
            return response()->json($batch->only(['id', 'name', 'start_date', 'end_date', 'status', 'max_students', 'course_id', 'instructor_id', 'course', 'instructor']));
        }

        if ($user->role === 'instructor') {
            abort_if($batch->instructor_id !== $user->id, 403);
        }

        return response()->json($batch);
    }

    public function store(Request $request)
    {
        $batch = Batch::create($request->all());
        return response()->json($batch);
    }

    public function update(Request $request, $id)
    {
        Batch::where('id', $id)->update($request->all());
        return $this->show($request, $id);
    }

    public function destroy(Request $request, $id)
    {
        $batch = Batch::with(['course', 'instructor'])->find($id);
        if (!$batch) return response()->json(['message' => 'المجموعة غير موجودة'], 404);
        $batchData = $batch->only(['id', 'name', 'course_id', 'instructor_id']);
        $batch->delete();
        return response()->json($batchData);
    }
}
