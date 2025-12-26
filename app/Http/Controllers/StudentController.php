<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Kreait\Firebase\Database;
use Illuminate\Support\Str;
use Kreait\Firebase\Contract\Database;
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

    /**
     * Show course overview page (3 dummy subjects).
     */
    public function courseOverview(Database $db)
    {
         if (!Session::has('uid') || Session::get('role') !== 'student') {
            return redirect('/login')->with('error', 'Access denied.');
        }

        $courses = $this->db->getReference('courses')->getValue() ?? [];
        $users   = $this->db->getReference('users')->getValue() ?? [];

        foreach ($courses as $code => &$course) {
            $lecturerId = $course['lecturer_id'] ?? null;
            $course['code'] = $code;
            $course['lecturer_email'] = $users[$lecturerId]['email'] ?? 'N/A';
        }

        return view('courses.student_courseOverview', compact('courses'));
    }

    /**
     * Student submits a report for a malicious subject/course.
     */
    public function reportCourse(Request $request, Database $db)
    {
        // Validate form input
        $data = $request->validate([
            'course_id'  => 'required|string',
            'reason'       => 'required|string|max:50',
            'description'  => 'required|string|max:500',
            'target_id'    => 'nullable|string|max:100',
        ]);

        // verify course_code exists in Firebase
        $course = $db->getReference('courses/'.$data['course_id'])->getValue();
        if (!$course) {
            return back()->with('error', 'Invalid course code.');
        }

        $firebaseUid = session('uid');
        $role = session('role');

        // Store report in Firebase: /reports/{pushId}
        $now = now()->format('Y-m-d H:i:s');

        $report = [
            'reporter_id'   => $firebaseUid,
            'reporter_role' => $role,
            'course_id'   => $data['course_id'],
            'target_type'   => $data['target_type'] ?? 'course',
            'target_id'     => $data['target_id'] ?? $data['course_id'],
            'reason'        => $data['reason'],
            'description'   => $data['description'],
            'status'        => 'pending',
            'created_at'    => $now,
            'updated_at'    => $now,
        ];

        $ref = $db->getReference('reports')->push($report);


        return redirect()
            ->route('student.course.overview')
            ->with('success', 'Report submitted successfully.');
    }

    private function getReportAction(string $reportId): ?array
    {
        $actions = $this->db
            ->getReference('report_action')
            ->orderByChild('report_id')
            ->equalTo($reportId)
            ->getValue();

        if (!$actions) {
            return null;
        }

        // Return the FIRST (latest) action
        return collect($actions)->first();
    }

    public function reportStatus()
    {
        $uid = session('uid');

        if (!$uid) {
            return redirect()->route('login')->withErrors('Session expired');
        }

        // 2. Fetch reports submitted by this student
        $reportsRaw = $this->db
            ->getReference('reports')
            ->orderByChild('reporter_id')
            ->equalTo($uid)
            ->getValue();

        if (!$reportsRaw) {
            return view('student.report_status', ['reports' => []]);
        }

        // 3. Fetch courses (for course name mapping)
        $courses = $this->db
            ->getReference('courses')
            ->getValue() ?? [];

        // 4. Normalize report data for UI
        $reports = [];

        foreach ($reportsRaw as $reportId => $report) {

            if (!is_array($report)) continue;

            $courseId = $report['course_id'] ?? null;

            $reports[] = [
                'id'           => $reportId,
                'course_id'    => $courseId,
                'course_name'  => $courses[$courseId]['name'] ?? $courseId,

                'reason'       => $report['reason'] ?? '-',
                'description'  => $report['description'] ?? '-',
                'status'       => $report['status'] ?? 'pending',

                'created_at'   => $report['created_at'] ?? '-',
                'updated_at'   => $report['updated_at'] ?? '-',

                // ✅ THIS IS THE IMPORTANT PART
                'action'       => $this->getReportAction($reportId),
            ];
        }

        return view('courses.student_reportStatus', [
            'reports' => $reports
        ]);
    }

}
