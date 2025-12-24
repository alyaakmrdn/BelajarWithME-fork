<!DOCTYPE html>
<html>
<head>
    <title>Payment Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; color: #333; }
        h2 { color: #228B22; margin-bottom: 5px; }
        .header, .footer { text-align: center; }
        .company { font-size: 18px; font-weight: bold; }
        .invoice-number { font-size: 14px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; text-align: left; }
        .total { font-weight: bold; }
        .note { margin-top: 20px; font-size: 14px; color: #555; }
    </style>
</head>
<body>

<div class="header">
    <h2>Payment Receipt</h2>
    <div class="company">BelajarWithME</div>
    <div class="invoice-number">
        Invoice #: {{ 'INV-' . time() }} <br>
        Date: {{ $date }}
    </div>
</div>

<hr>

<h4>Student / Payer Details</h4>
<p><strong>Student Name:</strong> {{ $child_name }}</p>
@if(isset($parent_name))
<p><strong>Parent Name:</strong> {{ $parent_name }}</p>
@endif

<h4>Course Details</h4>
<table>
    <tr>
        <th>Course Title</th>
        <th>Class / Level</th>
        <th>Lecturer</th>
        <th>Duration</th>
        <th>Course Fee (RM)</th>
    </tr>
    <tr>
        <td>{{ $course_name }}</td>
        <td>{{ $course->class ?? '-' }}</td>
        <td>{{ $course->lecturer ?? '-' }}</td>
        <td>{{ $course->duration ?? '-' }}</td>
        <td>{{ number_format($course->price, 2) }}</td>
    </tr>
</table>

<h4>Payment Details</h4>
<table>
    <tr>
        <th>Payment Method</th>
        <td>{{ $payment_method }}</td>
    </tr>
    <tr>
        <th>Bank Name</th>
        <td>{{ $bank_name ?? '-' }}</td>
    </tr>
    <tr>
        <th>Transaction ID</th>
        <td>{{ $transaction_id }}</td>
    </tr>
    <tr>
        <th>Total Paid (RM)</th>
        <td class="total">{{ number_format($total, 2) }}</td>
    </tr>
</table>

<div class="note">
    Thank you for enrolling! Keep this invoice for your records.
</div>

<div class="footer">
    <p>BelajarWithME &copy; {{ date('Y') }}</p>
</div>

</body>
</html>
