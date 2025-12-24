@extends('layouts.admin')

@section('title', 'All Student Enrollments')

@push('styles')
<style>
    .table tbody tr {
        border-bottom: 1px solid #eee;
    }

    .table tbody tr:last-child {
        border-bottom: none;
    }

    .table-hover tbody tr:hover {
        background-color: #faf7ff;
    }

        .hdr-prpl th {
        background-color: #6a0dad;
        color: white;
        font-weight: 600;
        letter-spacing: 0.05em;
    }
</style>
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-purple m-0">Student Enrollments</h2>
</div>

<!-- Filter -->
<div class="card shadow-sm mb-4 border-0">
    <div class="card-body">
        <form method="GET" class="row g-2">
    <div class="col-md-4">
        <label class="form-label fw-bold small">Student</label>
        <select name="child_id" class="form-select">
            <option value="">All Students</option>
            @foreach($studentsDropdown as $id => $name)
                <option value="{{ $id }}" {{ request('child_id')==$id?'selected':'' }}>
                    {{ $name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-bold small">Course</label>
        <select name="course_id" class="form-select">
            <option value="">All Courses</option>
            @foreach($coursesDropdown as $id => $title)
                <option value="{{ $id }}" {{ request('course_id')==$id?'selected':'' }}>
                    {{ $title }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3 d-flex gap-2 align-items-end">
        <button class="btn btn-primary w-100">Filter</button>
        <a href="{{ request()->url() }}" class="btn btn-light border w-100">Reset</a>
    </div>
</form>

    </div>
</div>

<!-- Enrollment Table -->
<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="hdr-prpl">
                <tr>
                    <th>Enrolled Date</th>
                    <th>Student</th>
                    <th>Course</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $r)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($r['created_at'])->format('d M Y') }}</td>
                    <td class="fw-bold">{{ $r['student_name'] }}</td>
                    <td>{{ $r['course_name'] }}</td>
                    <td>
                        <span class="badge bg-success">{{ ucfirst($r['status']) }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-5 text-muted">
                        <i class="bi bi-people display-4 mb-2"></i>
                        <div>No enrollments yet</div>
                        <small>This student has not enrolled in any courses yet.</small>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


@endsection
