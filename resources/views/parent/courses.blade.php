@extends('layouts.parent')

@section('title', 'Parent Courses List')

@push('styles')
<style>
    /* Card hover effect */
    .child-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 0.5rem;
    }
    .child-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    }

    /* Card header typography */
    .child-card h5 {
        font-weight: 700;
        color: #145214;
    }

    .child-card p {
        color: #4a4a4a;
        margin-bottom: 1rem;
    }

    /* Buttons */
    .btn-green {
        background-color: #228B22;
        color: white;
        transition: all 0.2s ease;
    }
    .btn-green:hover {
        background-color: #145214;
        color: white;
    }

    /* Empty state styling */
    .empty-state {
        border: 2px dashed #ccc;
        border-radius: 0.5rem;
        padding: 3rem 1rem;
        text-align: center;
        background-color: #f5fff5;
        color: #555;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
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
        <h2 class="text-green fw-bold">Children Courses</h2>
        
        <a href="/parent_dashboard" class="btn btn-secondary">
            ← Back
        </a>
    </div>
<p>Select your child and view their available courses</p>
    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(count($children) == 0)
        <div class="empty-state my-4">
            <i class="bi bi-people"></i>
            <h4 class="mt-2">No children registered yet</h4>
            <p>Start by adding your child to the system to view courses.</p>
            <a href="{{ route('children.create') }}" class="btn btn-green mt-2">Add Child</a>
        </div>
    @else
        <div class="row g-4">
            @foreach($children as $child)
            <div class="col-md-6 col-xl-4">
                <div class="card shadow-sm h-100 child-card">
                    <div class="card-body d-flex flex-column">
                        <h5>{{ $child->name }}</h5>
                        <p>Level: {{ $child->academic_level }}</p>
                        <a href="{{ route('courses.index', ['child_id' => $child->uid]) }}" class="btn btn-green mt-auto">
                            View Courses
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif


    <hr>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-green fw-bold">Enrolled Courses</h2>
    </div>

    <!-- Enhanced Filter Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Child</label>
                    <select name="child_id" class="form-select border-light-subtle shadow-none">
                        <option value="">All Children</option>
                        @foreach($childrenDropdown as $id => $name)
                            <option value="{{ $id }}" {{ request('child_id')==$id?'selected':'' }}>
        {{ $name }}
                        @endforeach
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
                        <th class="ps-4 py-3 border-0 text-uppercase fw-semibold">Enrolled Date</th>
                        <th class="py-3 border-0 text-uppercase small fw-bold ">Child</th>
                        <th class="py-3 border-0 text-uppercase small fw-bold ">Course</th>
                        <th class="py-3 border-0 text-uppercase small fw-bold text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($childTransactions as $t)
                    <tr>
                        <td class="ps-4">
                            <div class="text-muted">{{ \Carbon\Carbon::parse($t['created_at'])->format('d M Y') }}</div>
                            <!--<div class="text-muted x-small">#{{ $t['transaction_id'] ?? '-' }}</div>-->
                        </td>
                        <td>
                            <div class="fw-bold text-green">{{ $t['child_name'] ?? '-' }}</div>
                        </td>
                        <td>
                            <div class="text-muted"">{{ $t['course_name'] ?? '-' }}</div>
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

@endsection
