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

        $transactions[] = [
            'created_at'     => $t['created_at'] ?? now()->toDateTimeString(),
            'transaction_id' => $t['transaction_id'] ?? '-',
            'parent_name'    => $users[$parentId]['name'] ?? '-',
            'child_name'     => $users[$childId]['name'] ?? '-',
            'course_name'    => $dummyCourses[$t['course_id']]['title'] ?? '-',
            'total_paid'     => $t['total_paid'] ?? 0,
            'payment_method' => $t['payment_method'] ?? '-',
            'status'         => $t['status'] ?? '-',
            'invoice'        => $t['invoice'] ?? null,
        ];
    }

    /* Pagination */
    $page = $request->get('page', 1);
    $perPage = 10;

    $transactions = new LengthAwarePaginator(
        array_slice($transactions, ($page - 1) * $perPage, $perPage),
        count($transactions),
        $perPage,
        $page,
        ['path' => url()->current()]
    );

    return view('admin.transactions', compact('transactions'));
}

}
