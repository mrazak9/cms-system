@extends('admin.layouts.app')

@section('title', 'Contact Submissions')
@section('page-title', 'Contact Submissions')

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item active">Contact Submissions</div>
@endsection

@section('content')
    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total Submissions</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['total'] }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="fas fa-envelope-open"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Unread</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['unread'] }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-check"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Read</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['read'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Card --}}
    <div class="card">
        <div class="card-header">
            <h4>All Contact Submissions</h4>
            <div class="card-header-form">
                <form method="GET" action="{{ route('admin.contact-submissions.index') }}" class="d-flex">
                    <div class="input-group me-2">
                        <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <select name="status" class="form-control" onchange="this.form.submit()" style="width: auto;">
                        <option value="">All Status</option>
                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
                    </select>
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $submission)
                            <tr class="{{ $submission->isUnread() ? 'font-weight-bold' : '' }}">
                                <td>
                                    @if($submission->isUnread())
                                        <span class="badge badge-warning">
                                            <i class="fas fa-envelope"></i> Unread
                                        </span>
                                    @else
                                        <span class="badge badge-success">
                                            <i class="fas fa-envelope-open"></i> Read
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $submission->name }}</strong>
                                </td>
                                <td>
                                    <a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a>
                                </td>
                                <td>
                                    {{ Str::limit($submission->subject, 40) }}
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $submission->created_at->format('M d, Y H:i') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        @can('contact.view')
                                            <a href="{{ route('admin.contact-submissions.show', $submission->id) }}"
                                               class="btn btn-sm btn-primary"
                                               title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endcan

                                        @if($submission->isUnread())
                                            <a href="{{ route('admin.contact-submissions.mark-as-read', $submission->id) }}"
                                               class="btn btn-sm btn-success"
                                               title="Mark as Read"
                                               onclick="event.preventDefault(); document.getElementById('mark-read-form-{{ $submission->id }}').submit();">
                                                <i class="fas fa-check"></i>
                                            </a>
                                            <form id="mark-read-form-{{ $submission->id }}"
                                                  action="{{ route('admin.contact-submissions.mark-as-read', $submission->id) }}"
                                                  method="POST"
                                                  style="display: none;">
                                                @csrf
                                                @method('PATCH')
                                            </form>
                                        @else
                                            <a href="{{ route('admin.contact-submissions.mark-as-unread', $submission->id) }}"
                                               class="btn btn-sm btn-warning"
                                               title="Mark as Unread"
                                               onclick="event.preventDefault(); document.getElementById('mark-unread-form-{{ $submission->id }}').submit();">
                                                <i class="fas fa-undo"></i>
                                            </a>
                                            <form id="mark-unread-form-{{ $submission->id }}"
                                                  action="{{ route('admin.contact-submissions.mark-as-unread', $submission->id) }}"
                                                  method="POST"
                                                  style="display: none;">
                                                @csrf
                                                @method('PATCH')
                                            </form>
                                        @endif

                                        @can('contact.delete')
                                            <button type="button"
                                                    class="btn btn-sm btn-danger"
                                                    title="Delete"
                                                    onclick="deleteSubmission({{ $submission->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $submission->id }}"
                                                  action="{{ route('admin.contact-submissions.destroy', $submission->id) }}"
                                                  method="POST"
                                                  style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fas fa-envelope fa-3x mb-3"></i>
                                    <p>No contact submissions yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($submissions->hasPages())
            <div class="card-footer text-right">
                {{ $submissions->links() }}
            </div>
        @endif
    </div>
@endsection

@push('custom-scripts')
<script>
    function deleteSubmission(id) {
        if (confirm('Are you sure you want to delete this contact submission? This action cannot be undone.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
