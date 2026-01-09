@extends('layouts.parent')

@section('title', 'Child Reports')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Child Reports</h3>
    <p class="text-muted mb-3">View reports submitted by your child</p>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Child</th>
                            <th>Course</th>
                            <th>Reason</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Admin Action</th>
                            <th>Submitted At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports as $index => $report)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $report['child_name'] }}</strong></td>

                            {{-- Course with dummy fallback --}}
                            <td>{{ $dummyCourses[$report['course_id']]['title'] ?? $report['course_name'] ?? $report['course_id'] }}</td>

                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ ucfirst(str_replace('_',' ', $report['reason'])) }}
                                </span>
                            </td>

                            <td style="max-width:300px;">
                                <div class="text-truncate">{{ $report['description'] }}</div>
                            </td>

                            <td>
                                @if($report['status'] === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-success">Resolved</span>
                                @endif
                            </td>

                            <td style="max-width:300px;">
                                @if($report['action'])
                                    <div><strong>{{ $report['action']['action'] }}</strong></div>
                                    <div class="text-muted small">{{ $report['action']['note'] }}</div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>{{ $report['created_at'] }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No reports found for your child.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
