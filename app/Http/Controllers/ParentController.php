<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\FirebaseService;
use Illuminate\Pagination\LengthAwarePaginator;

class ParentController extends Controller
{
    protected $db;

    public function __construct(FirebaseService $firebase)
    {
        $this->db = $firebase->db();
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
    $snapshot = $this->db
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

    return view('login.parent_dashboard', compact('children'));
}

public function transactions(Request $request)
{
    if (!Session::has('uid')) {
        return redirect('/login');
    }

    $parent_uid = Session::get('uid');

    $allTransactions = $this->db->getReference('enrollments')->getValue() ?? [];
    $users = $this->db->getReference('users')->getValue() ?? [];
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
    $snapshot = $this->db
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

public function coursesPage()
{
    $parent_uid = session('uid');

    // Fetch children from Firebase
    $snapshot = $this->db
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

    return view('parent.courses', compact('children'));
}



}
