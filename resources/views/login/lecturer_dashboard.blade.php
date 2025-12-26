@extends('layouts.lecturer') {{-- adjust path if needed --}}

@section('title', 'Lecturer Dashboard')

@section('content')

<div class="container-fluid">

    <!-- Page Title -->
    <h2 class="fw-bold text-cherry mb-4">Welcome back, Lecturer 👩‍🏫</h2>

    <!-- SUMMARY CARDS -->
    <div class="row g-4 mb-4">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h6 class="text-muted">My Courses</h6>
                    <h2 class="fw-bold text-danger">3</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h6 class="text-muted">Total Students</h6>
                    <h2 class="fw-bold text-primary">45</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h6 class="text-muted">Pending Assignments</h6>
                    <h2 class="fw-bold text-warning">6</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h6 class="text-muted">Classes This Week</h6>
                    <h2 class="fw-bold text-success">5</h2>
                </div>
            </div>
        </div>

    </div>

    <!-- MAIN CONTENT ROW -->
    <div class="row g-4">

        <!-- COURSE OVERVIEW -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header btn-cherry">
                    <h5 class="mb-0">Course Overview</h5>
                </div>

                <div class="card-body">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Course</th>
                                <th>Students</th>
                                <th>Assignments</th>
                                <th>Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Mathematics</td>
                                <td>18</td>
                                <td>3</td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar bg-danger" style="width: 70%"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Science</td>
                                <td>15</td>
                                <td>2</td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar bg-warning" style="width: 55%"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>English</td>
                                <td>12</td>
                                <td>1</td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" style="width: 85%"></div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ANNOUNCEMENTS & TASKS -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header btn-cherry">
                    <h5 class="mb-0">Announcements</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3 p-3 bg-light border-start border-danger border-4 rounded">
                        <strong>📢 Exam Preparation</strong>
                        <p class="mb-0 small">Prepare final exam questions.</p>
                    </div>

                    <div class="mb-3 p-3 bg-light border-start border-warning border-4 rounded">
                        <strong>📝 Assignment Review</strong>
                        <p class="mb-0 small">6 assignments pending grading.</p>
                    </div>

                    <div class="p-3 bg-light border-start border-success border-4 rounded">
                        <strong>📅 Class Reminder</strong>
                        <p class="mb-0 small">English class on Thursday.</p>
                    </div>
                </div>
            </div>

            <!-- QUICK ACTIONS -->
            <div class="card border-0 shadow-sm">
                <div class="card-header btn-cherry">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="#" class="btn btn-outline-danger">Add Assignment</a>
                    <a href="#" class="btn btn-outline-warning">View Students</a>
                    <a href="#" class="btn btn-outline-success">Create Announcement</a>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
