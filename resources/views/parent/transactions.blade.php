@extends('layouts.parent')

@section('title', 'Parent Transaction')

@push('styles')
<style>
    .x-small { font-size: 0.75rem; }

    .bg-success-subtle { background-color: #d1e7dd; }
    .bg-warning-subtle { background-color: #fff3cd; }
    .bg-danger-subtle  { background-color: #f8d7da; }

    .btn-white {
        background-color: #fff;
        color: #333;
    }
    .btn-white:hover {
        background-color: #f1f5f1;
    }

    /* Table */
    .table tbody tr {
        border-bottom: 1px solid #e5f2e5;
    }

    .table-hover tbody tr:hover {
        background-color: #f3fbf3 !important;
    }

    /* Header */
    .hdr-grn th {
        background-color: #1e7a1e;
        color: white;
        font-weight: 600;
        letter-spacing: 0.04em;
    }
</style>
@endpush

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-green fw-bold mb-0">Transaction History</h2>
            <p class="text-muted small">View and download your tuition payment records</p>
        </div>
        <a href="/parent_dashboard" class="btn btn-secondary">
            ← Back
        </a>
    </div>
<div class="px-4 py-2 bg-light border-bottom small text-muted">
    Showing {{ $transactions->count() }} transactions
</div>

    <!-- Enhanced Filter Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Child</label>
                    <select name="child_id" class="form-select border-light-subtle shadow-none">
                        <option value="">All Children</option>
                        @foreach($children as $id => $name)
                            <option value="{{ $id }}" {{ request('child_id')==$id?'selected':'' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Course</label>
                    <select name="course_id" class="form-select border-light-subtle shadow-none">
                        <option value="">All Courses</option>
                        @foreach($dummyCourses as $id => $course)
                            <option value="{{ $id }}" {{ request('course_id')==$id?'selected':'' }}>{{ $course['title'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Status</label>
                    <select name="status" class="form-select border-light-subtle shadow-none">
                        <option value="">All Status</option>
                        <option value="paid" {{ request('status')=='paid'?'selected':'' }}>Paid</option>
                        <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                        <option value="failed" {{ request('status')=='failed'?'selected':'' }}>Failed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button class="btn btn-green w-100 fw-bold">Filter</button>
                        <a href="{{ request()->url() }}" class="btn btn-light border w-100">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="hdr-grn">
                    <tr>
                        <th class="ps-4 py-3 border-0 text-uppercase fw-semibold">Date</th>
                        <th class="py-3 border-0 text-uppercase small fw-bold ">Child & Course</th>
                        <th class="py-3 border-0 text-uppercase small fw-bold ">Amount</th>
                        <th class="py-3 border-0 text-uppercase small fw-bold text-center">Status</th>
                        <th class="pe-4 py-3 border-0 text-uppercase small fw-bold text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $t)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold">{{ \Carbon\Carbon::parse($t['created_at'])->format('d M Y') }}</div>
                            <!--<div class="text-muted x-small">#{{ $t['transaction_id'] ?? '-' }}</div>-->
                        </td>
                        <td>
                            <div class="fw-bold text-green">{{ $t['child_name'] ?? '-' }}</div>
                            <div class="text-muted small">{{ $t['course_name'] ?? '-' }}</div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">
    RM {{ number_format($t['total_paid'] ?? 0, 2) }}
</div>
<div class="text-muted x-small">Tuition Fee</div>

                            <!--<div class="text-muted x-small">{{ $t['payment_method'] ?? 'Online' }}</div>-->
                        </td>
                        <td class="text-center">
                            @if($t['status'] === 'paid')
                                <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2 fw-semibold">Paid</span>
                            @elseif($t['status'] === 'pending')
                                <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis px-3 py-2 fw-semibold">Pending</span>
                            @else
                                <span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-2 fw-semibold">Failed</span>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            @if(!empty($t['invoice']))
                                <a href="{{ asset('storage/invoices/' . $t['invoice']) }}"
                                   class="btn btn-sm btn-outline-success rounded-pill px-3"
                                   target="_blank">
                                    <i class="bi bi-download me-1"></i> Invoice
                                </a>
                            @else
                                <span class="text-muted small italic">No Invoice</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="bi bi-receipt text-muted display-4 d-block mb-3"></i>
                            <p class="text-muted">No transactions found for the selected filters.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $transactions->links('pagination::bootstrap-5') }}
    </div>

@endsection