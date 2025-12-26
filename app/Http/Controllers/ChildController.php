<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ChildController extends Controller
{
    protected $auth;
    protected $db;

    public function __construct(FirebaseService $firebase)
    {
        $this->auth = $firebase->auth();
        $this->db = $firebase->db();
    }

    // Show Add Child Page
    public function create()
{
    // Manual session check
    if (!Session::has('uid') || Session::get('role') !== 'parent') {
        return redirect('/login')->with('error', 'Access denied.');
    }

    return view('parent.add_child');
}

public function store(Request $request)
{
    // Manual session check
    if (!Session::has('uid') || Session::get('role') !== 'parent') {
        return redirect('/login')->with('error', 'Access denied.');
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'academic_level' => 'required|string|max:255',
        'email' => 'nullable|email',
        'password' => 'nullable|string|min:6',
    ]);

    try {
        $email = $request->email ?? Str::slug($request->name) . rand(100,999) . '@example.com';
        $password = $request->password ?? Str::random(8);

        // Firebase Auth user
        $childUser = $this->auth->createUser([
            'email' => $email,
            'password' => $password,
        ]);

        // Save child in Firebase DB
        $this->db->getReference('users/' . $childUser->uid)->set([
            'name' => $request->name,
            'role' => 'student',
            'parent_uid' => Session::get('uid'),  // link to parent
            'academic_level' => $request->academic_level,
            'created_at' => now()->toDateTimeString(),
            'email' => $email,
        ]);

        return redirect()->route('children.add')
                         ->with('success', 'Child added successfully!');

    } catch (\Throwable $e) {
        return back()->with('error', "Failed to add child: " . $e->getMessage());
    }
}

}
