@extends('layouts.student') {{-- change to your actual layout path --}}

@section('title', 'Student Dashboard')

@section('content')

@php
    $db = app('App\Services\FirebaseService')->db();
    $uid = session('uid');
    $student = $db->getReference("users/$uid")->getValue();

    $profilePic = $student['profile_picture'] ?? $student['profile_pic'] ?? 'https://via.placeholder.com/40';
    $studentName = $student['name'] ?? 'Student';
@endphp

    <div class="profile-card">
        <div class="card shadow-sm">
            <div class="card-header text-white" style="background:#001f4d">
                <h4>My Profile</h4>
            </div>
            <div class="card-body">
                <!-- Profile Picture -->
                <img src="{{ $student['profile_picture'] ?? 'https://via.placeholder.com/150' }}" 
                     class="rounded-circle mb-3" style="width:150px; height:150px; object-fit:cover;">

                <p><strong>Name:</strong> {{ $student['name'] ?? '' }}</p>
                <p><strong>Email:</strong> {{ $student['email'] ?? '' }}</p>
                <p><strong>Phone:</strong> {{ $student['phone'] ?? '' }}</p>
                <p><strong>Date of Birth:</strong> {{ $student['dob'] ?? '' }}</p>
                <p><strong>Age:</strong> {{ $student['age'] ?? '' }}</p>
                <p><strong>School Form:</strong> {{ $student['school_form'] ?? '' }}</p>
                <p><strong>Gender:</strong> {{ ucfirst($student['gender'] ?? '') }}</p>

                <div class="mt-4">
                    <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">Back</a>
                    <a href="/student/profile_student_edit" class="btn btn-navy">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection