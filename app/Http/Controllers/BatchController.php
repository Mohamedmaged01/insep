<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function index(Request $request)
    {
        $query = Batch::with(['course', 'instructor']);

        if ($request->user()->role === 'instructor') {
            $query->where('instructor_id', $request->user()->id);
        }
        if ($request->courseId) {
            $query->where('course_id', $request->courseId);
        }

        return response()->json($query->orderBy('start_date', 'desc')->get());
    }

    public function show($id)
    {
        $batch = Batch::with(['course', 'instructor', 'enrollments', 'enrollments.student'])->find($id);
        if (!$batch) return response()->json(['message' => 'المجموعة غير موجودة'], 404);
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
        return $this->show($id);
    }

    public function destroy($id)
    {
        $batch = Batch::with(['course', 'instructor', 'enrollments', 'enrollments.student'])->find($id);
        if (!$batch) return response()->json(['message' => 'المجموعة غير موجودة'], 404);
        $batchData = $batch->toArray();
        $batch->delete();
        return response()->json($batchData);
    }
}
