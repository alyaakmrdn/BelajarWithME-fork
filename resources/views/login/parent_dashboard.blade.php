@extends('layouts.parent') {{-- adjust path if needed --}}

@section('title', 'Parent Dashboard')

@section('content')

<div class="container-fluid">

    <!-- Page Title -->
    <h2 class="fw-bold text-green mb-4">Welcome, Parent 👋</h2>

    <!-- SUMMARY CARDS -->
    <div class="row g-4 mb-4">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h6 class="text-muted">Total Children</h6>
                    <h2 class="fw-bold text-green">{{ $childrenCount }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h6 class="text-muted">Active Courses</h6>
                    <h2 class="fw-bold text-primary">5</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h6 class="text-muted">Pending Fees</h6>
                    <h2 class="fw-bold text-danger">RM 150</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h6 class="text-muted">Upcoming Classes</h6>
                    <h2 class="fw-bold text-warning">3</h2>
                </div>
            </div>
        </div>

    </div>

    <!-- MAIN CONTENT ROW -->
    <div class="row g-4">

        <!-- CHILDREN PERFORMANCE -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header btn-green">
                    <h5 class="mb-0">Children Performance Overview</h5>
                </div>

                <div class="card-body">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Child Name</th>
                                <th>Course</th>
                                <th>Average Grade</th>
                                <th>Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Ahmad</td>
                                <td>Mathematics</td>
                                <td>88%</td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" style="width: 88%"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Aisyah</td>
                                <td>Science</td>
                                <td>75%</td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar bg-warning" style="width: 75%"></div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ANNOUNCEMENTS -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header btn-green">
                    <h5 class="mb-0">Announcements</h5>
                </div>

                <div class="card-body">

                @if(session('invoice'))
    <div class="alert alert-info alert-dismissible d-flex align-items-center shadow-sm border-0" role="alert">
        <i class="bi bi-file-earmark-pdf-fill fs-4 me-3"></i>
        <div class="flex-grow-1">
            <strong>Payment Successful!</strong> Your latest receipt is ready.
            <a href="{{ asset('storage/invoices/' . session('invoice')) }}" class="btn btn-sm btn-success ms-3" target="_blank">
                Download Receipt
            </a>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


                    <div class="mb-3 p-3 bg-light border-start border-success border-4 rounded">
                        <strong>📢 Exam Week</strong>
                        <p class="mb-0 small">Final exams begin next Monday.</p>
                    </div>

                    <div class="mb-3 p-3 bg-light border-start border-warning border-4 rounded">
                        <strong>⚠ Payment Reminder</strong>
                        <p class="mb-0 small">Outstanding tuition fee due this week.</p>
                    </div>

                    <div class="p-3 bg-light border-start border-primary border-4 rounded">
                        <strong>📅 Class Update</strong>
                        <p class="mb-0 small">Science class rescheduled to Friday.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
