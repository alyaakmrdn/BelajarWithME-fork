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

    public function __construct(FirebaseService $firebase)
    {
        $this->database = $firebase->db();
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
        $childrenSnapshot = $this->database
    ->getReference('users')
    ->orderByChild('parent_uid')
    ->equalTo($parentUid)
    ->getValue() ?? [];

$childUids = [];

foreach ($childrenSnapshot as $uid => $child) {
    if (($child['role'] ?? '') === 'student') {
        $childUids[] = $uid;
    }
}

if (empty($childUids)) {
    return view('courses.parent_manageReport', ['reports' => []]);
}


        /** 2️⃣ Get all reports */
        $reportsRaw = $this->database
            ->getReference('reports')
            ->getValue() ?? [];

        /** 3️⃣ Get courses (for course names) */
        $dummyCourses = app(\App\Http\Controllers\CourseController::class)->getDummyCourses();


        $reports = [];

        foreach ($reportsRaw as $reportId => $report) {
    if (!is_array($report)) continue;

    if (!in_array($report['reporter_id'] ?? null, $childUids)) {
        continue;
    }

    $courseId = $report['course_id'] ?? null;

    $reports[] = [
        'id'           => $reportId,
        'course_id'    => $courseId, // ✅ ADD THIS
        'child_name'   => $childrenSnapshot[$report['reporter_id']]['name'] ?? 'Child',
        'course_name'  => $dummyCourses[$courseId]['title'] ?? $courseId,
        'reason'       => $report['reason'] ?? '-',
        'description'  => $report['description'] ?? '-',
        'status'       => $report['status'] ?? 'pending',
        'created_at'   => $report['created_at'] ?? '-',
        'updated_at'   => $report['updated_at'] ?? '-',
        'action'       => $this->getReportAction($reportId),
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

public function profile()
{
    if (!Session::has('uid') || Session::get('role') !== 'parent') {
        return redirect('/login')->with('error', 'Access denied.');
    }

    return view('parent.profile_parent_view');
}

public function editProfile()
{
    if (!Session::has('uid') || Session::get('role') !== 'parent') {
        return redirect('/login')->with('error', 'Access denied.');
    }

    return view('parent.profile_parent_edit');
}

public function updateProfile(Request $request)
{
    if (!Session::has('uid') || Session::get('role') !== 'parent') {
        return redirect('/login')->with('error', 'Access denied.');
    }

    $uid = session('uid');

    $request->validate([
        'name'   => 'required|string|max:255',
        'email'  => 'required|email',
        'phone'  => 'required|string|max:20',
        'dob'    => 'required|date',
        'gender' => 'required',
        'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $updateData = [
        'name'   => $request->name,
        'email'  => $request->email,
        'phone'  => $request->phone,
        'dob'    => $request->dob,
        'gender' => $request->gender,
    ];

    /* ---------- Profile Picture Upload ---------- */
    if ($request->hasFile('profile_picture')) {
        $file = $request->file('profile_picture');

        $filename = 'parent_' . $uid . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('profile', $filename, 'public');

        $updateData['profile_picture'] = asset('storage/' . $path);
    }

    /* ---------- Update Firebase ---------- */
    $this->database
        ->getReference("users/{$uid}")
        ->update($updateData);

    return redirect()
        ->route('parent.profile.view')
        ->with('success', 'Profile updated successfully.');
}




}
