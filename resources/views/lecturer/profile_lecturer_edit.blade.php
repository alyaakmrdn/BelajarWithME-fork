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
                <h4>Edit Profile</h4>
            </div>
            <div class="card-body">

                {{-- SUCCESS ALERT --}}
                @if(session('success'))
                    <script>alert("{{ session('success') }}");</script>
                @endif

                <form method="POST" action="{{ route('lecturer.profile.update') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Profile Picture --}}
                    <div class="mb-3 text-center">
                        <label>Profile Picture</label><br>
                        <img src="{{ $lecturer['profile_picture'] ?? 'https://via.placeholder.com/120' }}"
                             class="rounded mb-2" width="120">
                        <input type="file" name="profile_picture" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control"
                               value="{{ $lecturer['name'] ?? '' }}" required>
                    </div>

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ $lecturer['email'] ?? '' }}" required>
                    </div>

                    <div class="mb-3">
                        <label>Gender</label>
                        <select name="gender" class="form-control" required>
                            <option value="Male" {{ ($lecturer['gender'] ?? '')=='Male'?'selected':'' }}>Male</option>
                            <option value="Female" {{ ($lecturer['gender'] ?? '')=='Female'?'selected':'' }}>Female</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Department</label>
                        <input type="text" name="department" class="form-control"
                               value="{{ $lecturer['department'] ?? '' }}" required>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <a href="/lecturer/profile_lecturer_view" class="btn btn-secondary">Cancel</a>
                        <button class="btn btn-cherry">Update Profile</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>

@endsection