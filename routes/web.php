<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PaymentController;

Route::get('/', [AuthController::class, 'showLogin']);

// Auth routes
Route::get('/signup', [AuthController::class, 'showSignup']);
Route::post('/signup', [AuthController::class, 'signup']);
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);



// Firebase test route
Route::get('/firebase-test', function(\App\Services\FirebaseService $firebase){
    $firebase->db()->getReference('test')->set(['time' => now()->toDateTimeString()]);
    return 'Successfully written to Firebase Realtime DB!';
});

// List all courses
Route::get('/courses', [CourseController::class, 'index']);
// Show course details / enrolment page
Route::get('/courses/{id}', [CourseController::class, 'show']);
// Process payment for enrolment
Route::post('/courses/{id}/payment', [PaymentController::class, 'process']);
// Access course materials (check payment inside controller)
Route::get('/courses/{id}/materials', [CourseController::class, 'materials']);

//student routes
Route::middleware(['firebase.session', 'role:student'])->group(function () {
    Route::get('/student_dashboard', [StudentController::class, 'index'])
        ->name('student.dashboard');
    // Course overview page
    Route::get('/student/courses', [StudentController::class, 'courseOverview'])
        ->name('student.course.overview');
    // Submit report (POST)
    Route::post('/student/report', [StudentController::class, 'reportCourse'])
        ->name('student.report.submit');
    Route::get('/student/reports', [StudentController::class, 'reportStatus'])
     ->name('student.report.status');
});

//admin routes
Route::middleware(['firebase.session', 'role:admin'])->group(function () {
    Route::get('/admin_dashboard', [AdminController::class, 'index']);
    Route::get('/admin/manage-reports', [AdminController::class, 'manageReports'])->name('admin.manage.reports');
    Route::post('/admin/report/resolve',[AdminController::class, 'resolveReport'])->name('admin.resolve.report');
});

//lecturer routes
Route::middleware(['firebase.session', 'role:lecturer'])->group(function () {
    Route::get('/lecturer_dashboard', [LecturerController::class, 'index'])->name('lecturer.dashboard');
    Route::get('/lecturer/notifications', [LecturerController::class, 'notificationIndex'])->name('lecturer.notifications');
});

//parent routes
Route::middleware(['firebase.session', 'role:parent'])->group(function () {
    Route::get('/parent_dashboard', [ParentController::class, 'index'])->name('parent.dashboard');
    Route::get('/parent/manage-reports', [ParentController::class, 'manageReports'])->name('parent.manage.reports');
});