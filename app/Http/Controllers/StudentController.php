<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StudentController extends Controller
{
    public function index()
    {
        if (!Session::has('uid') || Session::get('role') !== 'student') {
            return redirect('/login')->with('error', 'Access denied.');
        }

        return view('login.student_dashboard');
    }
}
