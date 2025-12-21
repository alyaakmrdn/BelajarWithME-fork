@extends('layouts.student')

@section('title', 'Student My Courses')

@push('styles')
<style>
    /* Card hover effect */
    .course-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 0.5rem;
    }
    .course-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    }

    /* Card header typography */
    .course-card h5 {
        font-weight: 700;
        color: #001f4d; /* navy */
    }

    .course-card p {
        color: #4a4a4a;
        margin-bottom: 1rem;
    }

    /* Buttons */
    .btn-navy {
        background-color: #001f4d;
        color: white;
        transition: all 0.2s ease;
    }
    .btn-navy:hover {
        background-color: #001033;
        color: white;
    }

    /* Empty state */
    .empty-state {
        border: 2px dashed #001f4d;
        border-radius: 0.5rem;
        padding: 3rem 1rem;
        text-align: center;
        background-color: #e6eaf0;
        color: #001f4d;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-navy fw-bold">My Courses</h2>
    </div>

    @if(count($enrolledCourses) === 0)
        <div class="empty-state my-4">
            <i class="bi bi-book"></i>
            <h4 class="mt-2">No courses enrolled yet</h4>
            <p>Enroll in a course to start learning and access materials.</p>
        </div>
    @else
        <div class="row g-4">
            @foreach($enrolledCourses as $course)
            <div class="col-md-6 col-xl-4">
                <div class="card shadow-sm h-100 course-card">
                    <div class="card-body d-flex flex-column">
                        <h5>{{ $course['title'] ?? 'Course' }}</h5>
                        <p>Class: {{ $course['class'] ?? '-' }}</p>
                        <a href="{{ route('courses.materials', $course['id']) }}" class="btn btn-navy mt-auto">
                            View Materials
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

@endsection
