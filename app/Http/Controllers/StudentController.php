<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Kreait\Firebase\Database;
use Illuminate\Support\Str;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Facades\Session;

class StudentController extends Controller
{

    protected Database $database;

    public function index()
    {
        if (!Session::has('uid') || Session::get('role') !== 'student') {
            return redirect('/login')->with('error', 'Access denied.');
        }

        // Get all courses
        $courses = $this->database->getReference('courses')->getValue() ?? [];

        // Get users (for lecturer lookup)
        $users = $this->database->getReference('users')->getValue() ?? [];

        // Attach lecturer info to courses
        foreach ($courses as $code => &$course) {
            $lecturerId = $course['lecturer_id'] ?? null;
            $course['lecturer_email'] = $users[$lecturerId]['email'] ?? 'N/A';
        }

        return view('login.student_dashboard', compact('courses'));
    }

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * Show course overview page (3 dummy subjects).
     */
    public function courseOverview(Database $db)
    {
         if (!Session::has('uid') || Session::get('role') !== 'student') {
            return redirect('/login')->with('error', 'Access denied.');
        }

        $courses = $this->database->getReference('courses')->getValue() ?? [];
        $users   = $this->database->getReference('users')->getValue() ?? [];

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
        $actions = $this->database
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
        $reportsRaw = $this->database
            ->getReference('reports')
            ->orderByChild('reporter_id')
            ->equalTo($uid)
            ->getValue();

        if (!$reportsRaw) {
            return view('student.report_status', ['reports' => []]);
        }

        // 3. Fetch courses (for course name mapping)
        $courses = $this->database
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
