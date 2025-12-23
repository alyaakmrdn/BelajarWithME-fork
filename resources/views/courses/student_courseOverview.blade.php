<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Overview</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #e6eaf0;
        }

        .sidebar {
            width: 250px;
            background-color: #001f4d; /* navy blue */
            position: fixed;
            height: 100%;
            color: #fff;
            transition: 0.3s;
            overflow-y: auto;
        }

        .sidebar.collapsed {
            margin-left: -250px;
        }

        .sidebar a {
            color: #fff;
        }

        .sidebar .nav-link:hover {
            background-color: #001033; /* darker navy */
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 260px;
            transition: 0.3s;
        }

        .main-content.expanded {
            margin-left: 20px;
        }

        .text-navy {
            color: #001f4d;
        }

        .btn-navy {
            background-color: #001f4d;
            color: white;
        }

        .btn-navy:hover {
            background-color: #001033;
            color: white;
        }

        .card-subject {
            border-left: 6px solid #001f4d;
        }

        .course-inactive {
            opacity: 0.6;
            filter: grayscale(100%);
            pointer-events: none;
        }

        .course-inactive .badge {
            pointer-events: auto;
        }
    </style>
</head>

<body>

<!-- TOP NAVBAR -->
    <nav class="navbar navbar-expand-lg bg-white shadow-sm px-4 fixed-top">
        <!-- Toggle Sidebar -->
        <button class="btn btn-outline-secondary me-3" id="toggleSidebar">
            ☰
        </button>

        <!-- System Title -->
        <a class="navbar-brand fw-bold text-navy" href="#">
            Student Dashboard
        </a>

        <div class="ms-auto"></div>

        <!-- Search Bar -->
        <form class="d-none d-md-flex me-3">
            <input class="form-control" type="search" placeholder="Search..." aria-label="Search">
        </form>

        <!-- Profile Dropdown -->
        <div class="dropdown">
            <a class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                <!-- <img src="https://via.placeholder.com/40" class="rounded-circle me-2"> -->
                <span>Student</span>
            </a>

            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#">Edit Profile</a></li>
                <li><a class="dropdown-item" href="#">Change Password</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a href="/logout" class="dropdown-item text-danger">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- SIDEBAR -->
    <div class="sidebar p-3" id="sidebar">
        <h4 class="text-center mb-4">Menu</h4>

        <ul class="nav flex-column">

            <li class="nav-item">
                <a class="nav-link" href="{{ route('student.dashboard') }}">Dashboard</a>
            </li>

            <!-- Courses Menu -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#coursesMenu">My Courses ▾</a>
                <div class="collapse ps-3" id="coursesMenu">
                    <a href="{{ route('student.course.overview') }}" class="nav-link">Course Overview</a>
                    <a href="#" class="nav-link">Mathematics</a>
                    <a href="#" class="nav-link">Science</a>
                    <a href="#" class="nav-link">English</a>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">Assignments</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">Grades</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('student.report.status') }}">Report Status</a>
            </li>

            <!-- Profile -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#profileMenu">Profile Settings ▾</a>
                <div class="collapse ps-3" id="profileMenu">
                    <a href="#" class="nav-link">Edit Profile</a>
                    <a href="#" class="nav-link">Change Password</a>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link text-danger" href="/logout">Logout</a>
            </li>

        </ul>
    </div>

    <div class="main-content p-4" id="mainContent">

        <h2 class="text-navy mb-4">Course Overview</h2>
        <div class="row g-4">
            @foreach($courses as $courseId => $course)
                @php
                    $isInactive = ($course['status'] ?? 'active') === 'inactive';
                @endphp
            <div class="col-md-4">
                <div class="card shadow-sm {{ $isInactive ? 'course-inactive' : '' }}">
                    <div class="card-body">
                        <h5 class="card-title d-flex justify-content-between align-items-center">
                        {{ $course['name'] }}

                        @if($isInactive)
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                        </h5>


                        <p class="card-text">
                            <strong>Course Code:</strong> {{ $courseId }} <br>
                            <strong>Lecturer:</strong> {{ $course['lecturer_email'] }} <br>
                            <strong>Status:</strong> {{ ucfirst($course['status']) }}
                        </p>

                        @if($isInactive)
                            <div class="alert alert-warning small mt-2">
                                This course has been deactivated by admin.
                            </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a
                                href="#"
                                class="btn btn-primary btn-sm {{ $isInactive ? 'disabled' : '' }}">
                                View
                            </a>

                            <button
                                class="btn btn-outline-danger btn-sm" type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#reportModal-{{ $courseId }}   
                                    {{ $isInactive ? 'disabled' : '' }}">
                                Report
                            </button>
                        </div>

                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Modal -->
     @foreach($courses as $courseId => $course)
    <div class="modal fade" id="reportModal-{{ $courseId }}" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('student.report.submit') }}">
                @csrf

                <input type="hidden" name="course_id" value="{{ $courseId }}">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Report {{ $course['name'] }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <label>Reason</label>
                        <select name="reason" class="form-select" required>
                            <option value="inappropriate_content">Inappropriate Content</option>
                            <option value="incorrect_information">Incorrect Information</option>
                        </select>

                        <label class="mt-2">Description</label>
                        <textarea name="description" class="form-control" required></textarea>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-danger">Submit Report</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endforeach

    @if (session('success'))
    <div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Success</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            {{ session('success') }}
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-success" data-bs-dismiss="modal">
            OK
            </button>
        </div>
        </div>
    </div>
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById("toggleSidebar").onclick = function () {
            document.getElementById("sidebar").classList.toggle("collapsed");
            document.getElementById("mainContent").classList.toggle("expanded");
        };

        const reportModal = document.getElementById('reportModal');

        reportModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const course = button.getAttribute('data-course');
            document.getElementById('courseCode').value = course;
        });

        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                var successModal = new bootstrap.Modal(
                    document.getElementById('successModal')
                );
                successModal.show();
            @endif
        });
    </script>

    @if (session('success'))
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var successModal = new bootstrap.Modal(
        document.getElementById('successModal')
        );
        successModal.show();
    });
    </script>
    @endif
</body>
</html>
