<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LecturerController extends Controller
{
    public function index()
    {
        if (!Session::has('uid') || Session::get('role') !== 'lecturer') {
            return redirect('/login')->with('error', 'Access denied.');
        }

        return view('login.lecturer_dashboard');
    }
}
