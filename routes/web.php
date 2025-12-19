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
Route::get('/admin_dashboard', [AdminController::class, 'index']);
Route::get('/student_dashboard', [StudentController::class, 'index']);
Route::get('/lecturer_dashboard', [LecturerController::class, 'index']);
Route::get('/parent_dashboard', [ParentController::class, 'index'])->name('parent.dashboard');

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

/*
|--------------------------------------------------------------------------
| Parent Specific Pages
|--------------------------------------------------------------------------
*/
// Show all children (My Children menu)
Route::get('/parent/children', [ParentController::class, 'children'])->name('parent.children');
// Show all children (Courses menu)
Route::get('/parent/courses', [ParentController::class, 'coursesPage'])->name('parent.courses');
// Add a child
Route::get('/parent/children/create', [ParentController::class, 'createChild'])->name('children.create');

/*
|--------------------------------------------------------------------------
| Child Controller (Add Child)
|--------------------------------------------------------------------------
*/
Route::get('/children/add', [ChildController::class, 'create'])->name('children.add');
Route::post('/children/store', [ChildController::class, 'store'])->name('children.store');

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

/*
|--------------------------------------------------------------------------
| Not Sure / Duplicate / Commented Out
|--------------------------------------------------------------------------
*/
//Route::get('/student/courses/{courseId}/materials', [StudentController::class, 'materials'])->name('student.materials');
//Route::get('/student/courses/{course_id}/materials', [CourseController::class, 'materials'])->name('courses.materials');
//Route::get('/children/add', function () {return view('parent.add_child');})->name('children.add');
//Route::post('/children/store', [ParentController::class, 'storeChild'])->name('children.store');
