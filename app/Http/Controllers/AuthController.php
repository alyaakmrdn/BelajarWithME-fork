<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Session;
use Kreait\Firebase\Exception\Auth\InvalidPassword;
use Kreait\Firebase\Exception\Auth\UserNotFound;

class AuthController extends Controller
{
    protected $auth;
    protected $db;

    public function __construct(FirebaseService $firebase)
    {
        $this->auth = $firebase->auth();
        $this->db = $firebase->db();
    }

    // 🔹 Show Signup Page
    public function showSignup()
    {
        if (Session::has('uid')) {
            return $this->redirectByRole(Session::get('role'));
        }

        return view('login.signup'); // matches your folder structure
    }

    // 🔹 Signup new user
    public function signup(Request $request)
{
    $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
        'role' => 'required|in:student,parent,lecturer,admin'
    ]);

    try {
        $user = $this->auth->createUser([
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        $this->db->getReference('users/' . $user->uid)->set([
            'email' => $validated['email'],
            'role' => $validated['role'],
            'created_at' => now()->toDateTimeString(),
        ]);

        Session::put('uid', $user->uid);
        Session::put('role', $validated['role']);

        // Redirect based on role
        return $this->redirectByRole($validated['role']);

    } catch (\Throwable $e) {
        return back()->with('error', $e->getMessage());
    }
}



    // 🔹 Show Login Page
    public function showLogin()
    {
        if (Session::has('uid')) {
            return $this->redirectByRole(Session::get('role'));
        }

        return view('login.login'); // matches your folder structure
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        try {
            $signInResult = $this->auth->signInWithEmailAndPassword(
                $validated['email'],
                $validated['password']
            );

            $uid = $signInResult->firebaseUserId();
            $userData = $this->db->getReference('users/' . $uid)->getValue();
            $role = $userData['role'] ?? 'guest';

            Session::put('uid', $uid);
            Session::put('role', $role);
            Session::put('firebase_user', [
                'email' => $validated['email'],
            ]);

            // Redirect based on role
            return $this->redirectByRole($role);

        } catch (InvalidPassword $e) {
            return back()->with('error', 'Wrong password.');
        } catch (UserNotFound $e) {
            return back()->with('error', 'User does not exist.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Login failed: ' . $e->getMessage());
        }
    }

    protected function redirectByRole($role)
    {
        switch ($role) {
            case 'admin':
                return redirect('/admin_dashboard');
            case 'student':
                return redirect('/student_dashboard');
            case 'lecturer':
                return redirect('/lecturer_dashboard');
            case 'parent':
                return redirect('/parent_dashboard');
            default:
                return redirect('/login');
        }
    }



    // 🔹 Logout
    public function logout()
    {
        session()->flush();
        return redirect('/login');
    }
}
