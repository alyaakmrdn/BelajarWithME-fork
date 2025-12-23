<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #fff5f5; /* light cherry background */
        }

        /* SIDEBAR */
        .sidebar {
            width: 250px;
            background-color: #b3002d; /* cherry red */
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
            background-color: #7a001f; /* darker cherry red */
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 260px;
            transition: 0.3s;
        }

        .main-content.expanded {
            margin-left: 20px;
        }

        .text-cherry {
            color: #b3002d;
        }

        .btn-cherry {
            background-color: #b3002d;
            color: white;
        }
        .btn-cherry:hover {
            background-color: #7a001f;
            color: white;
        }
    </style>
</head>

<body>

    <!-- TOP NAVBAR -->
    <nav class="navbar navbar-expand-lg bg-white shadow-sm px-4 fixed-top">
        <button class="btn btn-outline-secondary me-3" id="toggleSidebar">
            ☰
        </button>

        <a class="navbar-brand fw-bold text-cherry" href="#">
            Lecturer Dashboard
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
                <span>Lecturer</span>
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
                <a class="nav-link" href="{{ route('lecturer.dashboard') }}">Dashboard</a>
            </li>

            <!-- Courses Menu -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#coursesMenu">My Courses ▾</a>
                <div class="collapse ps-3" id="coursesMenu">
                    <a href="#" class="nav-link">Mathematics</a>
                    <a href="#" class="nav-link">Science</a>
                    <a href="#" class="nav-link">English</a>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">Students</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">Assignments</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('lecturer.notifications')}}">Notification</a>
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
    <div class="main-content p-4" id="mainContent">
        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
          <div>
            <h3 class="mb-1">Notifications</h3>
            <p class="text-muted mb-0">
              Messages and updates from administrators
            </p>
          </div>
        </div>

        {{-- Notification Table --}}
        <div class="card shadow-sm">
          <div class="card-body">

            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead class="table-light">
                  <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Received At</th>
                  </tr>
                </thead>

                <tbody>
                @forelse ($notifications as $index => $notification)
                  <tr class="{{ !$notification['read'] ? 'table-warning' : '' }}">
                    <td>{{ $index + 1 }}</td>

                    {{-- Title --}}
                    <td>
                      <strong>{{ $notification['title'] }}</strong>
                    </td>

                    {{-- Message --}}
                    <td style="max-width: 400px;">
                      <div class="text-truncate">
                        {{ $notification['message'] }}
                      </div>
                    </td>

                    {{-- Status --}}
                    <td>
                      @if($notification['read'])
                        <span class="badge bg-secondary">Read</span>
                      @else
                        <span class="badge bg-success">New</span>
                      @endif
                    </td>

                    {{-- Date --}}
                    <td>
                      {{ $notification['created_at'] }}
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                      No notifications received.
                    </td>
                  </tr>
                @endforelse
                </tbody>
              </table>
            </div>

          </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById("toggleSidebar").onclick = function () {
            document.getElementById("sidebar").classList.toggle("collapsed");
            document.getElementById("mainContent").classList.toggle("expanded");
        };
    </script>

</body>
</html>
