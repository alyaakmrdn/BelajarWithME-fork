@extends('layouts.student') {{-- change to your actual layout path --}}

@section('title', 'Student Dashboard')

@section('content')

<div class="container-fluid">
        <h2 class="text-navy mb-4">Course Overview</h2>
        <div class="row g-4">
            @foreach($courses as $courseId => $course)
                @php
                    $isInactive = ($course['status'] ?? 'active') === 'inactive';
                @endphp
            <div class="col-md-4">
                <div class="card shadow-sm {{ $isInactive ? 'course-inactive' : '' }}">
                    <div class="card-body">
                        <h5 class="card-title d-flex justify-content-between align-items-center">
                        {{ $course['name'] }}

                        @if($isInactive)
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                        </h5>


                        <p class="card-text">
                            <!-- <strong>Course Code:</strong> {{ $courseId }} <br>
                            <strong>Lecturer:</strong> {{ $course['lecturer_email'] }} <br> -->
                            <strong>Status:</strong> {{ ucfirst($course['status']) }}
                        </p>

                        @if($isInactive)
                            <div class="alert alert-warning small mt-2">
                                This course has been deactivated by admin.
                            </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <button
                                class="btn btn-outline-danger btn-sm" type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#reportModal-{{ $courseId }}   
                                    {{ $isInactive ? 'disabled' : '' }}">
                                Report
                            </button>
                        </div>

                    </div>
                </div>
            </div>
            @endforeach
        </div>

    <!-- Modal -->
     @foreach($courses as $courseId => $course)
    <div class="modal fade" id="reportModal-{{ $courseId }}" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('student.report.submit') }}">
                @csrf

                <input type="hidden" name="course_id" value="{{ $courseId }}">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Report {{ $course['name'] }}</h5>
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
            <button type="button" class="btn btn-success" data-bs-dismiss="modal">
            OK
            </button>
        </div>
        </div>
    </div>
    </div>
    @endif
</div>

<script>
        document.querySelectorAll('[id^="reportModal-"]').forEach(function (modal) {
            modal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const course = button.getAttribute('data-course');

                const input = modal.querySelector('input[name="course_id"]');
                if (input && course) {
                    input.value = course;
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                var successModal = new bootstrap.Modal(
                    document.getElementById('successModal')
                );
                successModal.show();
            @endif
        }); 
    </script>

@endsection
