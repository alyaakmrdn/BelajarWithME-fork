<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\FirebaseService;
use Kreait\Firebase\Database;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminController extends Controller
{
    protected Database $database;

    public function __construct()
    {
        $this->database = app('firebase.database');
    }

     public function index()
    {
        if (!Session::has('uid') || Session::get('role') !== 'admin') {
            return redirect('/login')->with('error', 'Access denied.');
        }

        return view('login.admin_dashboard');
    }

    private function getReportAction(string $reportId): ?array
    {
        $actions = $this->database
            ->getReference('report_action')
            ->orderByChild('report_id')
            ->equalTo($reportId)
            ->getValue();

        if (!$actions) return null;

        return collect($actions)->first();
    }

    public function manageReports()
    {
        $adminUid = session('uid');

        if (!$adminUid || session('role') !== 'admin') {
            return redirect()->route('login');
        }

        /** 1️⃣ Get courses handled by this admin */
        $coursesRaw = $this->database
            ->getReference('courses')
            ->orderByChild('centre_id')
            ->equalTo($adminUid)
            ->getValue() ?? [];

        if (empty($coursesRaw)) {
            return view('courses.admin_manageReport', ['reports' => []]);
        }

        $allowedCourseIds = array_keys($coursesRaw);

        /** 2️⃣ Get all users (for lecturer name lookup) */
        $users = $this->database
            ->getReference('users')
            ->getValue() ?? [];

        /** 3️⃣ Get all reports */
        $reportsRaw = $this->database
            ->getReference('reports')
            ->getValue() ?? [];

        $reports = [];

        foreach ($reportsRaw as $reportId => $report) {
            if (!is_array($report)) continue;

            $courseId = $report['course_id'] ?? null;

            // 🔐 Admin access control
            if (!in_array($courseId, $allowedCourseIds)) {
                continue;
            }

            $lecturerId = $coursesRaw[$courseId]['lecturer_id'] ?? null;
            $lecturerName = $users[$lecturerId]['name'] ?? 'Unknown Lecturer';

            $reports[] = [
                'id'            => $reportId,
                'course_id'     => $courseId,
                'course_name'   => $coursesRaw[$courseId]['name'] ?? $courseId,
                'reporter_id'    => $report['reporter_id'] ?? null,
                'reporter_role'  => $report['reporter_role'] ?? 'unknown',
                'lecturer_id'   => $lecturerId,
                'lecturer_name' => $lecturerName,
                'reason'        => $report['reason'] ?? '-',
                'description'   => $report['description'] ?? '-',
                'status'        => $report['status'] ?? 'pending',
                'created_at'    => $report['created_at'] ?? '-',
                'updated_at'    => $report['updated_at'] ?? '-',
                'action'        => $this->getReportAction($reportId),
            ];
        }

        return view('courses.admin_manageReport', compact('reports'));
    }

    public function resolveReport(Request $request)
    {
        $adminUid = session('uid');

        if (!$adminUid || session('role') !== 'admin') {
            return redirect()->route('login');
        }

        $reportId   = $request->report_id;
        $courseId   = $request->course_id;
        $lecturerId = $request->lecturer_id;

        $resolutionType = $request->resolution_type;
        $actionNote     = $request->action_note;

        $messageTitle   = $request->message_title;
        $messageBody    = $request->message_body;

        $now = now()->format('Y-m-d H:i:s');

        /** 1️⃣ Update report status */
        $this->database
            ->getReference("reports/{$reportId}/status")
            ->set('resolved');

        /** 2️⃣ Deactivate course IF chosen */
        if ($resolutionType === 'deactivate') {
            $this->database
                ->getReference("courses/{$courseId}/status")
                ->set('inactive');
        }

        /** 3️⃣ Record admin action */
        $this->database
            ->getReference('report_action')
            ->push([
                'report_id'   => $reportId,
                'admin_id'    => $adminUid,
                'action'      => $resolutionType === 'deactivate'
                                    ? 'Course deactivated'
                                    : 'Resolved – no issue found',
                'note'        => $actionNote,
                'created_at'  => $now
            ]);

        /** 4️⃣ Send notification to lecturer (ONLY if message filled) */
        if (!empty($messageTitle) && !empty($messageBody) && $lecturerId) {
            $this->database
                ->getReference('notification')
                ->push([
                    'user_id'    => $lecturerId,
                    'title'      => $messageTitle,
                    'message'    => $messageBody,
                    'created_at' => $now,
                    'read'       => false
                ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Report resolved successfully.');
    }

    public function transactions(Request $request)
{
    if (!Session::has('uid') || Session::get('role') !== 'admin') {
        return redirect('/login');
    }

    $allTransactions = $this->database->getReference('enrollments')->getValue() ?? [];
    $users = $this->database->getReference('users')->getValue() ?? [];
    $dummyCourses = app(\App\Http\Controllers\CourseController::class)->getDummyCourses();

    $transactions = [];

    foreach ($allTransactions as $t) {
        $parentId = $t['parent_id'] ?? null;
        $childId  = $t['child_id'] ?? null;
        $courseId = $t['course_id'] ?? null;

        $transactions[] = [
            'transaction_id' => $t['transaction_id'] ?? '-',
            'created_at'     => $t['created_at'] ?? now()->toDateTimeString(),
            'parent_name'    => $users[$parentId]['name'] ?? '-',
            'child_id'       => $childId,
            'child_name'     => $users[$childId]['name'] ?? 'Unknown Student',
            'course_id'      => $courseId,
            'course_name'    => $dummyCourses[$courseId]['title'] ?? '-',
            'total_paid'     => $t['total_paid'] ?? 0,
            'payment_method' => $t['payment_method'] ?? '-',
            'status'         => $t['status'] ?? '-',
            'invoice'        => $t['invoice'] ?? null,
        ];
    }

    /* ---------- STUDENT FILTER ---------- */
    if ($request->child_id) {
        $transactions = array_filter($transactions, fn($tx) => $tx['child_id'] === $request->child_id);
    }

    /* ---------- COURSE FILTER ---------- */
    if ($request->course_id) {
        $transactions = array_filter($transactions, fn($tx) => $tx['course_id'] === $request->course_id);
    }

    /* ---------- DROPDOWNS ---------- */
    $studentsDropdown = [];
    foreach ($users as $uid => $u) {
        if (($u['role'] ?? '') === 'student' && isset($u['name'])) {
            $studentsDropdown[$uid] = $u['name'];
        }
    }

    $coursesDropdown = [];
    foreach ($dummyCourses as $id => $c) {
        $coursesDropdown[$id] = $c['title'] ?? '-';
    }

    /* ---------- Pagination ---------- */
    $page = $request->get('page', 1);
    $perPage = 10;

    $transactions = new LengthAwarePaginator(
        array_slice($transactions, ($page - 1) * $perPage, $perPage),
        count($transactions),
        $perPage,
        $page,
        ['path' => url()->current(), 'query' => $request->query()]
    );

    return view('admin.transactions', compact('transactions', 'studentsDropdown', 'coursesDropdown'));
}


public function enrollmentPage(Request $request)
{
    if (!Session::has('uid') || Session::get('role') !== 'admin') {
        return redirect('/login');
    }

    // Get all enrollments
    $enrollments = $this->database->getReference('enrollments')->getValue() ?? [];

    // Get users & courses
    $users = $this->database->getReference('users')->getValue() ?? [];
    $dummyCourses = app(\App\Http\Controllers\CourseController::class)->getDummyCourses();

    $records = [];

    foreach ($enrollments as $e) {

        $studentId = $e['child_id'] ?? null;
        $parentId  = $e['parent_id'] ?? null;
        $courseId  = $e['course_id'] ?? null;

        // Skip if student does not exist
        if (!isset($users[$studentId]) || ($users[$studentId]['role'] ?? '') !== 'student') {
            continue;
        }

        $records[] = [
            'student_id'   => $studentId,
            'course_id'    => $courseId,
            'created_at'   => $e['created_at'] ?? now()->toDateTimeString(),
            'student_name' => $users[$studentId]['name'] ?? '-',
            'parent_name'  => isset($users[$parentId]['name']) ? $users[$parentId]['name'] : '-',
            'course_name'  => isset($dummyCourses[$courseId]['title']) ? $dummyCourses[$courseId]['title'] : '-',
            'status'       => $e['status'] ?? 'paid',
            'total_paid'   => $e['total_paid'] ?? 0,
        ];
    }

    /* ---------- STUDENT FILTER ---------- */
    if ($request->child_id) {
        $records = array_filter($records, fn($r) => $r['student_id'] === $request->child_id);
    }

    /* ---------- COURSE FILTER ---------- */
    if ($request->course_id) {
        $records = array_filter($records, fn($r) => $r['course_id'] === $request->course_id);
    }

    /* ---------- DROPDOWNS ---------- */
    $studentsDropdown = [];
    foreach ($users as $uid => $u) {
        if (($u['role'] ?? '') === 'student' && isset($u['name'])) {
            $studentsDropdown[$uid] = $u['name'];
        }
    }

    $coursesDropdown = [];
    foreach ($dummyCourses as $id => $course) {
        $coursesDropdown[$id] = $course['title'] ?? '-';
    }

    return view('admin.enrollment', compact('records', 'studentsDropdown', 'coursesDropdown'));
}



}
