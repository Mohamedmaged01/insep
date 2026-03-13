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
                ['label' => 'الإيرادات', 'value' => Transaction::where('type', 'income')->sum('amount'), 'color' => 'from-purple-500 to-purple-600', 'icon' => 'dollar'],
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
        $batches = Batch::with(['course', 'instructor'])->orderBy('created_at', 'desc')->get();
        return view('dashboard.batches', compact('batches'));
    }

    public function myCourses()
    {
        $enrollments = Enrollment::where('student_id', auth()->id())->with('course', 'batch')->get();
        return view('dashboard.mycourses', compact('enrollments'));
    }

    public function attendance()
    {
        $batches = Batch::with('course')->get();
        return view('dashboard.attendance', compact('batches'));
    }

    public function resources()
    {
        $resources = Resource::with('course')->orderBy('created_at', 'desc')->get();
        return view('dashboard.resources', compact('resources'));
    }

    public function liveSessions()
    {
        $sessions = LiveSession::with('batch')->orderBy('scheduled_at', 'desc')->get();
        return view('dashboard.live-sessions', compact('sessions'));
    }

    public function exams()
    {
        $exams = Exam::with('course')->orderBy('created_at', 'desc')->get();
        return view('dashboard.exams', compact('exams'));
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
            $summary = [
                'income' => Transaction::where('type', 'income')->sum('amount'),
                'expense' => Transaction::where('type', 'expense')->sum('amount'),
            ];
        } else {
            $transactions = Transaction::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
            $summary = [
                'income' => $transactions->where('type', 'income')->sum('amount'),
                'expense' => $transactions->where('type', 'expense')->sum('amount'),
            ];
        }
        return view('dashboard.finance', compact('transactions', 'summary'));
    }

    public function notifications()
    {
        $user = auth()->user();
        if ($user->role === 'student') {
            $notifications = Notification::where('user_id', $user->id)->orWhereNull('user_id')->orderBy('created_at', 'desc')->get();
        } else {
            $notifications = Notification::orderBy('created_at', 'desc')->get();
        }
        return view('dashboard.notifications', compact('notifications'));
    }

    public function departments()
    {
        $departments = Department::orderBy('created_at', 'desc')->get();
        return view('dashboard.departments', compact('departments'));
    }

    public function reports()
    {
        return view('dashboard.reports');
    }

    public function settings()
    {
        return view('dashboard.settings');
    }
}
