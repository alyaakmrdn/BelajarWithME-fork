<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f0ff;
        }

        /* SIDEBAR */
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

        /* MAIN CONTENT */
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
        <a class="navbar-brand fw-bold text-purple" href="#">
            Admin Dashboard
        </a>

        <!-- Spacer -->
        <div class="ms-auto"></div>

        <!-- Search Bar -->
        <form class="d-none d-md-flex me-3">
            <input class="form-control" type="search" placeholder="Search..." aria-label="Search">
        </form>

        <!-- Profile Dropdown -->
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
                <a class="nav-link" href="#">Dashboard</a>
            </li>

            <!-- Courses Menu -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#coursesMenu">Courses ▾</a>
                <div class="collapse ps-3" id="coursesMenu">
                    <a href="#" class="nav-link">Mathematics</a>
                    <a href="#" class="nav-link">Science</a>
                    <a href="#" class="nav-link">English</a>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">Subjects</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">Lecturers</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.manage.reports') }}">Manage Reports</a>
            </li>

            <!-- User Profile -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#profileMenu">User Profile ▾</a>
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
        <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
          <div>
            <h3 class="mb-1">Manage Reports</h3>
            <p class="text-muted mb-0">
              Review and manage reports submitted by users
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
                    <th>Course</th>
                    <th>Reason</th>
                    <th>Description</th>
                    <th>Reported By</th>
                    <th>Status</th>
                    <th>Reported At</th>
                    <th>Action</th>
                  </tr>
                </thead>

                <tbody>
                @forelse ($reports as $index => $report)
                  <tr>
                    {{-- Index --}}
                    <td>{{ $index + 1 }}</td>

                    {{-- Course --}}
                    <td>
                      <strong>{{ $report['course_name'] ?? $report['course_id'] }}</strong>
                      <div class="text-muted small">
                        {{ $report['course_id'] }}
                      </div>
                    </td>

                    {{-- Reason --}}
                    <td>
                      <span class="badge bg-info text-dark">
                        {{ ucfirst(str_replace('_', ' ', $report['reason'])) }}
                      </span>
                    </td>

                    {{-- Description --}}
                    <td style="max-width: 280px;">
                      <div class="text-truncate">
                        {{ $report['description'] }}
                      </div>
                    </td>

                    {{-- Reporter --}}
                    <td>
                      <span class="badge bg-secondary">
                        {{ ucfirst($report['reporter_role']) }}
                      </span>
                    </td>

                    {{-- Status --}}
                    <td>
                      @if ($report['status'] === 'pending')
                        <span class="badge bg-warning text-dark">Pending</span>
                      @elseif ($report['status'] === 'reviewed')
                        <span class="badge bg-primary">Reviewed</span>
                      @elseif ($report['status'] === 'resolved')
                        <span class="badge bg-success">Resolved</span>
                      @else
                        <span class="badge bg-secondary">
                          {{ ucfirst($report['status']) }}
                        </span>
                      @endif
                    </td>

                    {{-- Date --}}
                    <td>
                      {{ $report['created_at'] }}
                    </td>

                    {{-- Actions --}}
                    <td>
                      <div class="d-flex gap-2">

                        {{-- Review Button --}}
                        <button
                          class="btn btn-sm btn-outline-primary"
                          data-bs-toggle="modal"
                          data-bs-target="#reviewModal-{{ $report['id'] }}"
                          @if(empty($report['action'])) disabled @endif
                        >
                          Review
                        </button>

                        {{-- Resolve Button --}}
                        <button
                          class="btn btn-sm btn-outline-success"
                          data-bs-toggle="modal"
                          data-bs-target="#resolveModal-{{ $report['id'] }}"
                          @if($report['status'] !== 'pending') disabled @endif
                        >
                          Resolve
                        </button>

                      </div>
                    </td>
                  </tr>

                  <div class="modal fade" id="resolveModal-{{ $report['id'] }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                      <div class="modal-content">

                        <form method="POST" action="{{ route('admin.resolve.report') }}">
                          @csrf

                          <input type="hidden" name="report_id" value="{{ $report['id'] }}">
                          <input type="hidden" name="course_id" value="{{ $report['course_id'] }}">
                          <input type="hidden" name="lecturer_id" value="{{ $report['lecturer_id'] }}">

                          <div class="modal-header">
                            <h5 class="modal-title">Resolve Report</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>

                          <div class="modal-body">

                            {{-- Course --}}
                            <p><strong>Course:</strong> {{ $report['course_name'] }}</p>

                            {{-- Resolution Type --}}
                            <div class="mb-3">
                              <label class="form-label">Resolution Decision</label>
                              <select name="resolution_type" class="form-select" required>
                                <option value="">-- Select Action --</option>
                                <option value="deactivate">Deactivate Course</option>
                                <option value="no_issue">Resolve – No Issue Found</option>
                              </select>
                            </div>

                            {{-- Action Note --}}
                            <div class="mb-3">
                              <label class="form-label">Action Note (Visible to Student)</label>
                              <textarea
                                name="action_note"
                                class="form-control"
                                rows="3"
                                required
                                placeholder="Explain the resolution decision..."
                              ></textarea>
                            </div>

                            <hr>

                            {{-- Notification --}}
                            <h6 class="mb-2">Notify Lecturer (Optional)</h6>

                            <div class="mb-3">
                              <label class="form-label">Message Title</label>
                              <input
                                type="text"
                                name="message_title"
                                class="form-control"
                                placeholder="e.g. Course Status Update"
                              >
                            </div>

                            <div class="mb-3">
                              <label class="form-label">Message Content</label>
                              <textarea
                                name="message_body"
                                class="form-control"
                                rows="3"
                                placeholder="Write message to lecturer..."
                              ></textarea>
                            </div>

                            <div class="alert alert-info small">
                              If message title and content are provided, a notification will be sent to the lecturer.
                            </div>

                          </div>

                          <div class="modal-footer">
                            <button type="submit" class="btn btn-success">
                              Confirm Resolution
                            </button>
                          </div>

                        </form>

                      </div>
                    </div>
                  </div>

                  <div class="modal fade" id="reviewModal-{{ $report['id'] }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">

                        <div class="modal-header">
                          <h5 class="modal-title">Admin Action</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                          <p><strong>Action:</strong> {{ $report['action']['action'] ?? '-' }}</p>
                          <p><strong>Admin Note:</strong></p>
                          <p class="border p-2 rounded bg-light">
                            {{ $report['action']['note'] ?? 'No action recorded.' }}
                          </p>
                          <p class="text-muted small">
                            {{ $report['action']['created_at'] ?? '' }}
                          </p>
                        </div>

                      </div>
                    </div>
                  </div>
                @empty
                  <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                      No reports available.
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
