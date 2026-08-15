<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    /** Lesson ids the user has completed in a course. */
    private function completedIds($userId, $courseId): array
    {
        return LessonProgress::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->whereNotNull('completed_at')
            ->pluck('lesson_id')
            ->map(fn ($v) => (int) $v)
            ->all();
    }

    /** Staff always have access; students need an enrollment. */
    private function hasAccess($user, $courseId): bool
    {
        if (($user->role ?? 'student') !== 'student') return true;
        return Enrollment::where('student_id', $user->id)->where('course_id', $courseId)->exists();
    }

    /**
     * Serialise a lesson to the mobile contract, computing sequential-unlock
     * locking (preview open to all; the rest need access + previous completed).
     *
     * @param  array<int,Lesson>  $lessons  ordered lessons of the course
     */
    private function format(Lesson $lesson, array $lessons, int $index, array $completed, bool $access): array
    {
        $order = $index + 1;
        $prev  = $index > 0 ? $lessons[$index - 1] : null;
        $next  = $index < count($lessons) - 1 ? $lessons[$index + 1] : null;

        $isPreview     = (bool) $lesson->is_preview;
        $isCompleted   = in_array((int) $lesson->id, $completed, true);
        $prevCompleted = $index === 0 || ($prev && in_array((int) $prev->id, $completed, true));
        $locked        = !$isPreview && (!$access || !$prevCompleted);

        $quiz = $lesson->quiz;
        if (is_array($quiz)) {
            $quiz = [
                'exam_id'    => $quiz['exam_id'] ?? null,
                'pass_score' => $quiz['pass_score'] ?? 60,
                'attempts'   => $quiz['attempts'] ?? null,
                'questions'  => array_values($quiz['questions'] ?? []),
            ];
        } else {
            $quiz = null;
        }

        return [
            'id'             => (int) $lesson->id,
            'title'          => $lesson->tr('title') ?: ($lesson->title ?? "Lesson {$order}"),
            'order'          => $order,
            'video_url'      => $lesson->video_url,
            'duration'       => $lesson->duration,
            'attachments'    => $lesson->attachments ?? [],
            'quiz'           => $quiz,
            'is_preview'     => $isPreview,
            'is_locked'      => $locked,
            'completed'      => $isCompleted,
            'course_id'      => (int) $lesson->course_id,
            'course_title'   => optional($lesson->course)->tr('title'),
            'next_lesson_id' => $next ? (int) $next->id : null,
            'prev_lesson_id' => $prev ? (int) $prev->id : null,
        ];
    }

    /** GET /courses/{id}/lessons — one section with the ordered, access-aware lessons. */
    public function curriculum(Request $request, $id)
    {
        $course = Course::find($id);
        if (!$course) return response()->json(['message' => 'الدورة غير موجودة'], 404);

        $user      = $request->user();
        $lessons   = Lesson::where('course_id', $id)->orderBy('order')->orderBy('id')->get()->all();
        $completed = $this->completedIds($user->id, (int) $id);
        $access    = $this->hasAccess($user, (int) $id);

        $formatted = [];
        foreach ($lessons as $i => $lesson) {
            $lesson->setRelation('course', $course);
            $formatted[] = $this->format($lesson, $lessons, $i, $completed, $access);
        }

        return response()->json([[
            'id'      => (int) $id,
            'title'   => $course->tr('title') ?: 'دروس الدورة',
            'order'   => 1,
            'lessons' => $formatted,
        ]]);
    }

    /** GET /lessons/{id} — a single lesson (+ next/prev, locking, quiz). */
    public function show(Request $request, $id)
    {
        $lesson = Lesson::with('course')->find($id);
        if (!$lesson) return response()->json(['message' => 'الدرس غير موجود'], 404);

        $user     = $request->user();
        $courseId = (int) $lesson->course_id;
        $lessons  = Lesson::where('course_id', $courseId)->orderBy('order')->orderBy('id')->get()->all();

        $index = 0;
        foreach ($lessons as $i => $l) {
            if ((int) $l->id === (int) $lesson->id) { $index = $i; break; }
        }
        $lessons[$index]->setRelation('course', $lesson->course);

        $completed = $this->completedIds($user->id, $courseId);
        $access    = $this->hasAccess($user, $courseId);

        return response()->json($this->format($lessons[$index], $lessons, $index, $completed, $access));
    }

    // ── Admin management ─────────────────────────────────────────────────────
    public function store(Request $request, $id)
    {
        $course = Course::find($id);
        if (!$course) return response()->json(['message' => 'الدورة غير موجودة'], 404);

        $data = $request->all();
        $data['course_id'] = $id;
        $lesson = Lesson::create($data);

        return response()->json($lesson, 201);
    }

    public function update(Request $request, $id)
    {
        $lesson = Lesson::find($id);
        if (!$lesson) return response()->json(['message' => 'الدرس غير موجود'], 404);

        $lesson->update($request->all());
        return response()->json($lesson);
    }

    public function destroy($id)
    {
        $lesson = Lesson::find($id);
        if ($lesson) $lesson->delete();
        return response()->json(['deleted' => true]);
    }
}
