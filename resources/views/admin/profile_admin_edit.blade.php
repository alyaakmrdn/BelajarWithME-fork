@extends('layouts.admin') {{-- adjust path if needed --}}

@section('title', 'Admin Dashboard')

@section('content')

@php
    $db = app('App\Services\FirebaseService')->db();
    $uid = session('uid');
    $admin = $db->getReference("users/$uid")->getValue();
    $profilePic = $admin['profile_picture'] ?? 'https://via.placeholder.com/40';
    $adminName = $admin['name'] ?? 'Admin';
@endphp

    <h2 class="text-purple mb-4">Edit Profile</h2>

    <div class="card shadow-sm" style="max-width:600px;">
        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                @csrf

                <!-- PROFILE PICTURE -->
                <div class="mb-3 text-center">
                    <img src="{{ $admin['profile_picture'] ?? 'https://via.placeholder.com/150' }}"
                         class="rounded-circle mb-3"
                         style="width:150px;height:150px;object-fit:cover;">
                    <input type="file" name="profile_picture" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ $admin['name'] ?? '' }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ $admin['email'] ?? '' }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control"
                           value="{{ $admin['phone'] ?? '' }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="dob" class="form-control"
                           value="{{ $admin['dob'] ?? '' }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-control" required>
                        <option value="male" {{ ($admin['gender'] ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ ($admin['gender'] ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('admin.profile.view') }}" class="btn btn-secondary">Cancel</a>
                    <button class="btn btn-purple">Update Profile</button>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection