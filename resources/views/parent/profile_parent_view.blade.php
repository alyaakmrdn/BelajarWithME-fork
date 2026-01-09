@extends('layouts.parent') {{-- adjust path if needed --}}

@section('title', 'Parent Dashboard')

@section('content')

@php
    $db = app('App\Services\FirebaseService')->db();
    $uid = session('uid');
    $parent = $db->getReference("users/$uid")->getValue();
    $profilePic = $parent['profile_picture'] ?? 'https://via.placeholder.com/40';
@endphp

    <h2 class="text-green mb-4">My Profile</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm" style="max-width:600px;">
        <div class="card-body">

    <div class="text-center mb-3">
        <img src="{{ $parent['profile_picture'] ?? 'https://via.placeholder.com/120' }}"
             class="rounded" width="120">
    </div>

    <p><strong>Name:</strong> {{ $parent['name'] ?? '' }}</p>
    <p><strong>Email:</strong> {{ $parent['email'] ?? '' }}</p>
    <p><strong>Phone Number:</strong> {{ $parent['phone'] ?? '-' }}</p>
    <p><strong>Date of Birth:</strong> {{ $parent['dob'] ?? '' }}</p>
    <p><strong>Gender:</strong> {{ $parent['gender'] ?? '' }}</p>

    <div class="mt-4 d-flex gap-2">
        <a href="{{ route('parent.dashboard') }}" class="btn btn-secondary">Back</a>
        <a href="/parent/profile/edit" class="btn btn-green">Edit Profile</a>
    </div>
</div>

</div>
@endsection