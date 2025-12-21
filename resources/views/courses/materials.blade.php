@extends('layouts.student')

@section('title', 'Student Course Materials')

@section('content')

 @php
    $isPaid = true; // already verified in controller
@endphp

    <!-- Course Header -->
    <div class="card shadow-sm mb-4 position-relative">
        <div class="card-body">
            <h3 class="text-green mb-1">{{ $course['title'] }}</h3>
            <p class="mb-0 text-muted">
    Lecturer: {{ $course['lecturer'] }} | Price: RM {{ number_format($course['price'], 2) }}
</p>


            @if($isPaid)
                <span class="badge bg-success position-absolute" style="top:10px; right:10px;">
                    Paid ✓
                </span>
            @else
                <span class="badge bg-warning text-dark position-absolute" style="top:10px; right:10px;">
                    Not Paid
                </span>
            @endif
        </div>
    </div>

    <!-- Materials List -->
    <div class="card shadow-sm">
        <div class="card-header hdr-navy">
            Course Materials
        </div>
        <ul class="list-group list-group-flush">
            @foreach($course['materials'] as $material)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-secondary me-2">{{ $material['type'] }}</span>
                        {{ $material['title'] }}
                    </div>

                    @if(isset($material['url']))
                        @if($isPaid)
                            <a href="{{ $material['url'] }}" class="btn btn-sm btn-outline-success">Open</a>
                        @else
                            <button class="btn btn-sm btn-outline-secondary" disabled data-bs-toggle="tooltip" title="Pay to unlock">
                                Locked
                            </button>
                        @endif
                    @else
                        <span class="text-muted">Unavailable</span>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

    <hr class="my-4">

        <h2 class="text-navy">Submit Assignment</h2>

        <div class="card shadow-sm p-4" style="max-width: 600px;">
            <form>

                <label class="fw-bold">Select Course</label>
                <select class="form-select mb-3">
                    <option>Mathematics</option>
                    <option>Science</option>
                    <option>English</option>
                </select>

                <label class="fw-bold">Assignment Title</label>
                <input type="text" class="form-control mb-3" placeholder="Assignment title">

                <label class="fw-bold">Upload File / Link</label>
                <input type="file" class="form-control mb-3">

                <button class="btn btn-navy">Submit</button>

            </form>
        </div>

    <!-- Back Button -->
    <div class="mt-4">
        <a href="/student_dashboard" class="btn btn-secondary">← Dashboard</a>
    </div>

@stack('scripts')

<!-- Tooltip & Sidebar Toggle -->
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>

@endsection
