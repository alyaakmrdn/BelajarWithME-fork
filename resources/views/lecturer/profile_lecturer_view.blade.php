@extends('layouts.lecturer') {{-- adjust path if needed --}}

@section('title', 'Lecturer Dashboard')

@section('content')

@php
    $db = app('App\Services\FirebaseService')->db();
    $uid = session('uid');
    $lecturer = $db->getReference("users/$uid")->getValue();
    $profilePicture = $lecturer['profile_picture'] ?? 'https://via.placeholder.com/40';
    $lecturerName   = $lecturer['name'] ?? 'Lecturer';
@endphp

    <div class="container-card">
        <div class="card shadow-sm">
            <div class="card-header text-white" style="background:#b3002d">
                <h4>Lecturer Profile</h4>
            </div>
            <div class="card-body">

                <div class="mb-3 text-center">
                    <label>Profile Picture</label><br>
                    <img src="{{ $lecturer['profile_picture'] ?? 'https://via.placeholder.com/120' }}"
                         class="rounded mb-2" width="120">
                </div>

                <div class="mb-3">
                    <label>Name:</label>
                    <p>{{ $lecturer['name'] ?? '' }}</p>
                </div>

                <div class="mb-3">
                    <label>Email:</label>
                    <p>{{ $lecturer['email'] ?? '' }}</p>
                </div>

                <div class="mb-3">
                    <label>Gender:</label>
                    <p>{{ $lecturer['gender'] ?? '' }}</p>
                </div>

                <div class="mb-3">
                    <label>Department:</label>
                    <p>{{ $lecturer['department'] ?? '' }}</p>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('lecturer.dashboard') }}" class="btn btn-secondary">Back</a>
                    <a href="{{ route('lecturer.profile.edit') }}" class="btn btn-cherry">Edit Profile</a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection