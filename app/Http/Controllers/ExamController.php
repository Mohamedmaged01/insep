<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamResult;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $query = Exam::with('course');

        if ($request->courseId) $query->where('course_id', $request->courseId);
        if ($request->status) $query->where('status', $request->status);

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    public function show($id)
    {
        $exam = Exam::with(['course', 'results'])->find($id);
        if (!$exam) return response()->json(['message' => 'الاختبار غير موجود'], 404);
        return response()->json($exam);
    }

    public function store(Request $request)
    {
        $exam = Exam::create($request->all());
        return response()->json($exam);
    }

    public function update(Request $request, $id)
    {
        Exam::where('id', $id)->update($request->all());
        return $this->show($id);
    }

    public function destroy($id)
    {
        Exam::destroy($id);
        return response()->json(['deleted' => true]);
    }

    public function submitResult(Request $request, $id)
    {
        $result = ExamResult::create([
            'exam_id' => $id,
            'student_id' => $request->user()->id,
            'score' => $request->score,
            'attempt_number' => $request->attempt_number ?? 1,
        ]);
        return response()->json($result);
    }

    public function getResults($id)
    {
        $results = ExamResult::with('student')
            ->where('exam_id', $id)
            ->orderBy('submitted_at', 'desc')
            ->get();
        return response()->json($results);
    }

    public function myResults(Request $request)
    {
        $results = ExamResult::with(['exam', 'exam.course'])
            ->where('student_id', $request->user()->id)
            ->orderBy('submitted_at', 'desc')
            ->get();
        return response()->json($results);
    }
}
