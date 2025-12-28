<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Kreait\Firebase\Database;
use App\Services\FirebaseService;
use Illuminate\Pagination\LengthAwarePaginator;

class ParentController extends Controller
{
    protected Database $database;

    public function __construct()
    {
        $this->database = app('firebase.database');
    }

    // Parent Dashboard
    public function index()
{
    // Manual session check
    if (!Session::has('uid') || Session::get('role') !== 'parent') {
        return redirect('/login')->with('error', 'Access denied.');
    }

    $parent_uid = Session::get('uid');

    // Fetch children from Firebase
    $users = $this->database->getReference('users')->getValue();

$childrenCount = 0;

if ($users) {
    foreach ($users as $uid => $user) {
        if (
            isset($user['role'], $user['parent_uid']) &&
            $user['role'] === 'student' &&
            $user['parent_uid'] === $parent_uid
        ) {
            $childrenCount++;
        }
    }
}

    return view('login.parent_dashboard', compact('childrenCount'));
}

public function transactions(Request $request)
{
    if (!Session::has('uid')) {
        return redirect('/login');
    }

    $parent_uid = Session::get('uid');

    $allTransactions = $this->database->getReference('enrollments')->getValue() ?? [];
    $users = $this->database->getReference('users')->getValue() ?? [];
    $dummyCourses = app(\App\Http\Controllers\CourseController::class)->getDummyCourses();

    $transactions = [];

    foreach ($allTransactions as $t) {
        if (($t['parent_id'] ?? '') !== $parent_uid) continue;

        $transactions[] = [
            'created_at'     => $t['created_at'] ?? now()->toDateTimeString(),
            'transaction_id' => $t['transaction_id'] ?? 'TX' . time(),
            'child_id'       => $t['child_id'],
            'child_name'     => $users[$t['child_id']]['name'] ?? '-',
            'course_id'      => $t['course_id'],
            'course_name'    => $dummyCourses[$t['course_id']]['title'] ?? '-',
            'total_paid'     => $t['total_paid'] ?? 0,
            'payment_method' => $t['payment_method'] ?? '-',
            'status'         => $t['status'] ?? 'paid',
            'invoice'        => $t['invoice'] ?? null,
        ];
    }

    /* ---------- DROPDOWN FILTERS ---------- */
    if ($request->child_id) {
        $transactions = array_filter($transactions, fn($t) =>
            $t['child_id'] === $request->child_id
        );
    }

    if ($request->course_id) {
        $transactions = array_filter($transactions, fn($t) =>
            $t['course_id'] === $request->course_id
        );
    }

    if ($request->status) {
        $transactions = array_filter($transactions, fn($t) =>
            $t['status'] === $request->status
        );
    }

    /* ---------- PAGINATION ---------- */
    $page = $request->get('page', 1);
    $perPage = 10;
    $transactions = array_values($transactions);

    $transactions = new LengthAwarePaginator(
        array_slice($transactions, ($page - 1) * $perPage, $perPage),
        count($transactions),
        $perPage,
        $page,
        ['path' => url()->current()]
    );

    /* ---------- DATA FOR DROPDOWNS ---------- */
    $children = [];
    foreach ($users as $uid => $u) {
        if (($u['parent_uid'] ?? '') === $parent_uid && ($u['role'] ?? '') === 'student') {
            $children[$uid] = $u['name'];
        }
    }

    return view('parent.transactions', compact(
        'transactions',
        'children',
        'dummyCourses'
    ));
}

public function children()
{
    $parent_uid = session('uid');

    

    // Fetch children from Firebase
    $snapshot = $this->database
        ->getReference('users')
        ->orderByChild('parent_uid')  // Make sure parent_uid is consistent
        ->equalTo($parent_uid)
        ->getValue();

    $children = [];

    if ($snapshot) {
        foreach ($snapshot as $uid => $child) {
            if (($child['role'] ?? '') === 'student') {
                $children[] = (object)[
                    'uid' => $uid,
                    'name' => $child['name'],
                    'academic_level' => $child['academic_level'],
                    'email' => $child['email'] ?? '',
                ];
            }
        }
    }

    

    return view('parent.children', compact('children'));

    
}

public function coursesPage(Request $request)
{
    $parent_uid = session('uid');

    // Fetch children from Firebase
    $snapshot = $this->database
        ->getReference('users')
        ->orderByChild('parent_uid')  // Make sure parent_uid is consistent
        ->equalTo($parent_uid)
        ->getValue();

    //$children = [];

    if ($snapshot) {
        foreach ($snapshot as $uid => $child) {
            if (($child['role'] ?? '') === 'student') {
                $children[] = (object)[
                    'uid' => $uid,
                    'name' => $child['name'],
                    'academic_level' => $child['academic_level'],
                    'email' => $child['email'] ?? '',
                ];
            }
        }
    }

$dummyCourses = app(\App\Http\Controllers\CourseController::class)->getDummyCourses();
    $allTransactions = $this->database->getReference('enrollments')->getValue() ?? [];
$users = $this->database->getReference('users')->getValue() ?? [];

$childTransactions = [];

foreach ($allTransactions as $t) {
    if (($t['parent_id'] ?? '') !== $parent_uid) continue;

    $childTransactions[] = [
        'created_at' => $t['created_at'],
        'child_id'       => $t['child_id'],
        'child_name' => $users[$t['child_id']]['name'] ?? '-',
        'course_name' => $dummyCourses[$t['course_id']]['title'] ?? '-',
        'total_paid' => $t['total_paid'] ?? 0,
        'status'         => $t['status'] ?? 'paid',
    ];
}

/* ---------- DROPDOWN FILTERS ---------- */
    if ($request->child_id) {
        $childTransactions = array_filter($childTransactions, fn($t) =>
            $t['child_id'] === $request->child_id
        );
    }

    /* ---------- DATA FOR DROPDOWNS ---------- */
    $childrenDropdown = [];
    foreach ($users as $uid => $u) {
        if (($u['parent_uid'] ?? '') === $parent_uid && ($u['role'] ?? '') === 'student') {
            $childrenDropdown[$uid] = $u['name'];
        }
    }

    return view('parent.courses', compact('children', 'childTransactions', 'dummyCourses', 'childrenDropdown'));
}




    public function manageReports()
    {
        $parentUid = session('uid');

        if (!$parentUid || session('role') !== 'parent') {
            return redirect()->route('login');
        }

        /** 1️⃣ Get all users */
        $users = $this->database
            ->getReference('users')
            ->getValue() ?? [];

        /** 2️⃣ Find children linked to this parent */
        $children = [];

        foreach ($users as $userId => $user) {
            if (
                ($user['role'] ?? null) === 'student' &&
                (
                    ($user['parent_id'] ?? null) === $parentUid ||
                    ($user['parent_uid'] ?? null) === $parentUid
                )
            ) {
                $children[$userId] = $user['name'] ?? 'Unknown';
            }
        }

        if (empty($children)) {
            return view('parent.parent_manageReport', [
                'reports' => []
            ]);
        }

        /** 3️⃣ Get reports */
        $reportsRaw = $this->database
            ->getReference('reports')
            ->getValue() ?? [];

        /** 4️⃣ Get courses */
        $courses = $this->database
            ->getReference('courses')
            ->getValue() ?? [];

        /** 5️⃣ Get report actions */
        $actionsRaw = $this->database
            ->getReference('report_action')
            ->getValue() ?? [];

        $reports = [];

        foreach ($reportsRaw as $reportId => $report) {
            $reporterId = $report['reporter_id'] ?? null;

            if (!isset($children[$reporterId])) {
                continue;
            }

            // Find action for this report
            $actionData = null;
            foreach ($actionsRaw as $action) {
                if (($action['report_id'] ?? null) === $reportId) {
                    $actionData = $action;
                    break;
                }
            }

            $courseId = $report['course_id'] ?? null;

            $reports[] = [
                'id'          => $reportId,
                'child_name'  => $children[$reporterId],
                'course_name' => $courses[$courseId]['name'] ?? $courseId,
                'reason'      => $report['reason'] ?? '-',
                'description' => $report['description'] ?? '-',
                'status'      => $report['status'] ?? 'pending',
                'created_at'  => $report['created_at'] ?? '-',
                'action'      => $actionData,
            ];
        }

        return view('courses.parent_manageReport', compact('reports'));
    }


    /** 🔹 Fetch admin action (same as student side) */
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
}
