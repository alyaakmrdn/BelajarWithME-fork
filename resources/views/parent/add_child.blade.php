@extends('layouts.parent')

@section('title', 'Parent AddChild')

@section('content')

        <h2 class="text-green mb-4">Add Child</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Validation Errors --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Manual Error Message --}}
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

        <form method="POST" action="{{ route('children.store') }}">
    @csrf
    <div class="mb-3">
        <label>Child Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Email (optional)</label>
        <input type="email" name="email" class="form-control">
    </div>
    <div class="mb-3">
        <label>Password (optional, leave blank to auto-generate)</label>
        <input type="text" name="password" class="form-control">
    </div>
    <div class="mb-3">
        <label>Academic Level</label>
        <input type="text" name="academic_level" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-green">Save Child</button>
    <a href="{{ route('parent.dashboard') }}" class="btn btn-secondary ms-2">Cancel</a>
</form>

@endsection