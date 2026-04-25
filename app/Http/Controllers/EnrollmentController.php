<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Enrollment::with(['course', 'batch', 'student', 'batch.instructor']);

        if ($request->studentId) $query->where('student_id', $request->studentId);
        if ($request->courseId)  $query->where('course_id', $request->courseId);
        if ($request->batchId)   $query->where('batch_id', $request->batchId);

        return response()->json($query->orderBy('enrolled_at', 'desc')->get());
    }

    public function my(Request $request)
    {
        $enrollments = Enrollment::with(['course', 'batch', 'batch.instructor'])
            ->where('student_id', $request->user()->id)
            ->orderBy('enrolled_at', 'desc')
            ->get();

        return response()->json($enrollments);
    }

    public function store(Request $request)
    {
        $enrollment = Enrollment::create($request->all());
        return response()->json($enrollment);
    }

    public function update(Request $request, $id)
    {
        // Only admins may modify enrollment records
        abort_if($request->user()->role !== 'admin', 403);

        Enrollment::where('id', $id)->update($request->all());
        $enrollment = Enrollment::with(['course', 'batch'])->find($id);
        return response()->json($enrollment);
    }

    public function destroy($id)
    {
        Enrollment::destroy($id);
        return response()->json(['deleted' => true]);
    }
}
