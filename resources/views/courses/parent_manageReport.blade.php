@extends('layouts.parent') {{-- adjust path if needed --}}

@section('title', 'Parent Dashboard')

@section('content')

<div class="container-fluid">
        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h3 class="mb-1">Child Reports</h3>
            <p class="text-muted mb-0">
              View reports submitted by your child
            </p>
          </div>
        </div>

        {{-- Reports Table --}}
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

                    {{-- Child --}}
                    <td>
                      <strong>{{ $report['child_name'] }}</strong>
                    </td>

                    {{-- Course --}}
                    <td>{{ $report['course_name'] }}</td>

                    {{-- Reason --}}
                    <td>
                      <span class="badge bg-info text-dark">
                        {{ ucfirst(str_replace('_', ' ', $report['reason'])) }}
                      </span>
                    </td>

                    {{-- Description --}}
                    <td style="max-width: 300px;">
                      <div class="text-truncate">
                        {{ $report['description'] }}
                      </div>
                    </td>

                    {{-- Status --}}
                    <td>
                      @if ($report['status'] === 'pending')
                        <span class="badge bg-warning">Pending</span>
                      @else
                        <span class="badge bg-success">Resolved</span>
                      @endif
                    </td>

                    {{-- Admin Action --}}
                    <td style="max-width:300px;">
                      @if ($report['status'] === 'resolved' && !empty($report['action']))
                        <div><strong>{{ $report['action']['action'] }}</strong></div>
                        <div class="text-muted small">
                          {{ $report['action']['note'] }}
                        </div>
                      @else
                        <span class="text-muted">-</span>
                      @endif
                    </td>

                    {{-- Date --}}
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
