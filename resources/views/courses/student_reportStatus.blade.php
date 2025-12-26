@extends('layouts.student') {{-- change to your actual layout path --}}

@section('title', 'Student Dashboard')

@section('content')

<div class="container-fluid">

      <h3 class="mb-4">Report Status</h3>
      <p class="text-muted">Track the status of reports you have submitted</p>
      
      <div class="card shadow-sm">
        <div class="card-body">

          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Course</th>
                  <th>Reason</th>
                  <th>Description</th>
                  <th>Status</th>
                  <th>Admin Action</th>
                  <th>Submitted At</th>
                  <th>Last Update</th>
                </tr>
              </thead>

              <tbody>
              @forelse ($reports as $index => $report)
                <tr>
                  <td>{{ $index + 1 }}</td>

                  {{-- Course --}}
                  <td>
                    <strong>{{ $report['course_name'] }}</strong>
                    <div class="text-muted small">
                      {{ $report['course_id'] }}
                    </div>
                  </td>

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
                      <div>
                        <strong>{{ $report['action']['action'] }}</strong>
                      </div>
                      <div class="text-muted small">
                        {{ $report['action']['note'] }}
                      </div>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>

                  {{-- Created --}}
                  <td>
                    {{ $report['created_at'] }}
                  </td>

                  {{-- Updated --}}
                  <td>
                    {{ $report['updated_at'] }}
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center text-muted py-4">
                    You have not submitted any reports yet.
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
