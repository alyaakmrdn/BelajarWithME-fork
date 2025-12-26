@extends('layouts.parent')

@section('title', 'My Children')

@push('styles')
<style>
    .avatar-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        font-size: 1.25rem;
    }

    .student-card {
        transition: transform .15s ease, box-shadow .15s ease;
    }

    .student-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.08);
    }

    .student-meta {
        font-size: .85rem;
        color: #6c757d;
    }
</style>
@endpush


@section('content')


    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-green fw-bold mb-0">My Children</h2>
            <p class="text-muted">Manage your children's profiles and academic status.</p>
        </div>
        <a href="{{ route('children.create') }}" class="btn btn-green shadow-sm px-4">
            <i class="bi bi-plus-lg me-2"></i>Add New Child
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        @forelse($children as $child)
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm h-100 student-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <!-- Avatar Placeholder -->
                            <div class="avatar-circle me-3 bg-success bg-opacity-10 text-green d-flex align-items-center justify-content-center">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">{{ $child->name }}</h5>
                                <span class="student-meta">
                                    {{ $child->academic_level }}
                                </span>
                            </div>

                            <!-- Divider -->
        <hr class="my-3">

        <!-- Info -->
        <div class="student-meta">
            <div>📘 Courses Enrolled: <strong>3</strong></div>
            <div>📊 Status: <span class="text-success fw-semibold">Active</span></div>
        </div>


                        </div>
                    </div>
                     <!-- Footer (lighter action) -->
    <div class="card-footer bg-transparent border-0 px-4 pb-4 pt-0">
        <a href="#" class="btn btn-outline-success w-100">
            View Profile
        </a>
    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-dashed py-5 text-center bg-transparent">
                    <div class="card-body">
                        <i class="bi bi-people text-muted display-1"></i>
                        <h4 class="mt-3 text-muted">No children registered yet</h4>
                        <p class="text-muted">Start by adding your first child to the system.</p>
                        <a href="{{ route('children.create') }}" class="btn btn-green px-4">Get Started</a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>



@endsection
