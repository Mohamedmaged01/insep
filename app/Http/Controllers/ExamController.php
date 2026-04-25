<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ExamResult;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $user  = $request->user();
        $query = Exam::with('course');

        if ($user->role === 'instructor') {
            $batchIds = Batch::where('instructor_id', $user->id)->pluck('id');
            $query->whereIn('batch_id', $batchIds);
        } elseif ($user->role === 'student') {
            $batchIds = Enrollment::where('student_id', $user->id)->pluck('batch_id');
            $query->whereIn('batch_id', $batchIds);
        }

        if ($request->courseId) $query->where('course_id', $request->courseId);
        if ($request->status)   $query->where('status', $request->status);

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $exam = Exam::with(['course'])->find($id);

        if (!$exam) return response()->json(['message' => 'الاختبار غير موجود'], 404);

        if ($user->role === 'instructor') {
            $ownBatchIds = Batch::where('instructor_id', $user->id)->pluck('id');
            abort_if(!$ownBatchIds->contains($exam->batch_id), 403);
            // Instructors see all results for their exam
            $exam->load('results.student');
        } elseif ($user->role === 'student') {
            abort_if(
                !Enrollment::where('student_id', $user->id)->where('batch_id', $exam->batch_id)->exists(),
                403
            );
            // Students see only their own result
            $exam->setRelation('results', ExamResult::where('exam_id', $id)->where('student_id', $user->id)->get());
        } else {
            $exam->load('results.student');
        }

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
        return $this->show($request, $id);
    }

    public function destroy($id)
    {
        Exam::destroy($id);
        return response()->json(['deleted' => true]);
    }

    public function submitResult(Request $request, $id)
    {
        $user = $request->user();
        $exam = Exam::findOrFail($id);

        // Student must be enrolled in the batch this exam belongs to
        abort_if(
            !Enrollment::where('student_id', $user->id)->where('batch_id', $exam->batch_id)->exists(),
            403
        );

        $result = ExamResult::create([
            'exam_id'        => $id,
            'student_id'     => $user->id,
            'score'          => $request->score,
            'attempt_number' => $request->attempt_number ?? 1,
        ]);

        return response()->json($result);
    }

    public function getResults(Request $request, $id)
    {
        $user = $request->user();
        $exam = Exam::findOrFail($id);

        if ($user->role === 'student') {
            // Students see only their own results
            $results = ExamResult::with('student')
                ->where('exam_id', $id)
                ->where('student_id', $user->id)
                ->orderBy('submitted_at', 'desc')
                ->get();
        } elseif ($user->role === 'instructor') {
            $ownBatchIds = Batch::where('instructor_id', $user->id)->pluck('id');
            abort_if(!$ownBatchIds->contains($exam->batch_id), 403);
            $results = ExamResult::with('student')
                ->where('exam_id', $id)
                ->orderBy('submitted_at', 'desc')
                ->get();
        } else {
            $results = ExamResult::with('student')
                ->where('exam_id', $id)
                ->orderBy('submitted_at', 'desc')
                ->get();
        }

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
