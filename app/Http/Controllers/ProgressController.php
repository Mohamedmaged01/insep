<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    /** GET /progress/course/{id} — the user's completion summary for a course. */
    public function course(Request $request, $id)
    {
        $userId = $request->user()->id;
        $total  = Lesson::where('course_id', $id)->count();

        $ids = LessonProgress::where('user_id', $userId)
            ->where('course_id', $id)
            ->whereNotNull('completed_at')
            ->pluck('lesson_id')
            ->map(fn ($v) => (int) $v)
            ->values()
            ->all();

        $completed = min(count($ids), $total);

        return response()->json([
            'completed'            => $completed,
            'total'                => $total,
            'percent'              => $total ? (int) round($completed / $total * 100) : 0,
            'completed_lesson_ids' => $ids,
        ]);
    }

    /** POST /progress/lesson/{id} — mark a lesson complete, return the course summary. */
    public function markLesson(Request $request, $id)
    {
        $lesson = Lesson::find($id);
        if (!$lesson) return response()->json(['message' => 'الدرس غير موجود'], 404);

        LessonProgress::updateOrCreate(
            ['user_id' => $request->user()->id, 'lesson_id' => (int) $id],
            ['course_id' => (int) $lesson->course_id, 'completed_at' => now()],
        );

        return $this->course($request, $lesson->course_id);
    }
}
