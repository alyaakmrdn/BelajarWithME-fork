<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Database;
use Illuminate\Support\Facades\Session;

class CourseController extends Controller
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    // List all courses
    public function index()
    {
        $courses = $this->database->getReference('courses')->getValue();
        return view('courses.index', compact('courses'));
    }

    // Show course details / enrolment page
    public function show($id)
    {
        $course = $this->database->getReference("courses/{$id}")->getValue();
        return view('courses.show', compact('course', 'id'));
    }

    // Show course materials (only if payment verified)
    public function materials($id)
    {
        // Check session for payment status (manual access control)
        $paidCourses = Session::get('paid_courses', []);

        if (!in_array($id, $paidCourses)) {
            return "Access denied! Payment required.";
        }

        $course = $this->database->getReference("courses/{$id}")->getValue();
        return view('courses.materials', compact('course'));
    }
}
