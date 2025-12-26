@extends('layouts.parent')

@section('title', 'Parent Available Courses')

@section('content')

    <h2 class="text-green mb-4">Available Courses</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-3">
        @foreach($courses as $course)

<div class="col-md-4 mb-3">
    <div class="card shadow-sm h-100">
        <div class="card-body">
        <h5>{{ $course['title'] }}</h5>
        <p class="text-muted mb-2">{{ $course['subtitle'] }}</p>
        <p class="mb-1"><strong>Class:</strong> {{ $course['class'] }}</p>
        <p class="mb-1"><strong>Lecturer:</strong> {{ $course['lecturer'] }}</p>
        <p class="mb-1"><strong>Duration:</strong> {{ $course['duration'] }}</p>
        <hr>
        <p class="text-primary">Price: RM {{ $course['price'] }}</p>

        @if(in_array($course['id'], $paidCourses))
            <button class="btn btn-secondary" disabled>
                Already Enrolled
            </button>
        @else
            <form method="POST" action="{{ route('enroll.summary') }}">
                @csrf
                <input type="hidden" name="course_id" value="{{ $course['id'] }}">
                <input type="hidden" name="child_id" value="{{ $child_id }}">
                <button class="btn btn-success">
                    Enroll Now
                </button>
            </form>
        @endif
        </div>
    </div>
</div>

@endforeach

<!-- Back Button -->
    <div class="mt-4">
        <a href="parent/courses" class="btn btn-secondary">← Back</a>
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

