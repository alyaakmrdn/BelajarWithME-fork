<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Kreait\Firebase\Database;

class ParentController extends Controller
{
    protected Database $database;

    public function __construct()
    {
        $this->database = app('firebase.database');
    }

    public function index()
    {
        if (!Session::has('uid') || Session::get('role') !== 'parent') {
            return redirect('/login')->with('error', 'Access denied.');
        }

        return view('login.parent_dashboard');
    }

    public function manageReports()
    {
        $parentUid = session('uid');

        if (!$parentUid || session('role') !== 'parent') {
            return redirect()->route('login');
        }

        /** 1️⃣ Get parent user info */
        $parent = $this->database
            ->getReference("users/{$parentUid}")
            ->getValue();

        if (!$parent || empty($parent['child_id'])) {
            return view('courses.parent_manageReport', [
                'reports' => []
            ]);
        }

        $childUid = $parent['child_id'];

        $child = $this->database
            ->getReference("users/{$childUid}")
            ->getValue();

        $childName = $child['name'] ?? 'Child';

        /** 2️⃣ Get all reports */
        $reportsRaw = $this->database
            ->getReference('reports')
            ->getValue() ?? [];

        /** 3️⃣ Get courses (for course names) */
        $courses = $this->database
            ->getReference('courses')
            ->getValue() ?? [];

        $reports = [];

        foreach ($reportsRaw as $reportId => $report) {
            if (!is_array($report)) continue;

            // ✅ Only reports submitted by this parent's child
            if (($report['reporter_id'] ?? null) !== $childUid) {
                continue;
            }

            $courseId = $report['course_id'] ?? null;

            $reports[] = [
                'id'           => $reportId,
                'child_name'   => $childName,
                'course_name'  => $courses[$courseId]['name'] ?? $courseId,
                'reason'       => $report['reason'] ?? '-',
                'description'  => $report['description'] ?? '-',
                'status'       => $report['status'] ?? 'pending',
                'created_at'   => $report['created_at'] ?? '-',
                'updated_at'   => $report['updated_at'] ?? '-',
                'action'       => $this->getReportAction($reportId),
            ];
        }

        return view('courses.parent_manageReport', [
            'reports' => $reports
        ]);
    }

    /** 🔹 Fetch admin action (same as student side) */
    private function getReportAction(string $reportId): ?array
    {
        $actions = $this->database
            ->getReference('report_action')
            ->orderByChild('report_id')
            ->equalTo($reportId)
            ->getValue();

        if (!$actions) return null;

        return collect($actions)->first();
    }
}
