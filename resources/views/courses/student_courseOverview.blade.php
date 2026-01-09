@extends('layouts.student')

@section('title', 'Student Dashboard')

@section('content')
<div class="container-fluid">
    <h2 class="text-navy mb-4">Course Overview</h2>

    <div class="row g-4">
        @foreach($courses as $courseId => $course)
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $course['title'] ?? 'Course' }}</h5>
                        <p class="card-text">
                            <strong>Class:</strong> {{ $course['class'] ?? '-' }} <br>
                            <strong>Price:</strong> ${{ $course['price'] ?? '-' }} <br>
                            <strong>Status:</strong> {{ $course['status'] ?? 'active' }}
                        </p>

                        <div class="mt-auto d-flex justify-content-between">
                            <a href="{{ $course['enrolled'] ? route('courses.materials', $courseId) : '#' }}"
                               class="btn btn-primary btn-sm {{ $course['enrolled'] ? '' : 'disabled' }}">
                                View Materials
                            </a>

                            <button class="btn btn-outline-danger btn-sm" type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#reportModal-{{ $courseId }}">
                                Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Report Modals -->
    @foreach($courses as $courseId => $course)
        <div class="modal fade" id="reportModal-{{ $courseId }}" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('student.report.submit') }}">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $courseId }}">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Report {{ $course['title'] ?? 'Course' }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <label>Reason</label>
                            <select name="reason" class="form-select" required>
                                <option value="inappropriate_content">Inappropriate Content</option>
                                <option value="incorrect_information">Incorrect Information</option>
                            </select>

                            <label class="mt-2">Description</label>
                            <textarea name="description" class="form-control" required></textarea>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-danger">Submit Report</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <!-- Success Modal -->
    @if (session('success'))
        <div class="modal fade" id="successModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Success</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        {{ session('success') }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
