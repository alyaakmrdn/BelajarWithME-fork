<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="cdn.jsdelivr.net">
    
    <style>
        body {
            background-color: #f5f0ff;
        }

        .sidebar {
            width: 250px;
            background-color: #6a0dad;
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
            background-color: #4b0082;
        }

        .main-content {
            margin-left: 260px;
            transition: 0.3s;
        }

        .main-content.expanded {
            margin-left: 20px;
        }

        .text-purple {
            color: #6a0dad;
        }

        .btn-purple {
            background-color: #6a0dad;
            color: white;
        }

        .btn-purple:hover {
            background-color: #4b0082;
        }
    </style>

    @stack('styles')
</head>

<body>

<!-- TOP NAVBAR -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm px-4 fixed-top">
    <button class="btn btn-outline-secondary me-3" id="toggleSidebar">☰</button>

    <a class="navbar-brand fw-bold text-purple" href="#">
        Admin Dashboard
    </a>

    <div class="ms-auto"></div>

    <div class="dropdown">
        <a class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
            <img src="https://via.placeholder.com/40" class="rounded-circle me-2">
            <span>Admin</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#">Edit Profile</a></li>
            <li><a class="dropdown-item" href="#">Change Password</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="/logout">Logout</a></li>
        </ul>
    </div>
</nav>

<!-- SIDEBAR -->
<div class="sidebar p-3" id="sidebar">
    <h4 class="text-center mb-4">Menu</h4>

    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ url('/admin_dashboard') }}">Dashboard</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.transactions') }}">Transactions</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#">Courses</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#">Lecturers</a>
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

<script>
    document.getElementById("toggleSidebar").onclick = function () {
        document.getElementById("sidebar").classList.toggle("collapsed");
        document.getElementById("mainContent").classList.toggle("expanded");
    };
</script>

@stack('scripts')

</body>
</html>
