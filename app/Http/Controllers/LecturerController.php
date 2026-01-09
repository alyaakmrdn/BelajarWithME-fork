<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\FirebaseService;

class LecturerController extends Controller
{
    protected Database $database;
    
    public function index()
    {
        if (!Session::has('uid') || Session::get('role') !== 'lecturer') {
            return redirect('/login')->with('error', 'Access denied.');
        }

        return view('login.lecturer_dashboard');
    }

    protected $db;

    public function __construct(FirebaseService $firebase)
    {
        $this->db = $firebase->db();
    }

    public function notificationIndex()
    {
        $lecturerUid = session('uid');

        if (!$lecturerUid) {
            return redirect()->route('login')->withErrors('Session expired');
        }

        /** 1️⃣ Fetch notifications for this lecturer */
        $notificationsRaw = $this->database
            ->getReference('notification')
            ->orderByChild('user_id')
            ->equalTo($lecturerUid)
            ->getValue();

        if (!$notificationsRaw) {
            return view('courses.lecturer_notification', [
                'notifications' => []
            ]);
        }

        /** 2️⃣ Normalize data for view */
        $notifications = [];

        foreach ($notificationsRaw as $notificationId => $notification) {

            if (!is_array($notification)) continue;

            $notifications[] = [
                'id'         => $notificationId,
                'title'      => $notification['title'] ?? 'Notification',
                'message'    => $notification['message'] ?? '-',
                'read'       => $notification['read'] ?? false,
                'created_at' => $notification['created_at'] ?? '-',
            ];
        }

        foreach ($notificationsRaw as $id => $n) {
            if (!($n['read'] ?? false)) {
                $this->database
                    ->getReference("notification/{$id}/read")
                    ->set(true);
            }
        }

        return view('courses.lecturer_notification', [
            'notifications' => $notifications
        ]);
    }

public function profile()
{
    if (!Session::has('uid') || Session::get('role') !== 'lecturer') {
        return redirect('/login')->with('error', 'Access denied.');
    }

    return view('lecturer.profile_lecturer_view');
}

public function editProfile()
{
    if (!Session::has('uid') || Session::get('role') !== 'lecturer') {
        return redirect('/login')->with('error', 'Access denied.');
    }

    return view('lecturer.profile_lecturer_edit');
}

public function updateProfile(Request $request)
{
    if (!Session::has('uid') || Session::get('role') !== 'lecturer') {
        return redirect('/login')->with('error', 'Access denied.');
    }

    $uid = session('uid');

    $request->validate([
        'name'  => 'required|string|max:255',
        'email' => 'required|email',
        'gender' => 'required|string',
        'department' => 'required|string|max:255',
        'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $updateData = [
        'name'       => $request->name,
        'email'      => $request->email,
        'gender'     => $request->gender,
        'department' => $request->department,
    ];

    // 📸 Profile picture
    if ($request->hasFile('profile_picture')) {
        $file = $request->file('profile_picture');
        $filename = 'lecturer_' . $uid . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('profile', $filename, 'public');

        $updateData['profile_picture'] = asset('storage/' . $path);
    }

    $this->db
        ->getReference("users/{$uid}")
        ->update($updateData);

    return redirect()
        ->route('lecturer.profile.view')
        ->with('success', 'Profile updated successfully.');
}


    
}
