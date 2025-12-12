<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;

class FirebaseTestController extends Controller
{
    public function index(FirebaseService $firebase)
    {
        $db = $firebase->getDatabase();

        // Example write
        $db->getReference('users/user1')->set([
            'name' => 'Alyaa',
            'role' => 'admin'
        ]);

        // Example read
        $users = $db->getReference('users')->getValue();

        return view('firebase-test', compact('users'));
    }
}
