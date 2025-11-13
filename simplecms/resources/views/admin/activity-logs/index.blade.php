@extends('admin.layouts.app')

@section('title', 'Activity Log')
@section('page-title', 'Activity Log & Audit Trail')

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item active">Activity Log</div>
@endsection

@section('content')
    {{-- Statistics --}}
    <div class="row mb-4">
        <div class="col-lg-4">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary"><i class="fas fa-list"></i></div>
                <div class="card-wrap">
                    <div class="card-header"><h4>Total Activities</h4></div>
                    <div class="card-body">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success"><i class="fas fa-calendar-day"></i></div>
                <div class="card-wrap">
                    <div class="card-header"><h4>Today</h4></div>
                    <div class="card-body">{{ $stats['today'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning"><i class="fas fa-calendar-week"></i></div>
                <div class="card-wrap">
                    <div class="card-header"><h4>This Week</h4></div>
                    <div class="card-body">{{ $stats['this_week'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Activity Timeline</h4>
            <div class="card-header-form">
                <form method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Search..." value="{{ request('search') }}">
                    <select name="type" class="form-control me-2" onchange="this.form.submit()">
                        <option value="">All Types</option>
                        <option value="create" {{ request('type') === 'create' ? 'selected' : '' }}>Create</option>
                        <option value="update" {{ request('type') === 'update' ? 'selected' : '' }}>Update</option>
                        <option value="delete" {{ request('type') === 'delete' ? 'selected' : '' }}>Delete</option>
                        <option value="login" {{ request('type') === 'login' ? 'selected' : '' }}>Login</option>
                    </select>
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td><small>{{ $log->created_at->format('M d, Y H:i:s') }}</small></td>
                                <td>
                                    @if($log->user)
                                        {{ $log->user->name }}
                                    @else
                                        <span class="text-muted">System</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->log_type === 'create')
                                        <span class="badge badge-success">Create</span>
                                    @elseif($log->log_type === 'update')
                                        <span class="badge badge-info">Update</span>
                                    @elseif($log->log_type === 'delete')
                                        <span class="badge badge-danger">Delete</span>
                                    @elseif($log->log_type === 'login')
                                        <span class="badge badge-primary">Login</span>
                                    @else
                                        <span class="badge badge-secondary">{{ ucfirst($log->log_type) }}</span>
                                    @endif
                                </td>
                                <td>{{ $log->description }}</td>
                                <td><small>{{ $log->ip_address ?? 'N/A' }}</small></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-5">No activity logs found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($logs->hasPages())
            <div class="card-footer">{{ $logs->links() }}</div>
        @endif
    </div>
@endsection
