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
use App\Models\Installment;
use App\Models\Resource;
use App\Models\Notification;
use App\Models\LiveSession;
use App\Models\Point;

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

    public function overview()
    {
        // Core counts
        $incomeTotal  = Transaction::where('type', 'income')->sum('amount');
        $expenseTotal = Transaction::where('type', 'expense')->sum('amount');

        $stats = [
            'students'     => User::where('role', 'student')->count(),
            'instructors'  => User::where('role', 'instructor')->count(),
            'courses'      => Course::count(),
            'batches'      => Batch::count(),
            'enrollments'  => Enrollment::count(),
            'certificates' => Certificate::count(),
            'income'       => (float) $incomeTotal,
            'expense'      => (float) $expenseTotal,
            'profit'       => (float) ($incomeTotal - $expenseTotal),
        ];

        // KPI rates
        $totalEnrollments = $stats['enrollments'];
        $dropoutCount     = Enrollment::whereIn('status', ['dropped', 'cancelled'])->count();
        $dropoutRate      = $totalEnrollments > 0 ? round(($dropoutCount / $totalEnrollments) * 100) : 0;
        $retainedStudents = Enrollment::select('student_id')->groupBy('student_id')->havingRaw('COUNT(*) > 1')->count();
        $retentionRate    = $stats['students'] > 0 ? round(($retainedStudents / $stats['students']) * 100) : 0;
        $completionRate   = $totalEnrollments > 0 ? round(($stats['certificates'] / $totalEnrollments) * 100) : 0;
        $activeStudents   = User::where('role', 'student')->where('status', 'active')->count();
        $inactiveStudents = $stats['students'] - $activeStudents;

        // Attendance
        $totalAtt   = Attendance::count();
        $presentCnt = Attendance::where('status', 'present')->count();
        $absentCnt  = Attendance::where('status', 'absent')->count();
        $attRate    = $totalAtt > 0 ? round(($presentCnt / $totalAtt) * 100) : 0;

        // Exams
        $totalExams  = ExamResult::count();
        $avgScore    = $totalExams > 0 ? round(ExamResult::avg('score')) : 0;
        $passedCount = ExamResult::where('score', '>=', 60)->count();
        $passRate    = $totalExams > 0 ? round(($passedCount / $totalExams) * 100) : 0;

        // Installments
        $instTotal   = (float) Installment::sum('total_amount');
        $instPaid    = (float) Installment::sum('paid_amount');
        $instPending = max(0.0, $instTotal - $instPaid);
        $collectRate = $instTotal > 0 ? round(($instPaid / $instTotal) * 100) : 0;

        // Platform stats
        $totalResources     = class_exists(Resource::class)     ? Resource::count()           : 0;
        $totalDownloads     = class_exists(Resource::class)     ? (int) Resource::sum('downloads') : 0;
        $totalNotifications = class_exists(Notification::class) ? Notification::count()        : 0;
        $totalLiveSessions  = class_exists(LiveSession::class)  ? LiveSession::count()         : 0;
        $totalPoints        = class_exists(Point::class)        ? (int) Point::sum('amount')   : 0;

        // Monthly trends (last 12 months)
        $monthlyStudents    = [];
        $monthlyEnrollments = [];
        $incomeByMonth      = [];
        $expenseByMonth     = [];
        for ($i = 11; $i >= 0; $i--) {
            $m     = now()->subMonths($i);
            $label = $m->format('M Y');
            $monthlyStudents[$label]    = User::where('role', 'student')->whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->count();
            $monthlyEnrollments[$label] = Enrollment::whereYear('enrolled_at', $m->year)->whereMonth('enrolled_at', $m->month)->count();
            $incomeByMonth[$label]      = (int) Transaction::where('type', 'income')->whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->sum('amount');
            $expenseByMonth[$label]     = (int) Transaction::where('type', 'expense')->whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->sum('amount');
        }

        // Trainees list
        $recentStudents = User::where('role', 'student')
            ->withCount(['enrollments', 'certificates'])
            ->orderBy('created_at', 'desc')
            ->limit(100)->get()
            ->map(fn($s) => [
                'id'                  => $s->id,
                'name'                => $s->name,
                'email'               => $s->email,
                'phone'               => $s->phone,
                'status'              => $s->status ?? 'active',
                'enrollments_count'   => $s->enrollments_count,
                'certificates_count'  => $s->certificates_count,
                'created_at'          => optional($s->created_at)->format('Y-m-d'),
            ]);

        // Courses list
        $topCourses = Course::withCount(['enrollments', 'resources', 'exams'])
            ->orderByDesc('enrollments_count')
            ->limit(100)->get()
            ->map(fn($c) => [
                'id'                => $c->id,
                'title'             => $c->title,
                'category'          => $c->category,
                'level'             => $c->level ?? null,
                'price'             => (float) ($c->price ?? 0),
                'enrollments_count' => $c->enrollments_count,
                'resources_count'   => $c->resources_count,
                'exams_count'       => $c->exams_count,
            ]);

        // Trainers list
        $trainers = User::where('role', 'instructor')->get()->map(function ($inst) {
            $batchIds = Batch::where('instructor_id', $inst->id)->pluck('id');
            $courseIds = Batch::whereIn('id', $batchIds)->pluck('course_id')->unique();
            $results = ExamResult::whereHas('exam', fn($q) => $q->whereIn('course_id', $courseIds))->get();
            return [
                'id'            => $inst->id,
                'name'          => $inst->name,
                'email'         => $inst->email,
                'batch_count'   => $batchIds->count(),
                'student_count' => Enrollment::whereIn('batch_id', $batchIds)->distinct('student_id')->count('student_id'),
                'pass_rate'     => $results->count() > 0 ? round($results->where('score', '>=', 60)->count() / $results->count() * 100) : 0,
                'created_at'    => optional($inst->created_at)->format('Y-m-d'),
            ];
        })->sortByDesc('batch_count')->values();

        // Transactions list
        $recentTransactions = Transaction::with('user')
            ->orderBy('created_at', 'desc')->limit(100)->get()
            ->map(fn($tx) => [
                'id'          => $tx->id,
                'description' => $tx->description,
                'amount'      => (float) $tx->amount,
                'type'        => $tx->type,
                'user_name'   => $tx->user->name ?? null,
                'created_at'  => optional($tx->created_at)->format('Y-m-d'),
            ]);

        // Installments list
        $recentInstallments = Installment::with(['student', 'course'])
            ->orderBy('due_date')->limit(50)->get()
            ->map(fn($inst) => [
                'student_name' => $inst->student->name ?? null,
                'course_title' => $inst->course->title ?? null,
                'total_amount' => (float) $inst->total_amount,
                'paid_amount'  => (float) $inst->paid_amount,
                'due_date'     => $inst->due_date ? \Carbon\Carbon::parse($inst->due_date)->format('Y-m-d') : null,
                'status'       => $inst->status ?? 'pending',
            ]);

        // Attendance log
        $attendanceLog = Attendance::with(['student', 'batch'])
            ->orderBy('created_at', 'desc')->limit(100)->get()
            ->map(fn($att) => [
                'student_name' => $att->student->name ?? null,
                'batch_name'   => $att->batch->name ?? null,
                'status'       => $att->status,
                'date'         => optional($att->created_at)->format('Y-m-d'),
            ]);

        // Exam results log
        $examLog = ExamResult::with(['exam', 'student'])
            ->orderBy('submitted_at', 'desc')->limit(100)->get()
            ->map(fn($r) => [
                'student_name' => $r->student->name ?? null,
                'exam_title'   => $r->exam->title ?? null,
                'score'        => (float) ($r->score ?? 0),
                'passed'       => ($r->score ?? 0) >= 60,
                'date'         => optional($r->submitted_at ?? $r->created_at)->format('Y-m-d'),
            ]);

        return response()->json([
            'stats'              => $stats,
            'kpis'               => compact('completionRate', 'retentionRate', 'dropoutRate', 'dropoutCount', 'retainedStudents', 'activeStudents', 'inactiveStudents'),
            'attendance'         => ['total' => $totalAtt, 'present' => $presentCnt, 'absent' => $absentCnt, 'rate' => $attRate, 'log' => $attendanceLog],
            'exams'              => ['total' => $totalExams, 'avgScore' => $avgScore, 'passRate' => $passRate, 'log' => $examLog],
            'installments'       => ['total' => $instTotal, 'paid' => $instPaid, 'remaining' => $instPending, 'collectRate' => $collectRate, 'log' => $recentInstallments],
            'platform'           => compact('totalResources', 'totalDownloads', 'totalNotifications', 'totalLiveSessions', 'totalPoints'),
            'recentStudents'     => $recentStudents,
            'topCourses'         => $topCourses,
            'trainers'           => $trainers,
            'recentTransactions' => $recentTransactions,
            'monthlyStudents'    => $monthlyStudents,
            'monthlyEnrollments' => $monthlyEnrollments,
            'incomeByMonth'      => $incomeByMonth,
            'expenseByMonth'     => $expenseByMonth,
        ]);
    }
}
