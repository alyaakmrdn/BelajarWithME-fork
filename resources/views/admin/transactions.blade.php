@extends('layouts.admin')

@section('title', 'Admin Transactions')

@push('styles')
<style>
    .table tbody tr {
        border-bottom: 1px solid #eee;
    }

    .table tbody tr:last-child {
        border-bottom: none;
    }

    .table-hover tbody tr:hover {
        background-color: #faf7ff;
    }

        .hdr-prpl th {
        background-color: #6a0dad;
        color: white;
        font-weight: 600;
        letter-spacing: 0.05em;
    }
</style>
@endpush

@section('content')
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-purple m-0">All Transactions</h2>
        <span class="badge bg-light text-dark border">{{ count($transactions) }} Total</span>
    </div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="hdr-prpl">
                <tr>
                    <th class="ps-4 text-uppercase small">Date</th>
                    <th class="text-uppercase small">Student</th>
                    <th class="text-uppercase small">Course</th>
                    <th class="text-uppercase small">Amount</th>
                    <th class="text-uppercase small">Status</th>
                    <th class="text-end pe-4 text-uppercase small">Invoice</th>

                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $tx)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold">{{ \Carbon\Carbon::parse($tx['created_at'])->format('d M Y') }}</div>
                            <!--<div class="text-muted x-small">#{{ $t['transaction_id'] ?? '-' }}</div>-->
                        </td>
                        <td>
                            <div class="text-muted">{{ $tx['child_name'] ?? 'Unknown Student' }}</div>
                        </td>
                        <td>
                            <span class="text-muted">{{ $tx['course_name'] ?? '-' }}</span>
                        </td>
                        <td>
    <div class="fw-bold text-dark">
        RM {{ number_format($tx['total_paid'] ?? 0, 2) }}
    </div>
    <div class="small text-muted">Tuition Fee</div>
</td>
                        <td>
                            @if($tx['status'] === 'paid')
                                <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">Paid</span>
                            @elseif($tx['status'] === 'pending')
                                <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis px-3 py-2">Pending</span>
                            @else
                                <span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-2">Failed</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            @if(isset($tx['invoice']))
                                <a href="{{ asset('storage/invoices/' . $tx['invoice']) }}"
   class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center"
   target="_blank">
    ⬇ Invoice
</a>

                            @else
                                <span class="text-muted small">Not Available</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            No transactions found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection