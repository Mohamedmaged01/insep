<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Lesson;

/**
 * Seeds a starter curriculum for every course that has none yet, so the mobile
 * learning experience has content to show. Idempotent: run it as often as you
 * like — courses that already have lessons are skipped. Replace/extend the
 * generated lessons with real content via the admin lesson endpoints.
 *
 *   php artisan db:seed --class=LessonSeeder
 */
class LessonSeeder extends Seeder
{
    public function run(): void
    {
        Course::orderBy('id')->get()->each(function (Course $course) {
            if (Lesson::where('course_id', $course->id)->exists()) {
                return; // already has a curriculum
            }

            $count = 5 + ($course->id % 4); // 5–8 lessons

            for ($i = 1; $i <= $count; $i++) {
                Lesson::create([
                    'course_id'   => $course->id,
                    'title_ar'    => "الدرس {$i}",
                    'title_en'    => "Lesson {$i}",
                    'order'       => $i,
                    'video_url'   => $course->promo_video ?: null,
                    'duration'    => (8 + $i * 2) . ' min',
                    'is_preview'  => $i === 1,
                    'attachments' => $i === 1 ? [['name' => 'PDF', 'url' => 'https://insep-ksa.com']] : [],
                    'quiz'        => [
                        'pass_score' => 80,
                        'attempts'   => null,
                        'questions'  => array_map(fn ($n) => [
                            'id'             => $n,
                            'text'           => "سؤال تجريبي {$n} — الدرس {$i}",
                            'options'        => ['الخيار أ', 'الخيار ب', 'الخيار ج', 'الخيار د'],
                            'correct_answer' => 'الخيار أ',
                        ], [1, 2, 3]),
                    ],
                ]);
            }
        });
    }
}
