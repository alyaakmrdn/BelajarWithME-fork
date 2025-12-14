<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Database;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function process(Request $request, $id)
    {
        // Simulate payment success and store in session
        $paidCourses = Session::get('paid_courses', []);
        if (!in_array($id, $paidCourses)) {
            $paidCourses[] = $id;
            Session::put('paid_courses', $paidCourses);
        }

        // Optionally save payment in Firebase too
        $userId = $request->session()->getId(); // Use session ID as dummy user
        $this->database
            ->getReference("payments/{$userId}/{$id}")
            ->set([
                'status' => 'paid',
                'timestamp' => now()->toDateTimeString()
            ]);

        return redirect("/courses/{$id}/materials")
            ->with('success', 'Payment successful! Access granted.');
    }
}
