<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\FirebaseService;

class StudentController extends Controller
{
    protected $db;

    public function __construct(FirebaseService $firebase)
    {
        $this->db = $firebase->db();
    }

    
   public function index()
{
    if (!session()->has('uid') || session('role') !== 'student') {
        return redirect('/login');
    }

    $child_id = session('uid');

    // 🔹 SAME dummy courses as CourseController
    $dummyCourses = [
        1 => ['id'=>1,'title'=>'Mathematics','class'=>'Form 4','price'=>50],
        2 => ['id'=>2,'title'=>'Science','class'=>'Form 5','price'=>60],
        3 => ['id'=>3,'title'=>'English','class'=>'Form 3','price'=>40],
    ];

    $enrollments = $this->db->getReference('enrollments')->getValue();
    $courses = [];

    if ($enrollments) {
        foreach ($enrollments as $enrollment) {

            if (
                isset($enrollment['child_id'], $enrollment['course_id'], $enrollment['status']) &&
                $enrollment['child_id'] == $child_id &&
                $enrollment['status'] === 'paid'
            ) {
                // ✅ Fetch from dummy list
                if (isset($dummyCourses[$enrollment['course_id']])) {
                    $courses[] = $dummyCourses[$enrollment['course_id']];
                }
            }
        }
    }

    $addedCourses = [];

if ($enrollments) {
    foreach ($enrollments as $enrollment) {

        if (
            isset($enrollment['child_id'], $enrollment['status'], $enrollment['course_id']) &&
            $enrollment['child_id'] == $child_id &&
            $enrollment['status'] === 'paid'
        ) {

            if (in_array($enrollment['course_id'], $addedCourses)) {
                continue; // skip duplicate
            }

            $course = $this->db
                ->getReference('courses/' . $enrollment['course_id'])
                ->getValue();

            if ($course) {
                $course['id'] = $enrollment['course_id'];
                $courses[] = $course;
                $addedCourses[] = $enrollment['course_id'];
            }
        }
    }
}


    return view('login.student_dashboard', compact('courses'));
}

// 📌 Student Courses Page
public function courses()
{
    $student_uid = session('uid');
    $allEnrollments = $this->db->getReference('enrollments')->getValue() ?? [];
    $enrolledCourses = [];

    foreach ($allEnrollments as $enrollment) {
        if (($enrollment['child_id'] ?? '') === $student_uid && ($enrollment['status'] ?? '') === 'paid') {
            $dummyCourses = app(\App\Http\Controllers\CourseController::class)->getDummyCourses();
            $course_id = $enrollment['course_id'];
            if (isset($dummyCourses[$course_id])) {
                $enrolledCourses[$course_id] = $dummyCourses[$course_id];
            }
        }
    }

    return view('student.courses', compact('enrolledCourses'));
}



}
