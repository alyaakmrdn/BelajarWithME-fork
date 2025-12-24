<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\FirebaseService;

class CourseController extends Controller
{
    protected $db;

    public function __construct(FirebaseService $firebase)
    {
        $this->db = $firebase->db();
    }

public function getDummyCourses()
{
    return [
        1 => [
            'id' => 1,
            'title' => 'Mathematics',
            'subtitle' => 'Advanced Algebra & Calculus',
            'class' => 'High School',
            'lecturer' => 'Mark Lee',
            'duration' => '12 weeks',
            'price' => 50,
            'materials' => [
                ['type' => 'Note', 'title' => 'Algebra Notes', 'url' => '#'],
                ['type' => 'Video', 'title' => 'Calculus Video', 'url' => '#'],
                ['type' => 'Quiz', 'title' => 'Algebra Quiz', 'url' => '#'],
            ]
        ],
        2 => [
            'id' => 2,
            'title' => 'Science',
            'subtitle' => 'Physics & Chemistry Essentials',
            'class' => 'High School',
            'lecturer' => 'Jane Smith',
            'duration' => '10 weeks',
            'price' => 60,
            'materials' => [
                ['type' => 'Note', 'title' => 'Physics Notes', 'url' => '#'],
                ['type' => 'Video', 'title' => 'Chemistry Video', 'url' => '#'],
                ['type' => 'Quiz', 'title' => 'Physics Quiz', 'url' => '#'],
            ]
        ],
        3 => [
            'id' => 3,
            'title' => 'English',
            'subtitle' => 'Grammar & Literature',
            'class' => 'High School',
            'lecturer' => 'John Doe',
            'duration' => '8 weeks',
            'price' => 40,
            'materials' => [
                ['type' => 'Note', 'title' => 'Grammar Notes', 'url' => '#'],
                ['type' => 'Video', 'title' => 'Literature Video', 'url' => '#'],
                ['type' => 'Quiz', 'title' => 'Grammar Quiz', 'url' => '#'],
            ]
        ],
    ];
}


    // 🔹 List courses for a child
    public function index(Request $request)
    {
        $child_id = $request->child_id ?? session('uid');

        $coursesData = $this->getDummyCourses();
        $courses = [];

        // Attach ID properly
        foreach ($coursesData as $id => $course) {
            $course['id'] = $id;
            $courses[] = $course;
        }

        // ✅ Fetch paid courses
        $paidCourses = [];
        $enrollments = $this->db->getReference('enrollments')->getValue();

        if ($enrollments) {
            foreach ($enrollments as $enrollment) {
                if (
                    isset($enrollment['child_id'], $enrollment['course_id'], $enrollment['status']) &&
                    $enrollment['child_id'] == $child_id &&
                    $enrollment['status'] === 'paid'
                ) {
                    $paidCourses[] = $enrollment['course_id'];
                }
            }
        }

        return view('courses.index', compact('courses', 'child_id', 'paidCourses'));
    }

    // 🔹 Enroll (simulate payment)
    public function enroll(Request $request, $course_id)
    {
        if (!Session::has('uid') || Session::get('role') !== 'parent') {
            return redirect('/login')->with('error','Access denied.');
        }

        $child_id = $request->child_id;

        // Check if already enrolled and paid
        $enrollments = $this->db->getReference('enrollments')->getValue();
        if ($enrollments) {
            foreach ($enrollments as $enrollment) {
                if (
                    isset($enrollment['child_id'], $enrollment['course_id'], $enrollment['status']) &&
                    $enrollment['child_id'] == $child_id &&
                    $enrollment['course_id'] == $course_id &&
                    $enrollment['status'] === 'paid'
                ) {
                    return redirect()->back()->with('error', 'Course already enrolled and paid.');
                }
            }
        }

        // Save enrollment in Firebase
        $this->db->getReference('enrollments')->push([
            'child_id' => $child_id,
            'course_id' => $course_id,
            'enrolled_at' => now()->toDateTimeString(),
            'status' => 'unpaid' // initially unpaid, parent must pay
        ]);

        return redirect()->back()->with('success','Child enrolled successfully!');
    }

    // 🔹 View course materials
    public function materials($course_id)
{
    if (!session()->has('uid') || session('role') !== 'student') {
        return redirect('/login');
    }

    $child_id = session('uid');

    // Check paid enrollment
    $enrollments = $this->db->getReference('enrollments')->getValue();
    $hasAccess = false;

    if ($enrollments) {
        foreach ($enrollments as $enrollment) {
            if (
                isset($enrollment['child_id'], $enrollment['course_id'], $enrollment['status']) &&
                $enrollment['child_id'] === $child_id &&
                (int)$enrollment['course_id'] === (int)$course_id &&
                $enrollment['status'] === 'paid'
            ) {
                $hasAccess = true;
                break;
            }
        }
    }

    if (!$hasAccess) {
        return redirect()->route('student.courses')->with('error', 'You do not have access to this course.');
    }

    // Get dummy course info
    $dummyCourses = $this->getDummyCourses();
    if (!isset($dummyCourses[$course_id])) {
        abort(404, 'Course not found.');
    }

    $course = $dummyCourses[$course_id];
    $course['id'] = $course_id;

    // ✅ Use dummy materials
    $course['materials'] = $course['materials'] ?? [];

    return view('courses.materials', compact('course'));
}

}
