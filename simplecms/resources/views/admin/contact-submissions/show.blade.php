@extends('admin.layouts.app')

@section('title', 'View Contact Submission')
@section('page-title', 'Contact Submission Details')

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item"><a href="{{ route('admin.contact-submissions.index') }}">Contact Submissions</a></div>
    <div class="breadcrumb-item active">View</div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4>
                        <i class="fas fa-envelope-open-text"></i>
                        {{ $submission->subject }}
                    </h4>
                    <div class="card-header-action">
                        @if($submission->isUnread())
                            <span class="badge badge-warning">
                                <i class="fas fa-envelope"></i> Unread
                            </span>
                        @else
                            <span class="badge badge-success">
                                <i class="fas fa-envelope-open"></i> Read
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    {{-- Sender Information --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 1.5rem;">
                                {{ strtoupper(substr($submission->name, 0, 1)) }}
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-0">{{ $submission->name }}</h6>
                                <small class="text-muted">
                                    <i class="fas fa-envelope"></i>
                                    <a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a>
                                </small>
                            </div>
                        </div>
                        <div class="text-muted small">
                            <i class="fas fa-clock"></i>
                            Received on {{ $submission->created_at->format('F d, Y \a\t H:i') }}
                            ({{ $submission->created_at->diffForHumans() }})
                        </div>
                    </div>

                    <hr>

                    {{-- Message Content --}}
                    <div class="message-content">
                        <h6 class="mb-3">Message:</h6>
                        <div class="p-3 bg-light rounded">
                            {!! nl2br(e($submission->message)) !!}
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-4 d-flex gap-2">
                        <a href="mailto:{{ $submission->email }}?subject=Re: {{ $submission->subject }}"
                           class="btn btn-primary">
                            <i class="fas fa-reply"></i> Reply via Email
                        </a>

                        @if($submission->isUnread())
                            <form action="{{ route('admin.contact-submissions.mark-as-read', $submission->id) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check"></i> Mark as Read
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.contact-submissions.mark-as-unread', $submission->id) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-undo"></i> Mark as Unread
                                </button>
                            </form>
                        @endif

                        @can('contact.delete')
                            <button type="button"
                                    class="btn btn-danger"
                                    onclick="deleteSubmission()">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                            <form id="delete-form"
                                  action="{{ route('admin.contact-submissions.destroy', $submission->id) }}"
                                  method="POST"
                                  style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Submission Details Card --}}
            <div class="card">
                <div class="card-header">
                    <h4>Submission Details</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Submission ID</small>
                        <strong>#{{ $submission->id }}</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Status</small>
                        @if($submission->isUnread())
                            <span class="badge badge-warning">Unread</span>
                        @else
                            <span class="badge badge-success">Read</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Submitted</small>
                        <strong>{{ $submission->created_at->format('M d, Y H:i') }}</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">IP Address</small>
                        <code>{{ $submission->ip_address ?? 'N/A' }}</code>
                    </div>

                    @if($submission->user_agent)
                        <div class="mb-3">
                            <small class="text-muted d-block">User Agent</small>
                            <small class="text-break">{{ Str::limit($submission->user_agent, 100) }}</small>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Quick Actions Card --}}
            <div class="card">
                <div class="card-header">
                    <h4>Quick Actions</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.contact-submissions.index') }}"
                           class="btn btn-secondary btn-block">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                        <a href="{{ route('admin.contact-submissions.index', ['status' => 'unread']) }}"
                           class="btn btn-info btn-block">
                            <i class="fas fa-envelope"></i> View Unread ({{ \App\Models\ContactSubmission::unread()->count() }})
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
<script>
    function deleteSubmission() {
        if (confirm('Are you sure you want to delete this contact submission? This action cannot be undone.')) {
            document.getElementById('delete-form').submit();
        }
    }
</script>
@endpush

@push('custom-styles')
<style>
    .message-content {
        font-size: 1rem;
        line-height: 1.6;
    }
    .gap-2 {
        gap: 0.5rem;
    }
    .btn-block {
        width: 100%;
    }
</style>
@endpush
