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

    <h2 class="text-purple mb-4">My Profile</h2>

    <div class="card shadow-sm" style="max-width:600px;">
        <div class="card-body">

            <!-- PROFILE PICTURE -->
            <img src="{{ $admin['profile_picture'] ?? 'https://via.placeholder.com/150' }}"
                 class="rounded-circle mb-4"
                 style="width:150px;height:150px;object-fit:cover;">

            <p><strong>Name:</strong> {{ $admin['name'] ?? '-' }}</p>
            <p><strong>Email:</strong> {{ $admin['email'] ?? '-' }}</p>
            <p><strong>Phone Number:</strong> {{ $admin['phone'] ?? '-' }}</p>
            <p><strong>Date of Birth:</strong> {{ $admin['dob'] ?? '-' }}</p>
            <p><strong>Gender:</strong> {{ ucfirst($admin['gender'] ?? '-') }}</p>

            <div class="d-flex gap-2 mt-4">
                <a href="/admin_dashboard" class="btn btn-secondary">Back</a>
                <a href="/admin/profile_admin_edit" class="btn btn-purple">Edit Profile</a>
            </div>

        </div>
    </div>
</div>

@endsection