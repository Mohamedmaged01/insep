<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Certificate;
use App\Models\Transaction;
use App\Models\Attendance;
use App\Models\ExamResult;

class ReportController extends Controller
{
    public function enrollment()
    {
        $totalEnrollments = Enrollment::count();
        $activeEnrollments = Enrollment::where('status', 'active')->count();
        $courses = Course::all();

        $byCourse = [];
        foreach ($courses as $course) {
            $byCourse[] = [
                'courseId' => $course->id,
                'title' => $course->title,
                'count' => Enrollment::where('course_id', $course->id)->count(),
            ];
        }

        return response()->json([
            'totalEnrollments' => $totalEnrollments,
            'activeEnrollments' => $activeEnrollments,
            'byCourse' => $byCourse,
        ]);
    }

    public function performance()
    {
        $results = ExamResult::with(['exam', 'student'])->get();
        $avgScore = $results->count() > 0
            ? round($results->sum('score') / $results->count())
            : 0;

        return response()->json([
            'totalExamsTaken' => $results->count(),
            'avgScore' => $avgScore,
            'results' => $results->take(50),
        ]);
    }

    public function revenue()
    {
        $transactions = Transaction::orderBy('created_at', 'desc')->get();
        $revenue = $transactions->where('type', 'revenue')->sum('amount');
        $expenses = $transactions->where('type', 'expense')->sum('amount');

        return response()->json([
            'revenue' => $revenue,
            'expenses' => $expenses,
            'profit' => $revenue - $expenses,
            'totalTransactions' => $transactions->count(),
            'recentTransactions' => $transactions->take(20)->values(),
        ]);
    }

    public function attendance()
    {
        $all = Attendance::with(['student', 'batch'])->get();
        $total = $all->count();
        $present = $all->where('status', 'present')->count();
        $absent = $all->where('status', 'absent')->count();

        return response()->json([
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'rate' => $total > 0 ? round(($present / $total) * 100) : 0,
        ]);
    }

    public function certificates()
    {
        $total = Certificate::count();
        $certs = Certificate::with(['student', 'course'])
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        return response()->json([
            'total' => $total,
            'certificates' => $certs,
        ]);
    }
}
