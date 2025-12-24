<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\FirebaseService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\CourseController;


class EnrollmentController extends Controller
{
    protected $db;

    public function __construct(FirebaseService $firebase)
    {
        $this->db = $firebase->db();
    }

    // 🔹 Order summary page
    public function summary(Request $request)
    {
        if (!Session::has('uid') || Session::get('role') !== 'parent') {
            return redirect('/login');
        }

        $course_id = $request->course_id;
        $child_id  = $request->child_id;

        // ✅ Fetch course info (dummy for now)
    $dummyCourses = app(CourseController::class)->getDummyCourses();
    if (!isset($dummyCourses[$course_id])) {
        abort(404, 'Course not found.');
    }
    $course = (object) $dummyCourses[$course_id];
    $course->name = $course->title; // for blade

    // ✅ Fetch child/student info from Firebase
$users = $this->db->getReference('users')->getValue();

if (!isset($users[$child_id])) {
    abort(404, 'Child not found.');
}

$child = (object) $users[$child_id];

        //$tax = 0.06 * $course->price;
        //$total = $course->price + $tax;
        $total = $course->price;

        return view('enrollment.summary', compact('course', 'child', 'child_id', 'total'));
    }

    public function payment(Request $request)
{
    $child_id = $request->child_id;
    $course_id = $request->course_id;

    // Fetch child info from Firebase
    $users = $this->db->getReference('users')->getValue();
    if (!isset($users[$child_id])) {
        abort(404, 'Child not found.');
    }
    $child = (object) $users[$child_id];

    // Fetch course info from dummy courses
    $dummyCourses = app(\App\Http\Controllers\CourseController::class)->getDummyCourses();
    if (!isset($dummyCourses[$course_id])) {
        abort(404, 'Course not found.');
    }
    $course = (object) $dummyCourses[$course_id];

    $total = $request->total; // already calculated in summary

    return view('enrollment.payment', compact('child', 'course', 'total', 'child_id', 'course_id'));
}


    // 🔹 Confirm payment
   public function confirm(Request $request)
{
    $parent_uid = Session::get('uid');

    /* =============================
       1️⃣ FETCH CHILD
    ============================= */
    $users = $this->db->getReference('users')->getValue();
    if (!isset($users[$request->child_id])) {
        abort(404, 'Child not found.');
    }
    $child_name = $users[$request->child_id]['name'];

    /* =============================
       2️⃣ FETCH COURSE
    ============================= */
    $dummyCourses = app(CourseController::class)->getDummyCourses();
    if (!isset($dummyCourses[$request->course_id])) {
        abort(404, 'Course not found.');
    }
    $course = (object) $dummyCourses[$request->course_id];
    $course_name = $course->title;

    /* =============================
       3️⃣ GENERATE IDs
    ============================= */
    $transaction_id = 'TXN-' . time();
    $fileName = 'invoice_' . $transaction_id . '.pdf';

    /* =============================
       4️⃣ GENERATE PDF
    ============================= */
    $pdf = Pdf::loadView('enrollment.invoice', [
        'child_name'     => $child_name,
        'course_name'    => $course_name,
        'course'         => $course,
        'total'          => $request->total,
        'payment_method' => $request->payment_method,
        'bank_name'      => $request->bank_name ?? '-',
        'transaction_id' => $transaction_id,
        'date'           => now()->toDateTimeString()
    ]);

    $invoicePath = storage_path('app/public/invoices');
    if (!file_exists($invoicePath)) {
        mkdir($invoicePath, 0755, true);
    }

    $pdf->save($invoicePath . '/' . $fileName);

    /* =============================
       5️⃣ SAVE TO FIREBASE
    ============================= */
    $this->db->getReference('enrollments')->push([
        'parent_id'      => $parent_uid,
        'child_id'       => $request->child_id,
        'course_id'      => $request->course_id,
        'total_paid'     => $request->total,
        'payment_method' => $request->payment_method,
        'bank_name'      => $request->bank_name ?? null,
        'transaction_id' => $transaction_id,
        'invoice'        => $fileName, // ✅ NOW EXISTS
        'status'         => 'paid',
        'created_at'     => now()->toDateTimeString()
    ]);

    return redirect()->route('parent.dashboard')
        ->with('success', 'Enrollment successful!')
        ->with('invoice', $fileName);
}


}
