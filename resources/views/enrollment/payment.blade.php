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
            <input class="form-check-input" type="radio" name="payment_method" value="Online Banking" checked>
            <label class="form-check-label">Online Banking (FPX)</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="payment_method" value="Card">
            <label class="form-check-label">Debit / Credit Card</label>
        </div>
    </div>

    <!-- Bank Selection -->
    <div class="mb-3">
        <label class="form-label fw-bold">Select Bank</label>
        <select class="form-select" name="bank_name" required>
            <option value="">-- Choose Bank --</option>
            <option>Maybank</option>
            <option>CIMB</option>
            <option>Public Bank</option>
            <option>RHB</option>
            <option>Bank Islam</option>
        </select>
    </div>

    <!-- Buttons -->
    <div class="d-flex justify-content-between mt-4">
        <a href="/parent_dashboard" class="btn btn-secondary">← Cancel</a>

        <!-- Open modal ONLY -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmPaymentModal">
            Pay Now
        </button>
    </div>

    <!-- ✅ CONFIRMATION MODAL (INSIDE FORM) -->
    <div class="modal fade" id="confirmPaymentModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Confirm Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p>Are you sure you want to proceed with this payment?</p>
                    <ul class="mb-0">
                        <li><strong>Student:</strong> {{ $child->name }}</li>
                        <li><strong>Course:</strong> {{ $course->title }}</li>
                        <li><strong>Amount:</strong> RM {{ number_format($total, 2) }}</li>
                    </ul>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <!-- ✅ REAL SUBMIT -->
                    <button type="submit" class="btn btn-primary">
                        Yes, Pay Now
                    </button>
                </div>

            </div>
        </div>
    </div>
</form>

</div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>
