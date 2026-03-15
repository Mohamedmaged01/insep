<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
use App\Models\Department;

class DashboardWebController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $stats = [];

        if ($user->role === 'admin') {
            $stats = [
                ['label' => 'إجمالي الطلاب', 'value' => User::where('role', 'student')->count(), 'color' => 'from-blue-500 to-blue-600', 'icon' => 'users'],
                ['label' => 'الدورات النشطة', 'value' => Course::count(), 'color' => 'from-green-500 to-green-600', 'icon' => 'book'],
                ['label' => 'الإيرادات', 'value' => number_format(Transaction::where('type', 'income')->sum('amount')) . ' ج.م', 'color' => 'from-purple-500 to-purple-600', 'icon' => 'dollar'],
                ['label' => 'الشهادات الصادرة', 'value' => Certificate::count(), 'color' => 'from-orange-500 to-orange-600', 'icon' => 'award'],
            ];
        } elseif ($user->role === 'student') {
            $stats = [
                ['label' => 'دوراتي المسجلة', 'value' => Enrollment::where('student_id', $user->id)->count(), 'color' => 'from-blue-500 to-blue-600', 'icon' => 'book'],
                ['label' => 'الشهادات المحصلة', 'value' => Certificate::where('student_id', $user->id)->count(), 'color' => 'from-green-500 to-green-600', 'icon' => 'award'],
                ['label' => 'نسبة الحضور', 'value' => '0%', 'color' => 'from-purple-500 to-purple-600', 'icon' => 'check'],
                ['label' => 'المعدل العام', 'value' => '0%', 'color' => 'from-orange-500 to-orange-600', 'icon' => 'star'],
            ];
        } else {
            $stats = [
                ['label' => 'المجموعات الحالية', 'value' => Batch::where('instructor_id', $user->id)->count(), 'color' => 'from-blue-500 to-blue-600', 'icon' => 'clipboard'],
                ['label' => 'إجمالي الطلاب', 'value' => 0, 'color' => 'from-green-500 to-green-600', 'icon' => 'users'],
                ['label' => 'الساعات التدريبية', 'value' => 0, 'color' => 'from-purple-500 to-purple-600', 'icon' => 'clock'],
                ['label' => 'التقييم العام', 'value' => '-', 'color' => 'from-orange-500 to-orange-600', 'icon' => 'star'],
            ];
        }

        return view('dashboard.home', compact('stats'));
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
        $courses = Course::withCount('enrollments')->orderBy('created_at', 'desc')->get();
        return view('dashboard.courses', compact('courses'));
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
        return view('dashboard.batch-detail', compact('batch', 'resources', 'liveSessions', 'allStudents', 'enrolledIds'));
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
        } else {
            $transactions = Transaction::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        }
        $summary = [
            'income'  => $transactions->where('type', 'income')->sum('amount'),
            'expense' => $transactions->where('type', 'expense')->sum('amount'),
        ];
        $students = User::where('role', 'student')->get();
        return view('dashboard.finance', compact('transactions', 'summary', 'students'));
    }

    public function notifications()
    {
        $user = auth()->user();
        if ($user->role === 'student') {
            $notifications = Notification::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        } else {
            $notifications = Notification::with('user')->orderBy('created_at', 'desc')->get();
        }
        $batches = Batch::with('course')->orderBy('id', 'desc')->get();
        return view('dashboard.notifications', compact('notifications', 'batches'));
    }

    public function departments()
    {
        $departments = Department::orderBy('created_at', 'desc')->get();
        return view('dashboard.departments', compact('departments'));
    }

    public function reports()
    {
        $stats = [
            'students'     => User::where('role', 'student')->count(),
            'instructors'  => User::where('role', 'instructor')->count(),
            'courses'      => Course::count(),
            'batches'      => Batch::count(),
            'enrollments'  => Enrollment::count(),
            'certificates' => Certificate::count(),
            'income'       => Transaction::where('type', 'income')->sum('amount'),
            'expense'      => Transaction::where('type', 'expense')->sum('amount'),
        ];
        $recentStudents    = User::where('role', 'student')->orderBy('created_at', 'desc')->limit(10)->get();
        $recentCourses     = Course::withCount('enrollments')->orderBy('created_at', 'desc')->limit(10)->get();
        $recentTransactions = Transaction::with('user')->orderBy('created_at', 'desc')->limit(10)->get();
        return view('dashboard.reports', compact('stats', 'recentStudents', 'recentCourses', 'recentTransactions'));
    }

    public function settings()
    {
        return view('dashboard.settings');
    }

    // ── Students CRUD ──────────────────────────────────────────────
    public function storeStudent(Request $request)
    {
        User::create([
            'name'     => $request->name,
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
        $data = $request->only(['name', 'email', 'phone', 'status']);
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
        Course::create([
            'title'       => $request->title,
            'description' => $request->description,
            'category'    => $request->category,
            'price'       => $request->price ?? 0,
            'duration'    => $request->duration,
            'level'       => $request->level,
            'status'      => $request->status ?? 'active',
        ]);
        return back()->with('success', 'تم إضافة الدورة بنجاح');
    }

    public function updateCourse(Request $request, Course $course)
    {
        $course->update($request->only(['title', 'description', 'category', 'price', 'duration', 'level', 'status']));
        return back()->with('success', 'تم تحديث الدورة بنجاح');
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
        Resource::create([
            'title'         => $request->title,
            'type'          => $request->type ?? 'PDF',
            'file_url'      => $request->file_url,
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

    // ── Departments CRUD ───────────────────────────────────────────
    public function storeDepartment(Request $request)
    {
        Department::create([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
        ]);
        return back()->with('success', 'تم إضافة القسم بنجاح');
    }

    public function updateDepartment(Request $request, Department $department)
    {
        $department->update($request->only(['name_ar', 'name_en']));
        return back()->with('success', 'تم تحديث القسم بنجاح');
    }

    public function destroyDepartment(Department $department)
    {
        $department->delete();
        return back()->with('success', 'تم حذف القسم بنجاح');
    }

    // ── Transactions CRUD ──────────────────────────────────────────
    public function storeTransaction(Request $request)
    {
        Transaction::create([
            'description' => $request->description,
            'amount'      => $request->amount,
            'type'        => $request->type,
            'method'      => $request->method,
            'user_id'     => $request->user_id ?: null,
            'status'      => $request->status ?? 'completed',
        ]);
        return back()->with('success', 'تم إضافة المعاملة المالية بنجاح');
    }

    public function updateTransaction(Request $request, Transaction $transaction)
    {
        $transaction->update($request->only(['description', 'amount', 'type', 'method', 'status']));
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
        $text    = $request->text;
        $type    = $request->type ?? 'general';
        $batchId = $request->batch_id ?: null;

        if ($batchId) {
            $studentIds = Enrollment::where('batch_id', $batchId)->pluck('student_id');
        } else {
            $studentIds = User::where('role', 'student')->pluck('id');
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
        return back()->with('success', 'تم إرسال الإشعار بنجاح');
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
        ]);
        return back()->with('success', 'تم إضافة الاختبار بنجاح');
    }

    public function updateExam(Request $request, Exam $exam)
    {
        $exam->update($request->only(['title', 'course_id', 'type', 'questions', 'duration', 'attempts', 'status']));
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
