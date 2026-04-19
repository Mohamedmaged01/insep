<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Course;
use App\Models\Batch;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\Certificate;
use App\Models\Transaction;
use App\Models\Attendance;
use App\Models\Resource;
use App\Models\LiveSession;
use App\Models\Notification;
use App\Models\Point;
use App\Models\Badge;
use App\Models\Installment;
use App\Models\Section;
use App\Models\ExamResult;
use App\Models\CommitteeMember;

class DashboardWebController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = match($user->role) {
            'admin' => [
                ['label' => 'إجمالي المتدربين',  'value' => User::where('role', 'student')->count(),                              'color' => 'from-blue-500 to-blue-600',   'icon' => 'users'],
                ['label' => 'الدورات النشطة',    'value' => Course::count(),                                                      'color' => 'from-green-500 to-green-600', 'icon' => 'book'],
                ['label' => 'الإيرادات',          'value' => number_format(Transaction::where('type', 'income')->sum('amount')) . ' ج.م', 'color' => 'from-purple-500 to-purple-600', 'icon' => 'dollar'],
                ['label' => 'الشهادات الصادرة',  'value' => Certificate::count(),                                                 'color' => 'from-orange-500 to-orange-600', 'icon' => 'award'],
            ],
            'student' => [
                ['label' => 'دوراتي المسجلة',   'value' => Enrollment::where('student_id', $user->id)->count(),                  'color' => 'from-blue-500 to-blue-600',   'icon' => 'book'],
                ['label' => 'الشهادات المحصلة', 'value' => Certificate::where('student_id', $user->id)->count(),                 'color' => 'from-green-500 to-green-600', 'icon' => 'award'],
                ['label' => 'نقاطي',             'value' => Point::where('student_id', $user->id)->sum('amount'),                 'color' => 'from-purple-500 to-purple-600', 'icon' => 'star'],
                ['label' => 'المعدل العام',      'value' => '0%',                                                                 'color' => 'from-orange-500 to-orange-600', 'icon' => 'check'],
            ],
            'instructor' => [
                ['label' => 'مجموعاتي',          'value' => Batch::where('instructor_id', $user->id)->count(),                   'color' => 'from-blue-500 to-blue-600',   'icon' => 'clipboard'],
                ['label' => 'الدورات التي أدرّبها','value' => Course::where('instructor_id', $user->id)->count(),                 'color' => 'from-green-500 to-green-600', 'icon' => 'book'],
                ['label' => 'عدد المتدربين',     'value' => Enrollment::whereHas('batch', fn($q) => $q->where('instructor_id', $user->id))->count(), 'color' => 'from-purple-500 to-purple-600', 'icon' => 'users'],
                ['label' => 'التقييم العام',     'value' => ($user->rating ?? 0) . ' / 5',                                       'color' => 'from-orange-500 to-orange-600', 'icon' => 'star'],
            ],
            'finance' => [
                ['label' => 'إجمالي الإيرادات', 'value' => number_format(Transaction::where('type', 'income')->sum('amount')) . ' ج.م',  'color' => 'from-green-500 to-green-600',  'icon' => 'dollar'],
                ['label' => 'إجمالي المصروفات', 'value' => number_format(Transaction::where('type', 'expense')->sum('amount')) . ' ج.م', 'color' => 'from-red-500 to-red-600',      'icon' => 'dollar'],
                ['label' => 'صافي الإيراد',     'value' => number_format(Transaction::where('type', 'income')->sum('amount') - Transaction::where('type', 'expense')->sum('amount')) . ' ج.م', 'color' => 'from-blue-500 to-blue-600', 'icon' => 'dollar'],
                ['label' => 'عدد الفواتير',     'value' => Transaction::count(),                                                  'color' => 'from-purple-500 to-purple-600', 'icon' => 'file-text'],
            ],
            'support' => [
                ['label' => 'إجمالي المتدربين', 'value' => User::where('role', 'student')->count(),                               'color' => 'from-blue-500 to-blue-600',   'icon' => 'users'],
                ['label' => 'الحسابات النشطة',  'value' => User::where('status', 'active')->count(),                              'color' => 'from-green-500 to-green-600', 'icon' => 'user-check'],
                ['label' => 'الإشعارات المرسلة','value' => \App\Models\Notification::count(),                                    'color' => 'from-purple-500 to-purple-600', 'icon' => 'bell'],
                ['label' => 'الدورات المتاحة',  'value' => Course::count(),                                                       'color' => 'from-orange-500 to-orange-600', 'icon' => 'book'],
            ],
            default => [],
        };

        return view('dashboard.home', compact('stats'));
    }

    // ── General Users Management (admin) ────────────────────────────
    public function usersManagement()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        $roles = User::ROLES;
        return view('dashboard.users', compact('users', 'roles'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name_ar'  => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,instructor,student,finance,support',
        ]);

        User::create([
            'name'     => $request->name_ar,
            'name_ar'  => $request->name_ar,
            'name_en'  => $request->name_en ?? $request->name_ar,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'phone'    => $request->phone,
            'role'     => $request->role,
            'status'   => 'active',
        ]);
        return back()->with('success', 'تم إضافة المستخدم بنجاح');
    }

    public function updateUser(Request $request, User $user)
    {
        $data = $request->only('name_ar', 'name_en', 'email', 'phone', 'role', 'status');
        $data['name'] = $data['name_ar'] ?? $user->name;
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }
        if (isset($data['role']) && !array_key_exists($data['role'], User::ROLES)) {
            return back()->with('error', 'دور غير صالح');
        }
        $user->update($data);
        return back()->with('success', 'تم تحديث المستخدم بنجاح');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك حذف حسابك الخاص');
        }
        $user->delete();
        return back()->with('success', 'تم حذف المستخدم بنجاح');
    }

    public function students()
    {
        $students = User::where('role', 'student')->orderBy('created_at', 'desc')->get();
        return view('dashboard.students', compact('students'));
    }

    public function instructors()
    {
        $instructors = User::where('role', 'instructor')->orderBy('created_at', 'desc')->get();
        return view('dashboard.instructors', compact('instructors'));
    }

    public function courses()
    {
        $courses = Course::with('section')->withCount('enrollments')->orderBy('created_at', 'desc')->get();
        $sections = Section::orderBy('id', 'desc')->get();
        return view('dashboard.courses', compact('courses', 'sections'));
    }

    // ── Sections CRUD ──────────────────────────────────────────────
    public function sections()
    {
        $sections = Section::withCount('courses')->orderBy('id', 'desc')->get();
        return view('dashboard.sections', compact('sections'));
    }

    public function storeSection(Request $request)
    {
        Section::create([
            'name_ar'     => $request->name_ar,
            'name_en'     => $request->name_en,
            'description' => $request->description,
        ]);
        return back()->with('success', 'تم إضافة الشعبة بنجاح');
    }

    public function updateSection(Request $request, Section $section)
    {
        $section->update([
            'name_ar'     => $request->name_ar,
            'name_en'     => $request->name_en,
            'description' => $request->description,
        ]);
        return back()->with('success', 'تم تحديث الشعبة بنجاح');
    }

    public function destroySection(Section $section)
    {
        $section->delete();
        return back()->with('success', 'تم حذف الشعبة بنجاح');
    }

    public function batches()
    {
        $batches = Batch::with(['course', 'instructor'])->orderBy('id', 'desc')->get();
        $courses = Course::all();
        $instructors = User::where('role', 'instructor')->get();
        return view('dashboard.batches', compact('batches', 'courses', 'instructors'));
    }

    public function batchDetail(Batch $batch)
    {
        $batch->load(['course', 'instructor', 'enrollments.student']);
        $resources = Resource::where('batch_id', $batch->id)->orderBy('id', 'desc')->get();
        $liveSessions = LiveSession::where('batch_id', $batch->id)->orderBy('scheduled_at', 'desc')->get();
        $allStudents = User::where('role', 'student')->get();
        $enrolledIds = $batch->enrollments->pluck('student_id')->toArray();
        $certificates = Certificate::where('course_id', $batch->course_id)
            ->whereIn('student_id', $enrolledIds)
            ->get()
            ->keyBy('student_id');
        return view('dashboard.batch-detail', compact('batch', 'resources', 'liveSessions', 'allStudents', 'enrolledIds', 'certificates'));
    }

    public function enrollStudent(Request $request, Batch $batch)
    {
        $exists = Enrollment::where('student_id', $request->student_id)->where('batch_id', $batch->id)->exists();
        if (!$exists) {
            Enrollment::create([
                'student_id' => $request->student_id,
                'course_id'  => $batch->course_id,
                'batch_id'   => $batch->id,
                'status'     => 'active',
            ]);
        }
        return back()->with('success', 'تم إضافة الطالب للمجموعة بنجاح');
    }

    public function unenrollStudent(Batch $batch, Enrollment $enrollment)
    {
        $enrollment->delete();
        return back()->with('success', 'تم إزالة الطالب من المجموعة بنجاح');
    }

    public function myCourses()
    {
        $enrollments = Enrollment::where('student_id', auth()->id())->with('course', 'batch')->get();
        return view('dashboard.mycourses', compact('enrollments'));
    }

    public function attendance()
    {
        $batches = Batch::with('course')->orderBy('id', 'desc')->get();
        return view('dashboard.attendance', compact('batches'));
    }

    public function attendanceBatch(Batch $batch)
    {
        $batch->load(['course', 'enrollments.student']);
        $dates = Attendance::where('batch_id', $batch->id)->select('date')->distinct()->orderBy('date', 'desc')->pluck('date');
        $selectedDate = request('date', $dates->first() ?? date('Y-m-d'));
        $records = Attendance::where('batch_id', $batch->id)->where('date', $selectedDate)->with('student')->get()->keyBy('student_id');
        $enrolled = $batch->enrollments->map->student->filter();
        return view('dashboard.attendance-batch', compact('batch', 'dates', 'selectedDate', 'records', 'enrolled'));
    }

    public function storeAttendance(Request $request, Batch $batch)
    {
        $date = $request->date ?? date('Y-m-d');
        $statuses = $request->statuses ?? [];
        $notes = $request->notes ?? [];
        foreach ($statuses as $studentId => $status) {
            Attendance::updateOrCreate(
                ['batch_id' => $batch->id, 'student_id' => $studentId, 'date' => $date],
                ['status' => $status, 'notes' => $notes[$studentId] ?? null]
            );
        }
        return back()->with('success', 'تم حفظ الحضور بنجاح');
    }

    public function resources()
    {
        $resources = Resource::with('course')->orderBy('created_at', 'desc')->get();
        $courses = Course::all();
        $batches = Batch::with('course')->get();
        return view('dashboard.resources', compact('resources', 'courses', 'batches'));
    }

    public function liveSessions()
    {
        $sessions = LiveSession::with('batch')->orderBy('scheduled_at', 'desc')->get();
        $batches = Batch::with('course')->orderBy('id', 'desc')->get();
        return view('dashboard.live-sessions', compact('sessions', 'batches'));
    }

    public function exams()
    {
        $exams = Exam::with('course')->orderBy('created_at', 'desc')->get();
        $courses = Course::all();
        return view('dashboard.exams', compact('exams', 'courses'));
    }

    public function certificates()
    {
        $user = auth()->user();
        if ($user->role === 'student') {
            $certificates = Certificate::where('student_id', $user->id)->with('course')->get();
        } else {
            $certificates = Certificate::with(['student', 'course'])->orderBy('created_at', 'desc')->get();
        }
        return view('dashboard.certificates', compact('certificates'));
    }

    public function finance()
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            $transactions = Transaction::with('user')->orderBy('created_at', 'desc')->get();
            $installments  = Installment::with(['student', 'course', 'batch'])->orderBy('created_at', 'desc')->get();
        } else {
            $transactions = Transaction::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
            $installments  = Installment::where('student_id', $user->id)->with(['course', 'batch'])->orderBy('created_at', 'desc')->get();
        }
        $summary = [
            'income'  => $transactions->where('type', 'income')->sum('amount'),
            'expense' => $transactions->where('type', 'expense')->sum('amount'),
        ];
        $students = User::where('role', 'student')->get();
        $courses  = Course::all();
        $batches  = Batch::with('course')->get();
        return view('dashboard.finance', compact('transactions', 'summary', 'students', 'installments', 'courses', 'batches'));
    }

    public function notifications()
    {
        $user = auth()->user();
        if ($user->role === 'student') {
            $notifications = Notification::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
            $batches = collect();
        } elseif ($user->role === 'instructor') {
            // Only batches this instructor teaches
            $batches = Batch::with('course')->where('instructor_id', $user->id)->orderBy('id', 'desc')->get();
            // Notifications sent to students in their batches
            $batchIds = $batches->pluck('id');
            $studentIds = Enrollment::whereIn('batch_id', $batchIds)->pluck('student_id')->unique();
            $notifications = Notification::with('user')
                ->whereIn('user_id', $studentIds)
                ->orderBy('created_at', 'desc')->get();
        } else {
            // Admin sees everything
            $notifications = Notification::with('user')->orderBy('created_at', 'desc')->get();
            $batches = Batch::with('course')->orderBy('id', 'desc')->get();
        }
        return view('dashboard.notifications', compact('notifications', 'batches'));
    }


    public function reports(Request $request)
    {
        $from = $request->filled('from') ? \Carbon\Carbon::parse($request->input('from'))->startOfDay() : null;
        $to   = $request->filled('to')   ? \Carbon\Carbon::parse($request->input('to'))->endOfDay()   : null;

        $incomeTotal  = Transaction::where('type', 'income')
            ->when($from, fn($q) => $q->where('created_at', '>=', $from))
            ->when($to,   fn($q) => $q->where('created_at', '<=', $to))
            ->sum('amount');
        $expenseTotal = Transaction::where('type', 'expense')
            ->when($from, fn($q) => $q->where('created_at', '>=', $from))
            ->when($to,   fn($q) => $q->where('created_at', '<=', $to))
            ->sum('amount');

        $stats = [
            'students'     => User::where('role', 'student')->count(),
            'instructors'  => User::where('role', 'instructor')->count(),
            'courses'      => Course::count(),
            'batches'      => Batch::count(),
            'enrollments'  => Enrollment::when($from, fn($q) => $q->where('created_at', '>=', $from))->when($to, fn($q) => $q->where('created_at', '<=', $to))->count(),
            'certificates' => Certificate::when($from, fn($q) => $q->where('created_at', '>=', $from))->when($to, fn($q) => $q->where('created_at', '<=', $to))->count(),
            'income'       => $incomeTotal,
            'expense'      => $expenseTotal,
            'profit'       => $incomeTotal - $expenseTotal,
        ];

        // Trainee tab — with enrollment & certificate counts
        $recentStudents = User::where('role', 'student')
            ->withCount(['enrollments', 'certificates'])
            ->orderBy('created_at', 'desc')
            ->limit(100)->get();

        // Course tab — with resource & exam counts
        $topCourses = Course::withCount(['enrollments', 'resources', 'exams'])
            ->orderByDesc('enrollments_count')
            ->limit(100)->get();

        // Financial tab
        $recentTransactions = Transaction::with('user')
            ->when($from, fn($q) => $q->where('created_at', '>=', $from))
            ->when($to,   fn($q) => $q->where('created_at', '<=', $to))
            ->orderBy('created_at', 'desc')->limit(100)->get();

        $incomeByMonth = [];
        $expenseByMonth = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = now()->subMonths($i);
            $label = $m->format('M Y');
            $incomeByMonth[$label]  = (int) Transaction::where('type', 'income')->whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->sum('amount');
            $expenseByMonth[$label] = (int) Transaction::where('type', 'expense')->whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->sum('amount');
        }

        // Installments
        $installmentTotal   = Installment::sum('total_amount');
        $installmentPaid    = Installment::sum('paid_amount');
        $installmentPending = max(0, $installmentTotal - $installmentPaid);
        $recentInstallments = Installment::with(['student', 'course'])->orderBy('due_date')->limit(50)->get();

        // Attendance tab
        $totalAttendance  = Attendance::count();
        $presentCount     = Attendance::where('status', 'present')->count();
        $absentCount      = Attendance::where('status', 'absent')->count();
        $attendanceRate   = $totalAttendance > 0 ? round(($presentCount / $totalAttendance) * 100) : 0;
        $recentAttendance = Attendance::with(['student', 'batch'])->orderBy('created_at', 'desc')->limit(100)->get();

        // Exam/Assessment tab
        $totalExamsTaken = ExamResult::count();
        $avgScore        = $totalExamsTaken > 0 ? round(ExamResult::avg('score')) : 0;
        $passedCount     = ExamResult::where('score', '>=', 60)->count();
        $passRate        = $totalExamsTaken > 0 ? round(($passedCount / $totalExamsTaken) * 100) : 0;
        $recentExams     = ExamResult::with(['exam', 'student'])->orderBy('submitted_at', 'desc')->limit(100)->get();

        // Trainer tab — with student count and pass rate
        $instructorList = User::where('role', 'instructor')->get()->map(function ($instructor) {
            $batchIds              = Batch::where('instructor_id', $instructor->id)->pluck('id');
            $courseIds             = Batch::whereIn('id', $batchIds)->pluck('course_id')->unique();
            $instructor->batch_count   = $batchIds->count();
            $instructor->student_count = Enrollment::whereIn('batch_id', $batchIds)->distinct('student_id')->count('student_id');
            $results = ExamResult::whereHas('exam', fn($q) => $q->whereIn('course_id', $courseIds))->get();
            $instructor->pass_rate = $results->count() > 0 ? round($results->where('score', '>=', 60)->count() / $results->count() * 100) : 0;
            return $instructor;
        })->sortByDesc('batch_count');

        // KPI / trends — 12 months
        $monthlyStudents    = [];
        $monthlyEnrollments = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = now()->subMonths($i);
            $label = $m->format('M Y');
            $monthlyStudents[$label]    = User::where('role', 'student')->whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->count();
            $monthlyEnrollments[$label] = Enrollment::whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->count();
        }

        $totalEnrollments  = Enrollment::count();
        $dropoutCount      = Enrollment::whereIn('status', ['dropped', 'cancelled'])->count();
        $dropoutRate       = $totalEnrollments > 0 ? round(($dropoutCount / $totalEnrollments) * 100) : 0;
        $retainedStudents  = Enrollment::select('student_id')->groupBy('student_id')->havingRaw('COUNT(*) > 1')->count();
        $retentionRate     = $stats['students'] > 0 ? round(($retainedStudents / $stats['students']) * 100) : 0;
        $completionRate    = $totalEnrollments > 0 ? round(($stats['certificates'] / $totalEnrollments) * 100) : 0;

        // Platform / administrative stats
        $activeStudents     = User::where('role', 'student')->where('status', 'active')->count();
        $inactiveStudents   = $stats['students'] - $activeStudents;
        $totalDownloads     = Resource::sum('downloads');
        $totalNotifications = Notification::count();
        $totalLiveSessions  = LiveSession::count();
        $totalPoints        = Point::sum('amount');
        $totalResources     = Resource::count();

        $recentCourses = $topCourses;

        return view('dashboard.reports', compact(
            'stats', 'recentStudents', 'recentCourses', 'recentTransactions',
            'topCourses', 'totalAttendance', 'presentCount', 'absentCount', 'attendanceRate', 'recentAttendance',
            'totalExamsTaken', 'avgScore', 'passRate', 'recentExams',
            'instructorList', 'monthlyStudents', 'incomeByMonth', 'expenseByMonth', 'completionRate',
            'dropoutCount', 'dropoutRate', 'retentionRate', 'retainedStudents',
            'installmentTotal', 'installmentPaid', 'installmentPending', 'recentInstallments',
            'totalDownloads', 'totalNotifications', 'totalLiveSessions', 'totalPoints',
            'activeStudents', 'inactiveStudents', 'monthlyEnrollments', 'totalResources',
            'from', 'to'
        ));
    }

    public function exportReports(Request $request)
    {
        $format   = $request->get('format', 'excel');
        $date     = date('Y-m-d');
        $filename = 'insep-report-' . $date;

        $stats = [
            'إجمالي الطلاب'   => User::where('role', 'student')->count(),
            'المدربون'         => User::where('role', 'instructor')->count(),
            'الدورات'          => Course::count(),
            'المجموعات'        => Batch::count(),
            'التسجيلات'        => Enrollment::count(),
            'الشهادات'         => Certificate::count(),
            'الإيرادات'        => Transaction::where('type', 'income')->sum('amount'),
            'المصروفات'        => Transaction::where('type', 'expense')->sum('amount'),
        ];

        $students = User::where('role', 'student')->orderBy('created_at', 'desc')->limit(100)->get();
        $courses  = Course::withCount('enrollments')->orderByDesc('enrollments_count')->limit(100)->get();
        $transactions = Transaction::with('user')->orderBy('created_at', 'desc')->limit(100)->get();

        if ($format === 'pdf') {
            $html = $this->buildReportHtml($stats, $students, $courses, $transactions, $date, true);
            return response($html, 200, ['Content-Type' => 'text/html; charset=UTF-8']);
        }

        // Excel: HTML table exported as .xls (opens natively in Excel)
        $html = $this->buildReportHtml($stats, $students, $courses, $transactions, $date, false);
        return response($html, 200, [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.xls"',
        ]);
    }

    private function buildReportHtml(array $stats, $students, $courses, $transactions, string $date, bool $forPrint): string
    {
        $printScript = $forPrint ? '<script>window.onload=function(){window.print();}</script>' : '';
        $printStyle  = $forPrint ? '@media print{.no-print{display:none}}body{margin:20px}' : '';

        $statsRows = '';
        foreach ($stats as $label => $value) {
            $statsRows .= '<tr><td style="padding:6px 12px;border:1px solid #ddd;font-weight:bold">' . $label . '</td><td style="padding:6px 12px;border:1px solid #ddd;text-align:center">' . number_format((float)$value) . '</td></tr>';
        }

        $studentRows = '';
        foreach ($students as $s) {
            $status = ($s->status ?? 'active') === 'active' ? 'نشط' : 'معلق';
            $studentRows .= '<tr><td style="padding:6px 10px;border:1px solid #ddd">' . e($s->name) . '</td><td style="padding:6px 10px;border:1px solid #ddd">' . e($s->email) . '</td><td style="padding:6px 10px;border:1px solid #ddd">' . e($s->phone ?? '-') . '</td><td style="padding:6px 10px;border:1px solid #ddd;text-align:center">' . $status . '</td><td style="padding:6px 10px;border:1px solid #ddd;text-align:center">' . ($s->created_at ? \Carbon\Carbon::parse($s->created_at)->format('Y-m-d') : '-') . '</td></tr>';
        }

        $courseRows = '';
        foreach ($courses as $c) {
            $courseRows .= '<tr><td style="padding:6px 10px;border:1px solid #ddd">' . e($c->title) . '</td><td style="padding:6px 10px;border:1px solid #ddd">' . e($c->category ?? '-') . '</td><td style="padding:6px 10px;border:1px solid #ddd;text-align:center">' . number_format((float)$c->price) . '</td><td style="padding:6px 10px;border:1px solid #ddd;text-align:center">' . $c->enrollments_count . '</td></tr>';
        }

        $txRows = '';
        foreach ($transactions as $tx) {
            $type = $tx->type === 'income' ? 'إيراد' : 'مصروف';
            $color = $tx->type === 'income' ? '#16a34a' : '#dc2626';
            $txRows .= '<tr><td style="padding:6px 10px;border:1px solid #ddd">' . e($tx->description) . '</td><td style="padding:6px 10px;border:1px solid #ddd;text-align:center;color:' . $color . ';font-weight:bold">' . ($tx->type === 'income' ? '+' : '-') . number_format((float)$tx->amount) . '</td><td style="padding:6px 10px;border:1px solid #ddd;text-align:center">' . $type . '</td><td style="padding:6px 10px;border:1px solid #ddd">' . e($tx->user->name ?? '-') . '</td><td style="padding:6px 10px;border:1px solid #ddd;text-align:center">' . ($tx->created_at ? \Carbon\Carbon::parse($tx->created_at)->format('Y-m-d') : '-') . '</td></tr>';
        }

        return '<!DOCTYPE html><html dir="rtl" lang="ar"><head><meta charset="UTF-8">
        <style>
            body{font-family:Tahoma,Arial,sans-serif;direction:rtl;font-size:13px;color:#111}
            h1{color:#101756;font-size:20px;margin-bottom:4px}
            h2{color:#101756;font-size:15px;margin:24px 0 8px;border-bottom:2px solid #101756;padding-bottom:4px}
            table{width:100%;border-collapse:collapse;margin-bottom:16px}
            thead tr{background:#101756;color:#fff}
            thead th{padding:8px 10px;text-align:right;font-size:12px}
            tbody tr:nth-child(even){background:#f8f8f8}
            ' . $printStyle . '
        </style>' . $printScript . '</head><body>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
            <h1>تقرير INSEP PRO</h1>
            <span style="color:#888;font-size:12px">' . $date . '</span>
        </div>

        <h2>الإحصائيات العامة</h2>
        <table><thead><tr><th>البيان</th><th style="text-align:center">القيمة</th></tr></thead>
        <tbody>' . $statsRows . '</tbody></table>

        <h2>الطلاب المسجلون</h2>
        <table><thead><tr><th>الاسم</th><th>البريد الإلكتروني</th><th>الهاتف</th><th style="text-align:center">الحالة</th><th style="text-align:center">تاريخ التسجيل</th></tr></thead>
        <tbody>' . $studentRows . '</tbody></table>

        <h2>الدورات حسب التسجيل</h2>
        <table><thead><tr><th>الدورة</th><th>التصنيف</th><th style="text-align:center">السعر</th><th style="text-align:center">المسجلون</th></tr></thead>
        <tbody>' . $courseRows . '</tbody></table>

        <h2>آخر المعاملات المالية</h2>
        <table><thead><tr><th>الوصف</th><th style="text-align:center">المبلغ</th><th style="text-align:center">النوع</th><th>الطالب</th><th style="text-align:center">التاريخ</th></tr></thead>
        <tbody>' . $txRows . '</tbody></table>
        </body></html>';
    }

    public function gamification()
    {
        $leaderboard = User::where('role', 'student')
            ->withSum('points as total_points', 'amount')
            ->orderByDesc('total_points')
            ->limit(20)
            ->get();

        $badges = Badge::withCount('users')->orderBy('min_points')->get();
        $students = User::where('role', 'student')->get();

        $user = auth()->user();
        $myPoints = 0;
        $myBadges = collect();
        if ($user->role === 'student') {
            $myPoints = Point::where('student_id', $user->id)->sum('amount');
            $myBadges = Badge::whereHas('users', fn($q) => $q->where('user_id', $user->id))->get();
        }

        return view('dashboard.gamification', compact('leaderboard', 'badges', 'students', 'myPoints', 'myBadges'));
    }

    public function settings()
    {
        return view('dashboard.settings');
    }

    public function switchLocale(string $lang)
    {
        if (in_array($lang, ['ar', 'en'])) {
            session(['locale' => $lang]);
        }
        return redirect()->back();
    }

    // ── Installments CRUD ──────────────────────────────────────────
    public function installments()
    {
        return redirect()->route('dashboard.finance');
    }

    public function storeInstallment(Request $request)
    {
        Installment::create([
            'student_id'   => $request->student_id,
            'batch_id'     => $request->batch_id ?: null,
            'course_id'    => $request->course_id ?: null,
            'total_amount' => $request->total_amount,
            'paid_amount'  => $request->paid_amount ?? 0,
            'due_date'     => $request->due_date ?: null,
            'status'       => $request->status ?? 'pending',
            'notes'        => $request->notes,
        ]);
        return back()->with('success', 'تم إضافة خطة التقسيط بنجاح');
    }

    public function updateInstallment(Request $request, Installment $installment)
    {
        $installment->update($request->only(['total_amount', 'paid_amount', 'due_date', 'status', 'notes']));
        // auto-update status
        if ($installment->paid_amount >= $installment->total_amount) {
            $installment->update(['status' => 'paid']);
        } elseif ($installment->paid_amount > 0) {
            $installment->update(['status' => 'partial']);
        }
        return back()->with('success', 'تم تحديث التقسيط بنجاح');
    }

    public function destroyInstallment(Installment $installment)
    {
        $installment->delete();
        return back()->with('success', 'تم حذف التقسيط بنجاح');
    }

    // ── Gamification: Award Points / Badges ────────────────────────
    public function awardPoints(Request $request)
    {
        Point::create([
            'student_id' => $request->student_id,
            'amount'     => $request->amount,
            'reason'     => $request->reason,
        ]);

        // Auto-award badges based on total points
        $total = Point::where('student_id', $request->student_id)->sum('amount');
        $earnedBadges = Badge::where('min_points', '<=', $total)->pluck('id');
        $student = User::find($request->student_id);
        $student->badges()->syncWithoutDetaching($earnedBadges->map(fn($id) => [$id => ['awarded_at' => now()]])->collapse());

        return back()->with('success', 'تم منح النقاط بنجاح');
    }

    public function storeGamification(Request $request)
    {
        if ($request->filled('badge_name_ar')) {
            Badge::create([
                'name_ar'     => $request->badge_name_ar,
                'name_en'     => $request->badge_name_en ?? $request->badge_name_ar,
                'icon'        => $request->icon ?? '⭐',
                'description' => $request->description,
                'min_points'  => $request->min_points ?? 0,
            ]);
            return back()->with('success', 'تم إضافة الشارة بنجاح');
        }

        Point::create([
            'student_id' => $request->student_id,
            'amount'     => $request->amount,
            'reason'     => $request->reason,
        ]);
        return back()->with('success', 'تم منح النقاط بنجاح');
    }

    public function destroyBadge(Badge $badge)
    {
        $badge->delete();
        return back()->with('success', 'تم حذف الشارة بنجاح');
    }

    // ── Students CRUD ──────────────────────────────────────────────
    public function storeStudent(Request $request)
    {
        User::create([
            'name'     => $request->name_ar ?: $request->name,
            'name_ar'  => $request->name_ar,
            'name_en'  => $request->name_en,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'phone'    => $request->phone,
            'role'     => 'student',
            'status'   => 'active',
        ]);
        return back()->with('success', 'تم إضافة الطالب بنجاح');
    }

    public function updateStudent(Request $request, User $user)
    {
        $data = $request->only(['name_ar', 'name_en', 'email', 'phone', 'status']);
        if ($request->filled('name_ar')) {
            $data['name'] = $request->name_ar;
        }
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);
        return back()->with('success', 'تم تحديث بيانات الطالب بنجاح');
    }

    public function destroyStudent(User $user)
    {
        $user->delete();
        return back()->with('success', 'تم حذف الطالب بنجاح');
    }

    // ── Instructors CRUD ───────────────────────────────────────────
    public function storeInstructor(Request $request)
    {
        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request->password),
            'phone'     => $request->phone,
            'specialty' => $request->specialty,
            'role'      => 'instructor',
            'status'    => 'active',
        ]);
        return back()->with('success', 'تم إضافة المدرب بنجاح');
    }

    public function updateInstructor(Request $request, User $user)
    {
        $data = $request->only(['name', 'email', 'phone', 'specialty', 'status']);
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);
        return back()->with('success', 'تم تحديث بيانات المدرب بنجاح');
    }

    public function destroyInstructor(User $user)
    {
        $user->delete();
        return back()->with('success', 'تم حذف المدرب بنجاح');
    }

    // ── Courses CRUD ───────────────────────────────────────────────
    public function storeCourse(Request $request)
    {
        $data = [
            'title'             => $request->title,
            'description'       => $request->description,
            'content'           => $request->content,
            'features'          => $request->features,
            'accreditation'     => $request->accreditation,
            'job_opportunities' => $request->job_opportunities,
            'category'          => $request->category,
            'price'             => min((float)($request->price ?? 0), 9999999999999.99),
            'currency'          => $request->currency ?? 'USD',
            'duration'          => $request->duration,
            'level'             => $request->level,
            'status'            => $request->status ?? 'active',
            'is_featured'       => $request->boolean('is_featured'),
            'promo_video'       => $request->promo_video,
            'section_id'        => $request->section_id ?: null,
        ];
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('courses', 'public');
        }
        Course::create($data);
        return back()->with('success', 'تم إضافة الدورة بنجاح');
    }

    public function updateCourse(Request $request, Course $course)
    {
        $data = $request->only([
            'title', 'description', 'content', 'features', 'accreditation',
            'job_opportunities', 'category', 'price', 'currency', 'duration',
            'level', 'status', 'promo_video', 'section_id',
        ]);
        $data['is_featured'] = $request->boolean('is_featured');
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('courses', 'public');
        }
        $course->update($data);
        return back()->with('success', 'تم تحديث الدورة بنجاح');
    }

    // ── Committee Members CRUD ─────────────────────────────────────
    public function committeeMembers()
    {
        $members = CommitteeMember::orderBy('order')->get();
        return view('dashboard.committee', compact('members'));
    }

    public function storeCommitteeMember(Request $request)
    {
        $data = $request->only(['name', 'title', 'specialization', 'bio', 'order']);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('committee', 'public');
        }
        CommitteeMember::create($data);
        return back()->with('success', 'تم إضافة العضو بنجاح');
    }

    public function updateCommitteeMember(Request $request, CommitteeMember $committeeMember)
    {
        $data = $request->only(['name', 'title', 'specialization', 'bio', 'order']);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('committee', 'public');
        }
        $committeeMember->update($data);
        return back()->with('success', 'تم تحديث بيانات العضو بنجاح');
    }

    public function destroyCommitteeMember(CommitteeMember $committeeMember)
    {
        $committeeMember->delete();
        return back()->with('success', 'تم حذف العضو بنجاح');
    }

    public function destroyCourse(Course $course)
    {
        $course->delete();
        return back()->with('success', 'تم حذف الدورة بنجاح');
    }

    // ── Batches CRUD ───────────────────────────────────────────────
    public function storeBatch(Request $request)
    {
        Batch::create([
            'name'          => $request->name,
            'course_id'     => $request->course_id,
            'instructor_id' => $request->instructor_id,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
            'max_students'  => $request->max_students ?? 30,
            'status'        => $request->status ?? 'active',
        ]);
        return back()->with('success', 'تم إضافة المجموعة بنجاح');
    }

    public function updateBatch(Request $request, Batch $batch)
    {
        $batch->update($request->only(['name', 'course_id', 'instructor_id', 'start_date', 'end_date', 'max_students', 'status']));
        return back()->with('success', 'تم تحديث المجموعة بنجاح');
    }

    public function destroyBatch(Batch $batch)
    {
        $batch->delete();
        return back()->with('success', 'تم حذف المجموعة بنجاح');
    }

    // ── Resources CRUD ─────────────────────────────────────────────
    public function storeResource(Request $request)
    {
        $fileUrl = $request->file_url;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('resources', 'public');
            $fileUrl = Storage::url($path);
        }
        Resource::create([
            'title'         => $request->title,
            'type'          => $request->type ?? 'PDF',
            'file_url'      => $fileUrl,
            'course_id'     => $request->course_id ?: null,
            'batch_id'      => $request->batch_id ?: null,
            'instructor_id' => auth()->id(),
        ]);
        return back()->with('success', 'تم إضافة المحتوى بنجاح');
    }

    public function updateResource(Request $request, Resource $resource)
    {
        $resource->update($request->only(['title', 'type', 'file_url', 'course_id', 'batch_id']));
        return back()->with('success', 'تم تحديث المحتوى بنجاح');
    }

    public function destroyResource(Resource $resource)
    {
        $resource->delete();
        return back()->with('success', 'تم حذف المحتوى بنجاح');
    }


    // ── Transactions CRUD (with payment proof upload) ───────────────
    public function storeTransaction(Request $request)
    {
        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
        }

        Transaction::create([
            'description'   => $request->description,
            'currency'      => $request->currency ?? 'EGP',
            'amount'        => $request->amount,
            'type'          => $request->type,
            'method'        => $request->method,
            'user_id'       => $request->user_id ?: null,
            'status'        => $request->status ?? 'completed',
            'payment_proof' => $proofPath,
        ]);
        return back()->with('success', 'تم إضافة المعاملة المالية بنجاح');
    }

    public function updateTransaction(Request $request, Transaction $transaction)
    {
        $data = $request->only(['description', 'amount', 'currency', 'type', 'method', 'status']);
        if ($request->hasFile('payment_proof')) {
            $data['payment_proof'] = $request->file('payment_proof')->store('payment-proofs', 'public');
        }
        $transaction->update($data);
        return back()->with('success', 'تم تحديث المعاملة المالية بنجاح');
    }

    public function destroyTransaction(Transaction $transaction)
    {
        $transaction->delete();
        return back()->with('success', 'تم حذف المعاملة المالية بنجاح');
    }

    // ── Notifications ──────────────────────────────────────────────
    public function storeNotification(Request $request)
    {
        $user    = auth()->user();
        $text    = $request->text;
        $type    = $request->type ?? 'general';
        $batchId = $request->batch_id ?: null;

        if ($batchId) {
            // Security: instructor can only send to their own batch
            if ($user->role === 'instructor') {
                $batch = Batch::where('id', $batchId)->where('instructor_id', $user->id)->first();
                if (!$batch) return back()->with('error', 'غير مصرح لك بإرسال إشعار لهذه المجموعة');
            }
            $studentIds = Enrollment::where('batch_id', $batchId)->pluck('student_id');
        } elseif ($user->role === 'instructor') {
            // Instructor "all" = only their own students
            $batchIds   = Batch::where('instructor_id', $user->id)->pluck('id');
            $studentIds = Enrollment::whereIn('batch_id', $batchIds)->pluck('student_id')->unique();
        } else {
            $studentIds = User::where('role', 'student')->pluck('id');
        }

        if ($studentIds->isEmpty()) {
            return back()->with('error', 'لا يوجد طلاب مسجلون في هذه المجموعة');
        }

        foreach ($studentIds as $userId) {
            Notification::create([
                'user_id'  => $userId,
                'text'     => $text,
                'type'     => $type,
                'batch_id' => $batchId,
                'is_read'  => false,
            ]);
        }
        return back()->with('success', 'تم إرسال الإشعار بنجاح لـ ' . $studentIds->count() . ' طالب');
    }

    public function markNotificationRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);
        return back();
    }

    // ── Settings ───────────────────────────────────────────────────
    public function updateSettings(Request $request)
    {
        $user = auth()->user();
        $data = $request->only(['name', 'email', 'phone']);
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);
        return back()->with('success', 'تم تحديث المعلومات بنجاح');
    }

    // ── Exams CRUD ─────────────────────────────────────────────────
    public function storeExam(Request $request)
    {
        Exam::create([
            'title'     => $request->title,
            'course_id' => $request->course_id,
            'type'      => $request->type ?? 'quiz',
            'questions' => $request->questions ?? 30,
            'duration'  => $request->duration,
            'attempts'  => $request->attempts ?? 1,
            'status'    => $request->status ?? 'active',
            'exam_link' => $request->exam_link ?: null,
        ]);
        return back()->with('success', 'تم إضافة الاختبار بنجاح');
    }

    public function updateExam(Request $request, Exam $exam)
    {
        $exam->update($request->only(['title', 'course_id', 'type', 'questions', 'duration', 'attempts', 'status', 'exam_link']));
        return back()->with('success', 'تم تحديث الاختبار بنجاح');
    }

    public function destroyExam(Exam $exam)
    {
        $exam->delete();
        return back()->with('success', 'تم حذف الاختبار بنجاح');
    }

    // ── Live Sessions CRUD ─────────────────────────────────────────
    public function storeLiveSession(Request $request)
    {
        LiveSession::create([
            'title'         => $request->title,
            'live_url'      => $request->live_url,
            'batch_id'      => $request->batch_id,
            'instructor_id' => auth()->id(),
            'scheduled_at'  => $request->scheduled_at,
            'status'        => $request->status ?? 'scheduled',
        ]);
        return back()->with('success', 'تم إضافة جلسة البث بنجاح');
    }

    public function updateLiveSession(Request $request, LiveSession $liveSession)
    {
        $liveSession->update($request->only(['title', 'live_url', 'batch_id', 'scheduled_at', 'status']));
        return back()->with('success', 'تم تحديث جلسة البث بنجاح');
    }

    public function destroyLiveSession(LiveSession $liveSession)
    {
        $liveSession->delete();
        return back()->with('success', 'تم حذف جلسة البث بنجاح');
    }
}
