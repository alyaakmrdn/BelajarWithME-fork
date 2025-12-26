<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center mt-5">

    <div class="card shadow" style="max-width: 500px; width: 100%;">
        <div class="card-body">

            <h3 class="text-center mb-3">Complete Payment</h3>
            <hr>

            <p><strong>Student Name:</strong> {{ $child->name }}</p>
            <p><strong>Course:</strong> {{ $course->title }}</p>
            <p class="fw-bold">Total Amount: RM {{ number_format($total, 2) }}</p>

            <hr>

            <form method="POST" action="{{ route('enroll.confirm') }}">
    @csrf

    <input type="hidden" name="course_id" value="{{ $course_id }}">
    <input type="hidden" name="child_id" value="{{ $child_id }}">
    <input type="hidden" name="total" value="{{ $total }}">

    <!-- Payment Method -->
    <div class="mb-3">
        <label class="form-label fw-bold">Select Payment Method</label>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="payment_method" id="onlineBanking" value="Online Banking" checked>
            <label class="form-check-label" for="onlineBanking">
                Online Banking (FPX)
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="payment_method" id="cardPayment" value="Card">
            <label class="form-check-label" for="cardPayment">
                Debit / Credit Card
            </label>
        </div>
    </div>

    <!-- Bank Selection -->
    <div class="mb-3">
        <label class="form-label fw-bold">Select Bank</label>
        <select class="form-select" name="bank_name" required>
            <option value="">-- Choose Bank --</option>
            <option value="Maybank">Maybank</option>
            <option value="CIMB">CIMB</option>
            <option value="Public Bank">Public Bank</option>
            <option value="RHB">RHB</option>
            <option value="RHB">Bank Islam</option>
        </select>
    </div>

    <!-- Buttons -->
    <div class="d-flex justify-content-between mt-4">
        <a href="/parent_dashboard" class="btn btn-secondary">← Cancel</a>
        <button class="btn btn-primary">Pay Now</button>
    </div>
</form>


        </div>
    </div>

</div>

</body>

</html>
