@extends('layouts.parent') {{-- adjust path if needed --}}

@section('title', 'Parent Dashboard')

@section('content')

@php
    $db = app('App\Services\FirebaseService')->db();
    $uid = session('uid');
    $parent = $db->getReference("users/$uid")->getValue();
    $profilePic = $parent['profile_picture'] ?? 'https://via.placeholder.com/40';
@endphp

    <h2 class="text-green mb-4">Edit Profile</h2>
<div class="card shadow-sm" style="max-width:600px;">
        <div class="card-body">
    <form method="POST" action="{{ route('parent.profile.update') }}" enctype="multipart/form-data">
        @csrf

        <!-- Profile Picture -->
        <div class="mb-3 text-center">
            <img src="{{ $parent['profile_picture'] ?? 'https://via.placeholder.com/120' }}"
                 class="rounded mb-2" width="120">
            <input type="file" name="profile_picture" class="form-control mt-2">
        </div>

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control"
                   value="{{ $parent['name'] ?? '' }}" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control"
                   value="{{ $parent['email'] ?? '' }}" required>
        </div>

        <div class="mb-3">
            <label>Phone Number</label>
            <input type="text" name="phone" class="form-control"
                   value="{{ $parent['phone'] ?? '' }}"
                   placeholder="e.g. 0123456789" required>
        </div>

        <div class="mb-3">
            <label>Date of Birth</label>
            <input type="date" name="dob" class="form-control"
                   value="{{ $parent['dob'] ?? '' }}" required>
        </div>

        <div class="mb-3">
            <label>Gender</label>
            <select name="gender" class="form-control" required>
                <option value="Male" {{ ($parent['gender'] ?? '')=='Male'?'selected':'' }}>Male</option>
                <option value="Female" {{ ($parent['gender'] ?? '')=='Female'?'selected':'' }}>Female</option>
            </select>
        </div>

        <div class="mt-4 d-flex gap-2">
            <a href="{{ route('parent.profile.view') }}" class="btn btn-secondary">Cancel</a>
            <button class="btn btn-green">Update Profile</button>
        </div>
    </form>
</div>

</div>
@endsection