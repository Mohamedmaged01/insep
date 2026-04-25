<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\DashboardWebController;
use App\Http\Controllers\CmsController;

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/
// Public locale switch (works for both guests and authenticated users)
Route::get('/locale/{lang}', [DashboardWebController::class, 'switchLocale'])->name('locale.switch');

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/courses', [PageController::class, 'courses'])->name('courses');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
Route::get('/verify', [PageController::class, 'verify'])->name('verify');
Route::get('/courses/{id}', [PageController::class, 'courseDetail'])->name('course.detail');
Route::get('/platform-policy', [PageController::class, 'platformPolicy'])->name('platform-policy');
Route::get('/user-guide', [PageController::class, 'userGuide'])->name('user-guide');
Route::get('/support', [PageController::class, 'support'])->name('support');
Route::get('/news/{id}', [PageController::class, 'newsShow'])->name('news.show');
Route::get('/scientific-committee', [PageController::class, 'scientificCommittee'])->name('scientific-committee');

/*
|--------------------------------------------------------------------------
| Auth (Web)
|--------------------------------------------------------------------------
*/
Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [WebAuthController::class, 'login']);
Route::get('/register', [WebAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [WebAuthController::class, 'register']);
Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboard (Auth required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardWebController::class, 'index'])->name('dashboard');

    // Language switch
    Route::get('/locale/{lang}', [DashboardWebController::class, 'switchLocale'])->name('dashboard.locale');

    // Main sections (GET)
    Route::get('/users', [DashboardWebController::class, 'usersManagement'])->middleware('web.role:admin')->name('dashboard.users');
    Route::post('/users', [DashboardWebController::class, 'storeUser'])->middleware('web.role:admin')->name('dashboard.users.store');
    Route::put('/users/{user}', [DashboardWebController::class, 'updateUser'])->middleware('web.role:admin')->name('dashboard.users.update');
    Route::delete('/users/{user}', [DashboardWebController::class, 'destroyUser'])->middleware('web.role:admin')->name('dashboard.users.destroy');
    Route::get('/students', [DashboardWebController::class, 'students'])->name('dashboard.students');
    Route::get('/instructors', [DashboardWebController::class, 'instructors'])->name('dashboard.instructors');
    Route::get('/courses', [DashboardWebController::class, 'courses'])->name('dashboard.courses');
    Route::get('/batches', [DashboardWebController::class, 'batches'])->name('dashboard.batches');
    Route::get('/mycourses', [DashboardWebController::class, 'myCourses'])->name('dashboard.mycourses');
    Route::get('/mycourses/{enrollment}', [DashboardWebController::class, 'myCourseDetail'])->name('dashboard.mycourses.detail');
    Route::get('/attendance', [DashboardWebController::class, 'attendance'])->name('dashboard.attendance');
    Route::get('/resources', [DashboardWebController::class, 'resources'])->name('dashboard.resources');
    Route::get('/live-sessions', [DashboardWebController::class, 'liveSessions'])->name('dashboard.live-sessions');
    Route::get('/exams', [DashboardWebController::class, 'exams'])->name('dashboard.exams');
    Route::get('/certificates', [DashboardWebController::class, 'certificates'])->name('dashboard.certificates');
    Route::post('/certificates', [DashboardWebController::class, 'storeCertificate'])->name('dashboard.certificates.store');
    Route::delete('/certificates/{id}', [DashboardWebController::class, 'destroyCertificate'])->name('dashboard.certificates.destroy');
    Route::get('/certificates/{id}/download', [DashboardWebController::class, 'downloadCertificate'])->name('dashboard.certificates.download');
    Route::post('/certificates/bulk', [DashboardWebController::class, 'bulkUploadCertificates'])->name('dashboard.certificates.bulk');
    Route::get('/certificates/template', [DashboardWebController::class, 'downloadCertificateTemplate'])->name('dashboard.certificates.template');
    Route::post('/certificates/import', [DashboardWebController::class, 'importCertificates'])->name('dashboard.certificates.import');

    // Certificate requests
    Route::get('/certificate-requests', [DashboardWebController::class, 'certificateRequests'])->name('dashboard.certificate-requests');
    Route::post('/certificate-requests', [DashboardWebController::class, 'storeCertificateRequest'])->name('dashboard.certificate-requests.store');
    Route::patch('/certificate-requests/{id}', [DashboardWebController::class, 'updateCertificateRequest'])->name('dashboard.certificate-requests.update');
    Route::get('/finance', [DashboardWebController::class, 'finance'])->middleware('web.role:admin,finance')->name('dashboard.finance');
    Route::get('/notifications', [DashboardWebController::class, 'notifications'])->name('dashboard.notifications');
    Route::get('/sections', [DashboardWebController::class, 'sections'])->name('dashboard.sections');
    Route::get('/reports', [DashboardWebController::class, 'reports'])->middleware('web.role:admin,finance')->name('dashboard.reports');
    Route::get('/reports/export', [DashboardWebController::class, 'exportReports'])->middleware('web.role:admin,finance')->name('dashboard.reports.export');
    Route::get('/gamification', [DashboardWebController::class, 'gamification'])->name('dashboard.gamification');
    Route::get('/settings', [DashboardWebController::class, 'settings'])->name('dashboard.settings');

    // Students CRUD
    Route::post('/students', [DashboardWebController::class, 'storeStudent'])->name('dashboard.students.store');
    Route::put('/students/{user}', [DashboardWebController::class, 'updateStudent'])->name('dashboard.students.update');
    Route::delete('/students/{user}', [DashboardWebController::class, 'destroyStudent'])->name('dashboard.students.destroy');

    // Instructors CRUD
    Route::post('/instructors', [DashboardWebController::class, 'storeInstructor'])->name('dashboard.instructors.store');
    Route::put('/instructors/{user}', [DashboardWebController::class, 'updateInstructor'])->name('dashboard.instructors.update');
    Route::delete('/instructors/{user}', [DashboardWebController::class, 'destroyInstructor'])->name('dashboard.instructors.destroy');

    // Courses CRUD
    Route::post('/courses', [DashboardWebController::class, 'storeCourse'])->name('dashboard.courses.store');
    Route::put('/courses/{course}', [DashboardWebController::class, 'updateCourse'])->name('dashboard.courses.update');
    Route::delete('/courses/{course}', [DashboardWebController::class, 'destroyCourse'])->name('dashboard.courses.destroy');
    Route::patch('/courses/{course}/toggle-featured', [DashboardWebController::class, 'toggleCourseFeatured'])->name('dashboard.courses.toggle-featured');

    // Batches CRUD + Detail
    Route::post('/batches', [DashboardWebController::class, 'storeBatch'])->name('dashboard.batches.store');
    Route::put('/batches/{batch}', [DashboardWebController::class, 'updateBatch'])->name('dashboard.batches.update');
    Route::delete('/batches/{batch}', [DashboardWebController::class, 'destroyBatch'])->name('dashboard.batches.destroy');
    Route::get('/batches/{batch}/detail', [DashboardWebController::class, 'batchDetail'])->name('dashboard.batches.detail');
    Route::post('/batches/{batch}/enroll', [DashboardWebController::class, 'enrollStudent'])->name('dashboard.batches.enroll');
    Route::delete('/batches/{batch}/enrollments/{enrollment}', [DashboardWebController::class, 'unenrollStudent'])->name('dashboard.batches.unenroll');

    // Resources CRUD
    Route::post('/resources', [DashboardWebController::class, 'storeResource'])->name('dashboard.resources.store');
    Route::put('/resources/{resource}', [DashboardWebController::class, 'updateResource'])->name('dashboard.resources.update');
    Route::delete('/resources/{resource}', [DashboardWebController::class, 'destroyResource'])->name('dashboard.resources.destroy');

    // Sections CRUD
    Route::post('/sections', [DashboardWebController::class, 'storeSection'])->name('dashboard.sections.store');
    Route::put('/sections/{section}', [DashboardWebController::class, 'updateSection'])->name('dashboard.sections.update');
    Route::delete('/sections/{section}', [DashboardWebController::class, 'destroySection'])->name('dashboard.sections.destroy');


    // Finance / Transactions CRUD
    Route::post('/finance', [DashboardWebController::class, 'storeTransaction'])->name('dashboard.finance.store');
    Route::put('/finance/{transaction}', [DashboardWebController::class, 'updateTransaction'])->name('dashboard.finance.update');
    Route::delete('/finance/{transaction}', [DashboardWebController::class, 'destroyTransaction'])->name('dashboard.finance.destroy');

    // Installments CRUD
    Route::post('/installments', [DashboardWebController::class, 'storeInstallment'])->name('dashboard.installments.store');
    Route::put('/installments/{installment}', [DashboardWebController::class, 'updateInstallment'])->name('dashboard.installments.update');
    Route::delete('/installments/{installment}', [DashboardWebController::class, 'destroyInstallment'])->name('dashboard.installments.destroy');

    // Gamification
    Route::post('/gamification/points', [DashboardWebController::class, 'storeGamification'])->name('dashboard.gamification.points');
    Route::post('/gamification/badges', [DashboardWebController::class, 'storeGamification'])->name('dashboard.gamification.badges');
    Route::delete('/gamification/badges/{badge}', [DashboardWebController::class, 'destroyBadge'])->name('dashboard.gamification.badges.destroy');

    // Attendance per batch
    Route::get('/attendance/{batch}', [DashboardWebController::class, 'attendanceBatch'])->name('dashboard.attendance.batch');
    Route::post('/attendance/{batch}', [DashboardWebController::class, 'storeAttendance'])->name('dashboard.attendance.store');

    // Notifications
    Route::post('/notifications', [DashboardWebController::class, 'storeNotification'])->name('dashboard.notifications.store');
    Route::post('/notifications/{notification}/read', [DashboardWebController::class, 'markNotificationRead'])->name('dashboard.notifications.read');

    // Settings
    Route::post('/settings', [DashboardWebController::class, 'updateSettings'])->name('dashboard.settings.update');

    // CMS — admin only
    Route::get('/cms', [CmsController::class, 'index'])->middleware('web.role:admin')->name('dashboard.cms');
    Route::post('/cms', [CmsController::class, 'update'])->middleware('web.role:admin')->name('dashboard.cms.update');

    // Scientific Committee — admin only
    Route::get('/committee', [DashboardWebController::class, 'committeeMembers'])->middleware('web.role:admin')->name('dashboard.committee');
    Route::post('/committee', [DashboardWebController::class, 'storeCommitteeMember'])->middleware('web.role:admin')->name('dashboard.committee.store');
    Route::put('/committee/{committeeMember}', [DashboardWebController::class, 'updateCommitteeMember'])->middleware('web.role:admin')->name('dashboard.committee.update');
    Route::delete('/committee/{committeeMember}', [DashboardWebController::class, 'destroyCommitteeMember'])->middleware('web.role:admin')->name('dashboard.committee.destroy');

    // Public course detail
    Route::get('/courses/{id}', [DashboardWebController::class, 'courseDetail'])->name('dashboard.courses.show');

    // Exams CRUD
    Route::post('/exams', [DashboardWebController::class, 'storeExam'])->name('dashboard.exams.store');
    Route::put('/exams/{exam}', [DashboardWebController::class, 'updateExam'])->name('dashboard.exams.update');
    Route::delete('/exams/{exam}', [DashboardWebController::class, 'destroyExam'])->name('dashboard.exams.destroy');

    // Live Sessions CRUD
    Route::post('/live-sessions', [DashboardWebController::class, 'storeLiveSession'])->name('dashboard.live-sessions.store');
    Route::put('/live-sessions/{liveSession}', [DashboardWebController::class, 'updateLiveSession'])->name('dashboard.live-sessions.update');
    Route::delete('/live-sessions/{liveSession}', [DashboardWebController::class, 'destroyLiveSession'])->name('dashboard.live-sessions.destroy');
});
