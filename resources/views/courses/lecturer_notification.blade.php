@extends('layouts.lecturer') {{-- adjust path if needed --}}

@section('title', 'Lecturer Dashboard')

@section('content')

<div class="container-fluid">
        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h3 class="mb-1">Notifications</h3>
            <p class="text-muted mb-0">
              Messages and updates from administrators
            </p>
          </div>
        </div>

        {{-- Notification Table --}}
        <div class="card shadow-sm">
          <div class="card-body">

            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead class="table-light">
                  <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Received At</th>
                  </tr>
                </thead>

                <tbody>
                @forelse ($notifications as $index => $notification)
                  <tr class="{{ !$notification['read'] ? 'table-warning' : '' }}">
                    <td>{{ $index + 1 }}</td>

                    {{-- Title --}}
                    <td>
                      <strong>{{ $notification['title'] }}</strong>
                    </td>

                    {{-- Message --}}
                    <td style="max-width: 400px;">
                      <div class="text-truncate">
                        {{ $notification['message'] }}
                      </div>
                    </td>

                    {{-- Status --}}
                    <td>
                      @if($notification['read'])
                        <span class="badge bg-secondary">Read</span>
                      @else
                        <span class="badge bg-success">New</span>
                      @endif
                    </td>

                    {{-- Date --}}
                    <td>
                      {{ $notification['created_at'] }}
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                      No notifications received.
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
