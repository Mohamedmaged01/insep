<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Batch;
use App\Models\Enrollment;
use App\Models\Certificate;
use App\Models\Transaction;
use App\Models\Attendance;
use App\Models\ExamResult;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin()
    {
        $totalStudents = User::where('role', 'student')->count();
        $totalInstructors = User::where('role', 'instructor')->count();
        $activeCourses = Course::where('status', 'active')->count();
        $totalBatches = Batch::count();
        $totalCertificates = Certificate::count();

        $transactions = Transaction::all();
        $revenue = $transactions->where('type', 'revenue')->sum('amount');
        $expenses = $transactions->where('type', 'expense')->sum('amount');

        return response()->json([
            'stats' => [
                ['label' => 'إجمالي الطلاب', 'value' => $totalStudents, 'icon' => 'Users', 'color' => 'blue'],
                ['label' => 'الدورات النشطة', 'value' => $activeCourses, 'icon' => 'BookOpen', 'color' => 'green'],
                ['label' => 'الإيرادات', 'value' => $revenue, 'icon' => 'DollarSign', 'color' => 'yellow'],
                ['label' => 'الشهادات', 'value' => $totalCertificates, 'icon' => 'Award', 'color' => 'purple'],
            ],
            'totalInstructors' => $totalInstructors,
            'totalBatches' => $totalBatches,
            'revenue' => $revenue,
            'expenses' => $expenses,
            'profit' => $revenue - $expenses,
        ]);
    }

    public function student(Request $request)
    {
        $studentId = $request->user()->id;
        $enrollments = Enrollment::where('student_id', $studentId)->get();
        $certificates = Certificate::where('student_id', $studentId)->count();

        $attendance = Attendance::where('student_id', $studentId)->get();
        $totalAtt = $attendance->count();
        $presentAtt = $attendance->where('status', 'present')->count();
        $attendanceRate = $totalAtt > 0 ? round(($presentAtt / $totalAtt) * 100) : 100;

        $avgProgress = $enrollments->count() > 0
            ? round($enrollments->sum('progress') / $enrollments->count())
            : 0;

        return response()->json([
            'stats' => [
                ['label' => 'دوراتي', 'value' => $enrollments->count(), 'icon' => 'BookOpen', 'color' => 'blue'],
                ['label' => 'الشهادات', 'value' => $certificates, 'icon' => 'Award', 'color' => 'green'],
                ['label' => 'نسبة الحضور', 'value' => $attendanceRate . '%', 'icon' => 'CheckCircle', 'color' => 'yellow'],
                ['label' => 'التقدم العام', 'value' => $avgProgress . '%', 'icon' => 'TrendingUp', 'color' => 'purple'],
            ],
        ]);
    }

    public function instructor(Request $request)
    {
        $instructorId = $request->user()->id;
        $batches = Batch::where('instructor_id', $instructorId)->count();
        $batchIds = Batch::where('instructor_id', $instructorId)->pluck('id');

        $totalStudents = Enrollment::whereIn('batch_id', $batchIds)->count();
        $instructor = User::find($instructorId);

        return response()->json([
            'stats' => [
                ['label' => 'مجموعاتي', 'value' => $batches, 'icon' => 'Users', 'color' => 'blue'],
                ['label' => 'طلابي', 'value' => $totalStudents, 'icon' => 'GraduationCap', 'color' => 'green'],
                ['label' => 'الساعات التدريبية', 'value' => $batches * 40, 'icon' => 'Clock', 'color' => 'yellow'],
                ['label' => 'التقييم', 'value' => $instructor->rating ?: 4.8, 'icon' => 'Star', 'color' => 'purple'],
            ],
        ]);
    }
}
