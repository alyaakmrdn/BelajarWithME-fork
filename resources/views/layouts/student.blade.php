<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Student Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="cdn.jsdelivr.net">

    <style>
        body {
            background-color: #e6eaf0; /* light navy background */
        }

        /* SIDEBAR */
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

        .btn-navy,.hdr-navy {
            background-color: #001f4d;
            color: white;
        }
        .btn-navy:hover {
            background-color: #001033;
            color: white;
        }
        
.bg-navy { background-color: #000080; }
.btn-outline-navy { border-color: #000080; color: #000080; }
.btn-outline-navy:hover { background-color: #000080; color: white; }
.bg-success-subtle { background-color: #d1e7dd; }
.bg-danger-subtle { background-color: #f8d7da; }
.bg-primary-subtle { background-color: #cfe2ff; }
.hover-shadow:hover { box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;}
.transition { transition: 0.3s; }

        .course-inactive {
            opacity: 0.6;
            filter: grayscale(100%);
            pointer-events: none;
        }

        .course-inactive .badge {
            pointer-events: auto;
        }
    </style>
    @stack('styles')
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
                <img src="https://via.placeholder.com/40" class="rounded-circle me-2">
                <span>Student</span>
            </a>

            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('student.profile.view') }}">My Profile</a></li>
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
                    <a href="{{ route('student.courses') }}" class="nav-link">Courses Material</a>
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
                    <a href="{{ route('student.profile.view') }}" class="nav-link">My Profile</a>
                    <a href="#" class="nav-link">Change Password</a>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link text-danger" href="/logout">Logout</a>
            </li>

        </ul>
    </div>

    <!-- MAIN CONTENT -->
<div class="main-content p-4" id="mainContent">
    <div style="height: 80px;"></div>

    {{-- PAGE CONTENT --}}
    @yield('content')
</div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Sidebar Toggle Script -->
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

    @stack('scripts')

</body>
</html>
