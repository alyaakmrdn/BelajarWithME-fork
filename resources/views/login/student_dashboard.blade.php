@extends('layouts.student') {{-- change to your actual layout path --}}

@section('title', 'Student Dashboard')

@section('content')

<div class="container-fluid">

    <!-- Page Title -->
     <div class="col-md-8">
    <h2 class="fw-bold text-navy mb-4">Welcome back, Student 👋</h2>
    <p class="text-muted">Here is what's happening with your studies.</p>
    </div>
    <!-- STAT CARDS -->
    <div class="row g-4 mb-4">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm hover-shadow transition">
                <div class="card-body text-center">
                    <h6 class="text-muted">Enrolled Courses</h6>
                    <h2 class="fw-bold text-primary">4</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm hover-shadow transition">
                <div class="card-body text-center">
                    <h6 class="text-muted">Pending Assignments</h6>
                    <h2 class="fw-bold text-danger">2</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm hover-shadow transition">
                <div class="card-body text-center">
                    <h6 class="text-muted">Average Grade</h6>
                    <h2 class="fw-bold text-success">85%</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm hover-shadow transition">
                <div class="card-body text-center">
                    <h6 class="text-muted">Upcoming Classes</h6>
                    <h2 class="fw-bold text-warning">3</h2>
                </div>
            </div>
        </div>

    </div>

    <!-- MAIN ROW -->
    <div class="row g-4">

        <!-- MY COURSES -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header hdr-navy">
                    <h5 class="mb-0">My Courses</h5>
                </div>

                @foreach($courses as $course)
                    @php
                        $isInactive = ($course['status'] ?? 'active') === 'inactive';
                    @endphp
                    <div class="col-md-4">
                        <div class="card shadow-sm {{ $isInactive ? 'course-inactive' : '' }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $course['name'] }}
                                    @if($isInactive)
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </h5>
                                

                                <p class="card-text">
                                    Lecturer: {{ $course['lecturer_email'] }} <br>
                                    Status: {{ ucfirst($course['status']) }}
                                </p>

                                @if($isInactive)
                                    <div class="alert alert-warning small mt-2">
                                        This course has been deactivated by admin.
                                    </div>
                                @endif

                                <a
                                    href="#"
                                    class="btn btn-primary btn-sm {{ $isInactive ? 'disabled' : '' }}">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="card-body">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Course</th>
                                <th>Tutor</th>
                                <th>Progress</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Mathematics</td>
                                <td>Mr. Ali</td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" style="width: 80%"></div>
                                    </div>
                                </td>
                                <td><span class="badge bg-success">Active</span></td>
                            </tr>
                            <tr>
                                <td>Science</td>
                                <td>Ms. Aina</td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar bg-warning" style="width: 60%"></div>
                                    </div>
                                </td>
                                <td><span class="badge bg-warning">Ongoing</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ANNOUNCEMENTS -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header hdr-navy">
                    <h5 class="mb-0">Announcements</h5>
                </div>

                <div class="card-body">
                    <div class="mb-3 p-3 bg-primary-subtle rounded">
                        <strong>📢 Exam Week</strong>
                        <p class="mb-0 small">Final exams start next Monday.</p>
                    </div>

                    <div class="mb-3 p-3 bg-success-subtle rounded">
                        <strong>✅ Assignment Submitted</strong>
                        <p class="mb-0 small">Math homework has been graded.</p>
                    </div>

                    <div class="p-3 bg-danger-subtle rounded">
                        <strong>⚠ Class Rescheduled</strong>
                        <p class="mb-0 small">Science class moved to Friday.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
