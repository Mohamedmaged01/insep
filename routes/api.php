<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\LiveSessionController;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::middleware('auth:api')->group(function () {
        Route::get('profile', [AuthController::class, 'profile']);
        Route::put('profile', [AuthController::class, 'updateProfile']);
        Route::put('password', [AuthController::class, 'changePassword']);
    });
});

/*
|--------------------------------------------------------------------------
| Users (admin only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::post('users', [UserController::class, 'store']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Courses (public read, admin CUD)
|--------------------------------------------------------------------------
*/
Route::get('courses', [CourseController::class, 'index']);
Route::get('courses/{id}', [CourseController::class, 'show']);
Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::post('courses', [CourseController::class, 'store']);
    Route::put('courses/{id}', [CourseController::class, 'update']);
    Route::delete('courses/{id}', [CourseController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Batches (auth required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->group(function () {
    Route::get('batches', [BatchController::class, 'index']);
    Route::get('batches/{id}', [BatchController::class, 'show']);

    Route::middleware('role:admin')->group(function () {
        Route::post('batches', [BatchController::class, 'store']);
        Route::put('batches/{id}', [BatchController::class, 'update']);
        Route::delete('batches/{id}', [BatchController::class, 'destroy']);
    });
});

/*
|--------------------------------------------------------------------------
| Enrollments (auth required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->group(function () {
    Route::get('enrollments/my', [EnrollmentController::class, 'my']);
    Route::put('enrollments/{id}', [EnrollmentController::class, 'update']);

    Route::middleware('role:admin')->group(function () {
        Route::get('enrollments', [EnrollmentController::class, 'index']);
        Route::post('enrollments', [EnrollmentController::class, 'store']);
        Route::delete('enrollments/{id}', [EnrollmentController::class, 'destroy']);
    });
});

/*
|--------------------------------------------------------------------------
| Exams (auth required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->group(function () {
    Route::get('exams', [ExamController::class, 'index']);
    Route::get('exams/student/my-results', [ExamController::class, 'myResults']);
    Route::get('exams/{id}', [ExamController::class, 'show']);
    Route::post('exams/{id}/submit', [ExamController::class, 'submitResult']);
    Route::get('exams/{id}/results', [ExamController::class, 'getResults']);

    Route::middleware('role:admin,instructor')->group(function () {
        Route::post('exams', [ExamController::class, 'store']);
        Route::put('exams/{id}', [ExamController::class, 'update']);
    });

    Route::middleware('role:admin')->group(function () {
        Route::delete('exams/{id}', [ExamController::class, 'destroy']);
    });
});

/*
|--------------------------------------------------------------------------
| Certificates (public verify, auth for rest)
|--------------------------------------------------------------------------
*/
Route::get('certificates/verify/{serial}', [CertificateController::class, 'verify']);
Route::middleware('auth:api')->group(function () {
    Route::get('certificates', [CertificateController::class, 'index']);

    Route::middleware('role:admin')->group(function () {
        Route::post('certificates', [CertificateController::class, 'store']);
        Route::put('certificates/{id}', [CertificateController::class, 'update']);
        Route::delete('certificates/{id}', [CertificateController::class, 'destroy']);
    });
});

/*
|--------------------------------------------------------------------------
| Finance (auth required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->group(function () {
    Route::get('finance/transactions', [FinanceController::class, 'transactions']);

    Route::middleware('role:admin')->group(function () {
        Route::get('finance/summary', [FinanceController::class, 'summary']);
        Route::post('finance/transactions', [FinanceController::class, 'store']);
        Route::delete('finance/transactions/{id}', [FinanceController::class, 'destroy']);
    });
});

/*
|--------------------------------------------------------------------------
| Attendance (auth required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->group(function () {
    Route::get('attendance', [AttendanceController::class, 'index']);
    Route::get('attendance/batch/{batchId}/students', [AttendanceController::class, 'studentsByBatch']);
    Route::get('attendance/stats/{batchId}', [AttendanceController::class, 'stats']);

    Route::middleware('role:admin,instructor')->group(function () {
        Route::post('attendance', [AttendanceController::class, 'recordBulk']);
        Route::put('attendance/{id}', [AttendanceController::class, 'update']);
    });
});

/*
|--------------------------------------------------------------------------
| Resources (auth required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->group(function () {
    Route::get('resources', [ResourceController::class, 'index']);
    Route::post('resources/{id}/download', [ResourceController::class, 'download']);

    Route::middleware('role:admin,instructor')->group(function () {
        Route::post('resources', [ResourceController::class, 'store']);
        Route::post('resources/upload', [ResourceController::class, 'upload']);
        Route::delete('resources/{id}', [ResourceController::class, 'destroy']);
    });
});

/*
|--------------------------------------------------------------------------
| Contact (public create, admin manage)
|--------------------------------------------------------------------------
*/
Route::post('contact', [ContactController::class, 'store']);
Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('contact', [ContactController::class, 'index']);
    Route::put('contact/{id}/read', [ContactController::class, 'markRead']);
    Route::delete('contact/{id}', [ContactController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| News (public read, admin CUD)
|--------------------------------------------------------------------------
*/
Route::get('news', [NewsController::class, 'index']);
Route::get('news/{id}', [NewsController::class, 'show']);
Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::post('news', [NewsController::class, 'store']);
    Route::put('news/{id}', [NewsController::class, 'update']);
    Route::delete('news/{id}', [NewsController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Dashboard (auth required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->group(function () {
    Route::get('dashboard/admin', [DashboardController::class, 'admin'])->middleware('role:admin');
    Route::get('dashboard/student', [DashboardController::class, 'student']);
    Route::get('dashboard/instructor', [DashboardController::class, 'instructor']);
});

/*
|--------------------------------------------------------------------------
| Reports (admin only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api', 'role:admin'])->prefix('reports')->group(function () {
    Route::get('enrollment', [ReportController::class, 'enrollment']);
    Route::get('performance', [ReportController::class, 'performance']);
    Route::get('revenue', [ReportController::class, 'revenue']);
    Route::get('attendance', [ReportController::class, 'attendance']);
    Route::get('certificates', [ReportController::class, 'certificates']);
});

/*
|--------------------------------------------------------------------------
| Notifications (auth required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->group(function () {
    Route::get('notifications/my', [NotificationController::class, 'my']);
    Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::put('notifications/{id}/read', [NotificationController::class, 'markRead']);

    Route::middleware('role:admin')->group(function () {
        Route::get('notifications', [NotificationController::class, 'index']);
    });

    Route::middleware('role:admin,instructor')->group(function () {
        Route::post('notifications/batch/{batchId}', [NotificationController::class, 'sendToBatch']);
    });
});

/*
|--------------------------------------------------------------------------
| Live Sessions (auth required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->group(function () {
    Route::get('live-sessions', [LiveSessionController::class, 'index']);

    Route::middleware('role:admin,instructor')->group(function () {
        Route::post('live-sessions', [LiveSessionController::class, 'store']);
        Route::put('live-sessions/{id}', [LiveSessionController::class, 'update']);
        Route::delete('live-sessions/{id}', [LiveSessionController::class, 'destroy']);
    });
});

