<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f0fff0; /* light forest green background */
        }

        /* SIDEBAR */
        .sidebar {
            width: 250px;
            background-color: #228B22; /* forest green */
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
            background-color: #145214;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 260px;
            transition: 0.3s;
        }
        .main-content.expanded {
            margin-left: 20px;
        }

        .text-green {
            color: #228B22;
        }

        .btn-green {
            background-color: #228B22;
            color: white;
        }
        .btn-green:hover {
            background-color: #145214;
            color: white;
        }
    </style>
</head>

<body>

<!-- TOP NAVBAR -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm px-4 fixed-top">
    <button class="btn btn-outline-secondary me-3" id="toggleSidebar">☰</button>
    <a class="navbar-brand fw-bold text-green" href="#">Courses</a>
    <div class="ms-auto"></div>
</nav>

<!-- SIDEBAR -->
<div class="sidebar p-3" id="sidebar">
    <h4 class="text-center mb-4">Menu</h4>
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link" href="#">Dashboard</a></li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('parent.children') }}">My Children</a>
            <!--<div class="collapse ps-3" id="childrenMenu">
                <a href="#" class="nav-link">Child 1</a>
                <a href="#" class="nav-link">Child 2</a>
                <a href="#" class="nav-link">Child 3</a>
            </div>-->
        </li>
        <a class="nav-link" href="{{ route('parent.courses') }}">Courses</a>
        <li class="nav-item"><a class="nav-link" href="#">Grades & Reports</a></li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#profileMenu">Profile Settings ▾</a>
            <div class="collapse ps-3" id="profileMenu">
                <a href="#" class="nav-link">Edit Profile</a>
                <a href="#" class="nav-link">Change Password</a>
            </div>
        </li>
        <li class="nav-item"><a class="nav-link" href="#">Transaction</a></li>
        <li class="nav-item"><a class="nav-link text-danger" href="/logout">Logout</a></li>
    </ul>
</div>

<!-- MAIN CONTENT -->
 <div class="main-content p-4" id="mainContent">
    <div style="height: 80px;"></div>

    <h2 class="text-green mb-4">Available Courses</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-3">
        @foreach($courses as $course)

<div class="col-md-4 mb-3">
    <div class="card shadow-sm h-100">
        <div class="card-body">
        <h5>{{ $course['title'] }}</h5>
        <p class="text-muted mb-2">{{ $course['subtitle'] }}</p>
        <p class="mb-1"><strong>Class:</strong> {{ $course['class'] }}</p>
        <p class="mb-1"><strong>Lecturer:</strong> {{ $course['lecturer'] }}</p>
        <p class="mb-1"><strong>Duration:</strong> {{ $course['duration'] }}</p>
        <hr>
        <p class="text-primary">Price: RM {{ $course['price'] }}</p>

        @if(in_array($course['id'], $paidCourses))
            <button class="btn btn-secondary" disabled>
                Already Enrolled
            </button>
        @else
            <form method="POST" action="{{ route('enroll.summary') }}">
                @csrf
                <input type="hidden" name="course_id" value="{{ $course['id'] }}">
                <input type="hidden" name="child_id" value="{{ $child_id }}">
                <button class="btn btn-success">
                    Enroll Now
                </button>
            </form>
        @endif
        </div>
    </div>
</div>

@endforeach

<!-- Back Button -->
    <div class="mt-4">
        <a href="parent/courses" class="btn btn-secondary">← Back</a>
    </div>

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

