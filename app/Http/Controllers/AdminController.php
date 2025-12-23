<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\FirebaseService;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminController extends Controller
{
    protected $db;

public function __construct(FirebaseService $firebase)
{
    $this->db = $firebase->db();
}
 
    
    public function index()
    {
        if (!Session::has('uid') || Session::get('role') !== 'admin') {
            return redirect('/login')->with('error', 'Access denied.');
        }

        return view('login.admin_dashboard');
    }

    public function transactions(Request $request)
{
    if (!Session::has('uid') || Session::get('role') !== 'admin') {
        return redirect('/login');
    }

    $allTransactions = $this->db->getReference('enrollments')->getValue() ?? [];
    $users = $this->db->getReference('users')->getValue() ?? [];
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
    $enrollments = $this->db->getReference('enrollments')->getValue() ?? [];

    // Get users & courses
    $users = $this->db->getReference('users')->getValue() ?? [];
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
