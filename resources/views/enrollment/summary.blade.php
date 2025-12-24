<!DOCTYPE html>
<html>
<head>
    <title>Enrollment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark" style="padding-top: 80px;">

<nav class="navbar navbar-expand-lg bg-white shadow-sm px-4 fixed-top">
    <button class="btn btn-outline-secondary me-3" id="toggleSidebar">☰</button>
    <a class="navbar-brand fw-bold text-green" href="#">Enrollment</a>
</nav>

<div class="container d-flex justify-content-center">
    <div class="card shadow" style="max-width: 500px; width: 100%;">
        <div class="card-body">
            <h3 class="text-center">Enrollment Summary</h3>
            <hr>

            <p><strong>Student Name:</strong> {{ $child->name }}</p>
            <p><strong>Course:</strong> {{ $course->name }}</p>
            <p><strong>Course Fee:</strong> RM {{ number_format($course->price, 2) }}</p>

            <hr>
            <h5 class="text-end">Total: RM {{ number_format($total, 2) }}</h5>

            <form method="POST" action="{{ route('enroll.payment') }}">
                @csrf

                <input type="hidden" name="course_id" value="{{ $course->id }}"> 
                <input type="hidden" name="child_id" value="{{ $child_id }}"> 
                <input type="hidden" name="total" value="{{ $total }}">
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">← Cancel</a>
                    <button class="btn btn-success">Proceed to Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>


</html>
