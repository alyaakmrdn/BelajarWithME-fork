<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    public function process(Request $request, $id)
    {
        // Simulate payment success
        $paidCourses = Session::get('paid_courses', []);
        if (!in_array($id, $paidCourses)) {
            $paidCourses[] = $id;
            Session::put('paid_courses', $paidCourses);
        }

        // Redirect to course materials
        return redirect()->route('courses.materials', ['id' => $id])
                         ->with('success', 'Payment successful! Access granted.');
    }
}
