@extends('layouts.admin') {{-- adjust path if needed --}}

@section('title', 'Admin Dashboard')

@section('content')

<div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h3 class="mb-1">Manage Reports</h3>
            <p class="text-muted mb-0">
              Review and manage reports submitted by users
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
                    <th>Course</th>
                    <th>Reason</th>
                    <th>Description</th>
                    <th>Reported By</th>
                    <th>Status</th>
                    <th>Reported At</th>
                    <th>Action</th>
                  </tr>
                </thead>

                <tbody>
                @forelse ($reports as $index => $report)
                  <tr>
                    {{-- Index --}}
                    <td>{{ $index + 1 }}</td>

                    {{-- Course --}}
                    <td>
    <strong>{{ $dummyCourses[$report['course_id']]['title'] ?? $report['course_name'] ?? $report['course_id'] }}</strong>
    <div class="text-muted small">{{ $report['course_id'] }}</div>
</td>


                    {{-- Reason --}}
                    <td>
                      <span class="badge bg-info text-dark">
                        {{ ucfirst(str_replace('_', ' ', $report['reason'])) }}
                      </span>
                    </td>

                    {{-- Description --}}
                    <td style="max-width: 280px;">
                      <div class="text-truncate">
                        {{ $report['description'] }}
                      </div>
                    </td>

                    {{-- Reporter --}}
                    <td>
                      <span class="badge bg-secondary">
                        {{ ucfirst($report['reporter_role']) }}
                      </span>
                    </td>

                    {{-- Status --}}
                    <td>
                      @if ($report['status'] === 'pending')
                        <span class="badge bg-warning text-dark">Pending</span>
                      @elseif ($report['status'] === 'reviewed')
                        <span class="badge bg-primary">Reviewed</span>
                      @elseif ($report['status'] === 'resolved')
                        <span class="badge bg-success">Resolved</span>
                      @else
                        <span class="badge bg-secondary">
                          {{ ucfirst($report['status']) }}
                        </span>
                      @endif
                    </td>

                    {{-- Date --}}
                    <td>
                      {{ $report['created_at'] }}
                    </td>

                    {{-- Actions --}}
                    <td>
                      <div class="d-flex gap-2">

                        {{-- Review Button --}}
                        <button
                          class="btn btn-sm btn-outline-primary"
                          data-bs-toggle="modal"
                          data-bs-target="#reviewModal-{{ $report['id'] }}"
                          @if(empty($report['action'])) disabled @endif
                        >
                          Review
                        </button>

                        {{-- Resolve Button --}}
                        <button
                          class="btn btn-sm btn-outline-success"
                          data-bs-toggle="modal"
                          data-bs-target="#resolveModal-{{ $report['id'] }}"
                          @if($report['status'] !== 'pending') disabled @endif
                        >
                          Resolve
                        </button>

                      </div>
                    </td>
                  </tr>

                  <div class="modal fade" id="resolveModal-{{ $report['id'] }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                      <div class="modal-content">

                        <form method="POST" action="{{ route('admin.resolve.report') }}">
                          @csrf

                          <input type="hidden" name="report_id" value="{{ $report['id'] }}">
                          <input type="hidden" name="course_id" value="{{ $report['course_id'] }}">
                          <input type="hidden" name="lecturer_id" value="{{ $report['lecturer_id'] }}">

                          <div class="modal-header">
                            <h5 class="modal-title">Resolve Report</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>

                          <div class="modal-body">

                            {{-- Course --}}
                            <p><strong>Course:</strong> {{ $report['course_name'] }}</p>

                            {{-- Resolution Type --}}
                            <div class="mb-3">
                              <label class="form-label">Resolution Decision</label>
                              <select name="resolution_type" class="form-select" required>
                                <option value="">-- Select Action --</option>
                                <option value="deactivate">Deactivate Course</option>
                                <option value="no_issue">Resolve – No Issue Found</option>
                              </select>
                            </div>

                            {{-- Action Note --}}
                            <div class="mb-3">
                              <label class="form-label">Action Note (Visible to Student)</label>
                              <textarea
                                name="action_note"
                                class="form-control"
                                rows="3"
                                required
                                placeholder="Explain the resolution decision..."
                              ></textarea>
                            </div>

                            <hr>

                            {{-- Notification --}}
                            <h6 class="mb-2">Notify Lecturer (Optional)</h6>

                            <div class="mb-3">
                              <label class="form-label">Message Title</label>
                              <input
                                type="text"
                                name="message_title"
                                class="form-control"
                                placeholder="e.g. Course Status Update"
                              >
                            </div>

                            <div class="mb-3">
                              <label class="form-label">Message Content</label>
                              <textarea
                                name="message_body"
                                class="form-control"
                                rows="3"
                                placeholder="Write message to lecturer..."
                              ></textarea>
                            </div>

                            <div class="alert alert-info small">
                              If message title and content are provided, a notification will be sent to the lecturer.
                            </div>

                          </div>

                          <div class="modal-footer">
                            <button type="submit" class="btn btn-success">
                              Confirm Resolution
                            </button>
                          </div>

                        </form>

                      </div>
                    </div>
                  </div>

                  <div class="modal fade" id="reviewModal-{{ $report['id'] }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">

                        <div class="modal-header">
                          <h5 class="modal-title">Admin Action</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                          <p><strong>Action:</strong> {{ $report['action']['action'] ?? '-' }}</p>
                          <p><strong>Admin Note:</strong></p>
                          <p class="border p-2 rounded bg-light">
                            {{ $report['action']['note'] ?? 'No action recorded.' }}
                          </p>
                          <p class="text-muted small">
                            {{ $report['action']['created_at'] ?? '' }}
                          </p>
                        </div>

                      </div>
                    </div>
                  </div>
                @empty
                  <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                      No reports available.
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
