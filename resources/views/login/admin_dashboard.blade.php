@extends('layouts.admin') {{-- adjust path if needed --}}

@section('title', 'Admin Dashboard')

@section('content')

<div class="container-fluid">

    <!-- Page Title -->
    <h2 class="fw-bold text-purple mb-4">System Overview</h2>

    <!-- STAT CARDS -->
    <div class="row g-4 mb-4">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h6 class="text-muted">Total Students</h6>
                    <h2 class="fw-bold text-primary">320</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h6 class="text-muted">Total Parents</h6>
                    <h2 class="fw-bold text-success">210</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h6 class="text-muted">Lecturers</h6>
                    <h2 class="fw-bold text-warning">25</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h6 class="text-muted">Active Courses</h6>
                    <h2 class="fw-bold text-danger">18</h2>
                </div>
            </div>
        </div>

    </div>

    <!-- SECOND ROW -->
    <div class="row g-4">

        <!-- TRANSACTIONS -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header btn-purple">
                    <h5 class="mb-0">Recent Transactions</h5>
                </div>

                <div class="card-body">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Parent</th>
                                <th>Student</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>10 Dec 2025</td>
                                <td>Mr. Ahmad</td>
                                <td>Aisyah</td>
                                <td>RM 150</td>
                                <td><span class="badge bg-success">Paid</span></td>
                            </tr>
                            <tr>
                                <td>09 Dec 2025</td>
                                <td>Mrs. Siti</td>
                                <td>Adam</td>
                                <td>RM 200</td>
                                <td><span class="badge bg-warning">Pending</span></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="text-end">
                        <a href="{{ route('admin.transactions') }}" class="btn btn-outline-secondary btn-sm">
                            View All Transactions
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- SYSTEM ALERTS -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header btn-purple">
                    <h5 class="mb-0">System Alerts</h5>
                </div>

                <div class="card-body">
                    <div class="mb-3 p-3 bg-light border-start border-danger border-4 rounded">
                        <strong>⚠ Pending Payments</strong>
                        <p class="mb-0 small">12 unpaid transactions.</p>
                    </div>

                    <div class="mb-3 p-3 bg-light border-start border-warning border-4 rounded">
                        <strong>👨‍🏫 Lecturer Assignment</strong>
                        <p class="mb-0 small">2 courses without lecturer.</p>
                    </div>

                    <div class="p-3 bg-light border-start border-success border-4 rounded">
                        <strong>✅ System Healthy</strong>
                        <p class="mb-0 small">No critical issues detected.</p>
                    </div>
                </div>
            </div>

            <!-- QUICK ACTIONS -->
            <div class="card border-0 shadow-sm">
                <div class="card-header btn-purple">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="#" class="btn btn-outline-primary">Add Course</a>
                    <a href="#" class="btn btn-outline-success">Add Lecturer</a>
                    <a href="#" class="btn btn-outline-danger">View Reports</a>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
