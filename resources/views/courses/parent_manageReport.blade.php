<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard</title>

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
            background-color: #145214; /* darker forest green */
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
        <!-- Toggle Sidebar -->
        <button class="btn btn-outline-secondary me-3" id="toggleSidebar">
            ☰
        </button>

        <!-- System Title -->
        <a class="navbar-brand fw-bold text-green" href="#">
            Parent Dashboard
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
                <span>Parent</span>
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
                <a class="nav-link" href="{{ route('parent.dashboard')}}">Dashboard</a>
            </li>

            <!-- Children Menu -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#childrenMenu">My Children ▾</a>
                <div class="collapse ps-3" id="childrenMenu">
                    <a href="#" class="nav-link">Child 1</a>
                    <a href="#" class="nav-link">Child 2</a>
                    <a href="#" class="nav-link">Child 3</a>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">Courses</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">Grades & Reports</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('parent.manage.reports')}}">View Reports</a>
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
    <!-- MAIN CONTENT -->
    <div class="main-content p-4" id="mainContent">
        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
          <div>
            <h3 class="mb-1">Child Reports</h3>
            <p class="text-muted mb-0">
              View reports submitted by your child
            </p>
          </div>
        </div>

        {{-- Reports Table --}}
        <div class="card shadow-sm">
          <div class="card-body">

            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead class="table-light">
                  <tr>
                    <th>#</th>
                    <th>Child</th>
                    <th>Course</th>
                    <th>Reason</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Admin Action</th>
                    <th>Submitted At</th>
                  </tr>
                </thead>

                <tbody>
                @forelse ($reports as $index => $report)
                  <tr>
                    <td>{{ $index + 1 }}</td>

                    {{-- Child --}}
                    <td>
                      <strong>{{ $report['child_name'] }}</strong>
                    </td>

                    {{-- Course --}}
                    <td>{{ $report['course_name'] }}</td>

                    {{-- Reason --}}
                    <td>
                      <span class="badge bg-info text-dark">
                        {{ ucfirst(str_replace('_', ' ', $report['reason'])) }}
                      </span>
                    </td>

                    {{-- Description --}}
                    <td style="max-width: 300px;">
                      <div class="text-truncate">
                        {{ $report['description'] }}
                      </div>
                    </td>

                    {{-- Status --}}
                    <td>
                      @if ($report['status'] === 'pending')
                        <span class="badge bg-warning">Pending</span>
                      @else
                        <span class="badge bg-success">Resolved</span>
                      @endif
                    </td>

                    {{-- Admin Action --}}
                    <td style="max-width:300px;">
                      @if ($report['status'] === 'resolved' && !empty($report['action']))
                        <div><strong>{{ $report['action']['action'] }}</strong></div>
                        <div class="text-muted small">
                          {{ $report['action']['note'] }}
                        </div>
                      @else
                        <span class="text-muted">-</span>
                      @endif
                    </td>

                    {{-- Date --}}
                    <td>{{ $report['created_at'] }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                      No reports found for your child.
                    </td>
                  </tr>
                @endforelse
                </tbody>
              </table>
            </div>

          </div>
        </div>

    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Sidebar Toggle Script -->
    <script>
        document.getElementById("toggleSidebar").onclick = function () {
            document.getElementById("sidebar").classList.toggle("collapsed");
            document.getElementById("mainContent").classList.toggle("expanded");
        };
    </script>

</body>
</html>
