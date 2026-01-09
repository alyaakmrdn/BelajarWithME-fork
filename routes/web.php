<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ChildController;

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/
Route::get('/', [AuthController::class, 'showLogin']);
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::get('/signup', [AuthController::class, 'showSignup']);
Route::post('/signup', [AuthController::class, 'signup']);
Route::get('/logout', [AuthController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| Dashboards
|--------------------------------------------------------------------------
*/
//temp deleted
// Route::get('/admin_dashboard', [AdminController::class, 'index']);
// Route::get('/student_dashboard', [StudentController::class, 'index']);
// Route::get('/lecturer_dashboard', [LecturerController::class, 'index']);
// Route::get('/parent_dashboard', [ParentController::class, 'index'])->name('parent.dashboard');

/*
|--------------------------------------------------------------------------
| Course Routes
|--------------------------------------------------------------------------
*/
// Show courses for a child (parent/teacher view)
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
// Enroll a child in a course
Route::post('/courses/{course_id}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');

// Student course routes
Route::get('/student/courses', [StudentController::class, 'courses'])->name('student.courses');
Route::get('/student/courses/{course_id}/materials', [CourseController::class, 'materials'])->name('student.materials');
//dashboard student my course menu
Route::get('/student/courses/{course_id}/materials', [CourseController::class, 'materials'])->name('courses.materials');
/*
|--------------------------------------------------------------------------
| Parent Specific Pages
|--------------------------------------------------------------------------
*/
// Show all children (My Children menu)
Route::get('/parent/children', [ParentController::class, 'children'])->name('parent.children');
// Show all children (Courses menu)
Route::get('/parent/courses', [ParentController::class, 'coursesPage'])->name('parent.courses');

/*
|--------------------------------------------------------------------------
| Child Controller (Add Child)
|--------------------------------------------------------------------------
*/
Route::get('/children/add', [ChildController::class, 'create'])->name('children.add');
Route::post('/children/store', [ChildController::class, 'store'])->name('children.store');
// Add Child 
Route::get('/parent/children/add', [ChildController::class, 'create'])->name('children.create');
/*
|--------------------------------------------------------------------------
| Enrollment & Payment (Demo Flow)
|--------------------------------------------------------------------------
*/
Route::get('/enrollment/register', [PaymentController::class, 'register'])->name('enrollment.register');
Route::post('/enrollment/pay', [PaymentController::class, 'process'])->name('payment.process');
Route::post('/enroll/summary', [EnrollmentController::class, 'summary'])->name('enroll.summary');
Route::post('/enroll/payment', [EnrollmentController::class, 'payment'])->name('enroll.payment');
Route::post('/enroll/confirm', [EnrollmentController::class, 'confirm'])->name('enroll.confirm');

/*
|--------------------------------------------------------------------------
| Transactions
|--------------------------------------------------------------------------
*/
Route::get('/parent/transactions', [ParentController::class, 'transactions'])->name('transactions');

// Admin - View all transactions
Route::get('/admin/transactions', [AdminController::class, 'transactions'])
    ->name('admin.transactions');

Route::get('/admin/enrollments', [AdminController::class, 'enrollmentPage'])
    ->name('admin.enrollments');

/*
|--------------------------------------------------------------------------
| Not Sure / Duplicate / Commented Out
|--------------------------------------------------------------------------
*/
//Route::get('/student/courses/{courseId}/materials', [StudentController::class, 'materials'])->name('student.materials');
//Route::get('/children/add', function () {return view('parent.add_child');})->name('children.add');
//Route::post('/children/store', [ParentController::class, 'storeChild'])->name('children.store');


//student routes
Route::middleware(['firebase.session', 'role:student'])->group(function () {
    Route::get('/student_dashboard', [StudentController::class, 'index'])
        ->name('student.dashboard');
    // Course overview page
    Route::get('/student/coursesOverview', [StudentController::class, 'courseOverview'])
        ->name('student.course.overview');
    // Submit report (POST)
    Route::post('/student/report', [StudentController::class, 'reportCourse'])
        ->name('student.report.submit');
    Route::get('/student/reports', [StudentController::class, 'reportStatus'])
     ->name('student.report.status');
});

//admin routes
Route::middleware(['firebase.session', 'role:admin'])->group(function () {
    Route::get('/admin_dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
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

/* ---------- Admin Profile ---------- */

// Edit profile (view)
Route::get('/admin/profile_admin_edit', [AdminController::class, 'editProfile'])
    ->name('admin.profile.edit');

// Update profile (submit)
Route::post('/admin/profile_admin_update', [AdminController::class, 'updateProfile'])
    ->name('admin.profile.update');

// View profile (VIEW)
Route::get('/admin/profile_admin_view', [AdminController::class, 'profile'])
    ->name('admin.profile.view');

/* ---------- Parent Profile ---------- */

// View profile
Route::get('/parent/profile_parent_view', [ParentController::class, 'profile'])
    ->name('parent.profile.view');

// Edit profile
Route::get('/parent/profile/edit', [ParentController::class, 'editProfile'])
    ->name('parent.profile.edit');

// Update profile
Route::post('/parent/profile/update', [ParentController::class, 'updateProfile'])
    ->name('parent.profile.update');

/* ---------- Student Profile ---------- */

// View profile
Route::get('/student/profile_student_view', [StudentController::class, 'profile'])
    ->name('student.profile.view');

// Edit profile
Route::get('/student/profile_student_edit', [StudentController::class, 'editProfile'])
    ->name('student.profile.edit');

// Update profile
Route::post('/student/profile/update', [StudentController::class, 'updateProfile'])
    ->name('student.profile.update');

/* ---------- Lecturer Profile ---------- */

// View profile
Route::get('/lecturer/profile_lecturer_view', [LecturerController::class, 'profile'])
    ->name('lecturer.profile.view');

// Edit profile
Route::get('/lecturer/profile_lecturer_edit', [LecturerController::class, 'editProfile'])
    ->name('lecturer.profile.edit');

// Update profile
Route::post('/lecturer/profile/update', [LecturerController::class, 'updateProfile'])
    ->name('lecturer.profile.update');