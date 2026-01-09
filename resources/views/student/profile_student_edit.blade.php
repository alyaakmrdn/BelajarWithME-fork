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
                <h4>Edit Profile</h4>
            </div>
            <div class="card-body">

                @if(session('success'))
                    <script>alert("{{ session('success') }}");</script>
                @endif

                <form method="POST" action="{{ route('student.profile.update') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Profile Picture -->
                    <div class="mb-3 text-center">
                        <img src="{{ $student['profile_picture'] ?? 'https://via.placeholder.com/120' }}"
                             class="rounded mb-2" width="120">
                        <input type="file" name="profile_picture" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $student['name'] ?? '' }}" required>
                    </div>

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $student['email'] ?? '' }}" required>
                    </div>

                    <div class="mb-3">
                        <label>Age</label>
                        <input type="number" name="age" class="form-control" value="{{ $student['age'] ?? '' }}" required>
                    </div>

                    <div class="mb-3">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" class="form-control" value="{{ $student['dob'] ?? '' }}" required>
                    </div>

                    <div class="mb-3">
                        <label>School Form</label>
                        <select name="school_form" class="form-control" required>
                            @foreach(['Form 1','Form 2','Form 3','Form 4','Form 5'] as $form)
                                <option value="{{ $form }}" {{ ($student['school_form'] ?? '') == $form ? 'selected' : '' }}>
                                    {{ $form }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Gender</label>
                        <select name="gender" class="form-control" required>
                            <option value="Male" {{ ($student['gender'] ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ ($student['gender'] ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <a href="{{ route('student.profile.view') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-navy">Save Changes</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection