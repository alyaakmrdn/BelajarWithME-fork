<!-- resources/views/parent/transactions.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Transactions</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background-color: #f0fff0; }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #228B22;
            position: fixed;
            height: 100%;
            color: #fff;
            transition: 0.3s;
            overflow-y: auto;
        }
        .sidebar.collapsed { margin-left: -250px; }
        .sidebar a { color: #fff; }
        .sidebar .nav-link:hover { background-color: #145214; }

        /* Main content */
        .main-content { margin-left: 260px; transition: 0.3s; }
        .main-content.expanded { margin-left: 20px; }

        .text-green { color: #228B22; }
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
        <a class="navbar-brand fw-bold text-green" href="#">My Transactions</a>
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
            <li class="nav-item"><a class="nav-link" href="{{ route('transactions') }}">Transaction</a></li>
            <li class="nav-item"><a class="nav-link text-danger" href="/logout">Logout</a></li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content p-4" id="mainContent">
        <div style="height: 80px;"></div>

        <h2 class="text-green mb-4">Transaction History</h2>

        <!-- Filters -->
<form method="GET" class="mb-3 row g-2">
    <!-- Child Dropdown -->
    <div class="col-md-3">
        <select name="child_id" class="form-select">
            <option value="">All Children</option>
            @foreach($children as $id => $name)
                <option value="{{ $id }}" {{ request('child_id')==$id?'selected':'' }}>
                    {{ $name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Course Dropdown -->
    <div class="col-md-3">
        <select name="course_id" class="form-select">
            <option value="">All Courses</option>
            @foreach($dummyCourses as $id => $course)
                <option value="{{ $id }}" {{ request('course_id')==$id?'selected':'' }}>
                    {{ $course['title'] }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Status -->
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">All Status</option>
            <option value="paid" {{ request('status')=='paid'?'selected':'' }}>Paid</option>
            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
            <option value="failed" {{ request('status')=='failed'?'selected':'' }}>Failed</option>
        </select>
    </div>

    <div class="col-md-3">
        <button class="btn btn-green">Filter</button>
    </div>
</form>

        <!-- Transactions Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-success">
                    <tr>
                        <th>Date</th>
                        <th>Transaction ID</th>
                        <th>Child Name</th>
                        <th>Course</th>
                        <th>Amount Paid (RM)</th>
                        <th>Payment Method</th>
                        <th>Invoice</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $t)
                    <tr>
    <td>{{ \Carbon\Carbon::parse($t['created_at'])->format('d M Y') }}</td>
    <td>{{ $t['transaction_id'] ?? '-' }}</td>
    <td>{{ $t['child_name'] ?? '-' }}</td>
    <td>{{ $t['course_name'] ?? '-' }}</td>
    <td>RM {{ number_format($t['total_paid'] ?? 0, 2) }}</td>
    <td>{{ $t['payment_method'] ?? '-' }}</td>
    <td>
    @if(!empty($t['invoice']))
        <a href="{{ asset('storage/invoices/' . $t['invoice']) }}"
           class="btn btn-sm btn-success"
           target="_blank">
            Download
        </a>
    @else
        -
    @endif
</td>

    <td>
    @if($t['status'] === 'paid')
        <span class="badge bg-success">Paid</span>
    @elseif($t['status'] === 'pending')
        <span class="badge bg-warning">Pending</span>
    @else
        <span class="badge bg-danger">Failed</span>
    @endif
</td>
</tr>

                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No transactions found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $transactions->links() }}
        </div>

        <!-- Buttons -->
    <div class="d-flex justify-content-between mt-4">
        <a href="/parent_dashboard" class="btn btn-secondary">← Back to Dashboard</a>
    </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById("toggleSidebar").onclick = function () {
            document.getElementById("sidebar").classList.toggle("collapsed");
            document.getElementById("mainContent").classList.toggle("expanded");
        };
    </script>
</body>
</html>
