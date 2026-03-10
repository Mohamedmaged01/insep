<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\DashboardWebController;

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/courses', [PageController::class, 'courses'])->name('courses');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
Route::get('/verify', [PageController::class, 'verify'])->name('verify');

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
    Route::get('/students', [DashboardWebController::class, 'students'])->name('dashboard.students');
    Route::get('/instructors', [DashboardWebController::class, 'instructors'])->name('dashboard.instructors');
    Route::get('/courses', [DashboardWebController::class, 'courses'])->name('dashboard.courses');
    Route::get('/batches', [DashboardWebController::class, 'batches'])->name('dashboard.batches');
    Route::get('/mycourses', [DashboardWebController::class, 'myCourses'])->name('dashboard.mycourses');
    Route::get('/attendance', [DashboardWebController::class, 'attendance'])->name('dashboard.attendance');
    Route::get('/resources', [DashboardWebController::class, 'resources'])->name('dashboard.resources');
    Route::get('/live-sessions', [DashboardWebController::class, 'liveSessions'])->name('dashboard.live-sessions');
    Route::get('/exams', [DashboardWebController::class, 'exams'])->name('dashboard.exams');
    Route::get('/certificates', [DashboardWebController::class, 'certificates'])->name('dashboard.certificates');
    Route::get('/finance', [DashboardWebController::class, 'finance'])->name('dashboard.finance');
    Route::get('/notifications', [DashboardWebController::class, 'notifications'])->name('dashboard.notifications');
    Route::get('/departments', [DashboardWebController::class, 'departments'])->name('dashboard.departments');
    Route::get('/reports', [DashboardWebController::class, 'reports'])->name('dashboard.reports');
    Route::get('/settings', [DashboardWebController::class, 'settings'])->name('dashboard.settings');
});
