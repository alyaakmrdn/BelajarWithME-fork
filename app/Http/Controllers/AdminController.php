<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\FirebaseService;

class AdminController extends Controller
{
     public function index()
    {
        if (!Session::has('uid') || Session::get('role') !== 'admin') {
            return redirect('/login')->with('error', 'Access denied.');
        }

        return view('login.admin_dashboard');
    }
}
