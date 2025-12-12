<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ParentController extends Controller
{
    public function index()
    {
        if (!Session::has('uid') || Session::get('role') !== 'parent') {
            return redirect('/login')->with('error', 'Access denied.');
        }

        return view('login.parent_dashboard');
    }
}
