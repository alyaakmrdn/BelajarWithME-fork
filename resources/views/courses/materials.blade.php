<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

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

        .btn-navy {
            background-color: #001f4d;
            color: white;
        }
        .btn-navy:hover {
            background-color: #001033;
            color: white;
        }

        .hdr-navy {
            background-color: #001f4d;
            color: white;
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
            Course Materials
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
                <a class="nav-link" href="#">Dashboard</a>
            </li>

            <!-- Courses Menu -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('student.courses') }}">My Courses</a>
                <div class="collapse ps-3" id="coursesMenu">
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">Assignments</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">Grades</a>
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

<!-- MAIN CONTENT -->
 @php
    $isPaid = true; // already verified in controller
@endphp


<div class="main-content p-4" id="mainContent">
    <div style="height: 80px;"></div>

    <!-- Course Header -->
    <div class="card shadow-sm mb-4 position-relative">
        <div class="card-body">
            <h3 class="text-green mb-1">{{ $course['title'] }}</h3>
            <p class="mb-0 text-muted">
    Lecturer: {{ $course['lecturer'] }} | Price: RM {{ number_format($course['price'], 2) }}
</p>


            @if($isPaid)
                <span class="badge bg-success position-absolute" style="top:10px; right:10px;">
                    Paid ✓
                </span>
            @else
                <span class="badge bg-warning text-dark position-absolute" style="top:10px; right:10px;">
                    Not Paid
                </span>
            @endif
        </div>
    </div>

    <!-- Materials List -->
    <div class="card shadow-sm">
        <div class="card-header hdr-navy">
            Course Materials
        </div>
        <ul class="list-group list-group-flush">
            @foreach($course['materials'] as $material)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-secondary me-2">{{ $material['type'] }}</span>
                        {{ $material['title'] }}
                    </div>

                    @if(isset($material['url']))
                        @if($isPaid)
                            <a href="{{ $material['url'] }}" class="btn btn-sm btn-outline-success">Open</a>
                        @else
                            <button class="btn btn-sm btn-outline-secondary" disabled data-bs-toggle="tooltip" title="Pay to unlock">
                                Locked
                            </button>
                        @endif
                    @else
                        <span class="text-muted">Unavailable</span>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

    <hr class="my-4">

        <h2 class="text-navy">Submit Assignment</h2>

        <div class="card shadow-sm p-4" style="max-width: 600px;">
            <form>

                <label class="fw-bold">Select Course</label>
                <select class="form-select mb-3">
                    <option>Mathematics</option>
                    <option>Science</option>
                    <option>English</option>
                </select>

                <label class="fw-bold">Assignment Title</label>
                <input type="text" class="form-control mb-3" placeholder="Assignment title">

                <label class="fw-bold">Upload File / Link</label>
                <input type="file" class="form-control mb-3">

                <button class="btn btn-navy">Submit</button>

            </form>
        </div>

    <!-- Back Button -->
    <div class="mt-4">
        <a href="/parent_dashboard" class="btn btn-secondary">← Back to Dashboard</a>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Tooltip & Sidebar Toggle -->
<script>
    document.getElementById("toggleSidebar").onclick = function () {
        document.getElementById("sidebar").classList.toggle("collapsed");
        document.getElementById("mainContent").classList.toggle("expanded");
    };

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>

</body>
</html>
