@extends('admin.layouts.app')

@section('title', 'Security Dashboard')
@section('page-title', 'Security Dashboard')

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item active">Security</div>
@endsection

@section('content')
    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Failed Attempts Today</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['total_today'] }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Failed This Week</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['total_week'] }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-info">
                    <i class="fas fa-network-wired"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Unique IPs Today</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['unique_ips_today'] }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Locked Accounts</h4>
                    </div>
                    <div class="card-body">
                        {{ $lockedAccounts->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Locked Accounts -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-lock me-2"></i>Currently Locked Accounts</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.security.locked-accounts') }}" class="btn btn-primary">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($lockedAccounts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Email</th>
                                        <th>Locked Until</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lockedAccounts->take(5) as $account)
                                        <tr>
                                            <td>{{ $account->email }}</td>
                                            <td>
                                                <span class="badge badge-danger">
                                                    {{ $account->locked_until->diffForHumans() }}
                                                </span>
                                            </td>
                                            <td>
                                                @can('security.manage')
                                                    <form action="{{ route('admin.security.unlock', $account) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('POST')
                                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Unlock this account?')">
                                                            <i class="fas fa-unlock"></i> Unlock
                                                        </button>
                                                    </form>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-check-circle fa-3x mb-3"></i>
                            <p>No accounts are currently locked</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Failed Attempts -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-exclamation-circle me-2"></i>Recent Failed Login Attempts</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.security.failed-logins') }}" class="btn btn-primary">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentAttempts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Email</th>
                                        <th>IP Address</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentAttempts->take(10) as $attempt)
                                        <tr>
                                            <td>
                                                {{ $attempt->email ?? 'Unknown' }}
                                            </td>
                                            <td><code>{{ $attempt->ip_address }}</code></td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $attempt->created_at->diffForHumans() }}
                                                </small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-shield-alt fa-3x mb-3"></i>
                            <p>No failed login attempts recorded</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Top Targeted Emails and IPs -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-crosshairs me-2"></i>Most Targeted Emails (This Week)</h4>
                </div>
                <div class="card-body p-0">
                    @if($stats['top_targeted_emails']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Email</th>
                                        <th>Attempts</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['top_targeted_emails'] as $email)
                                        <tr>
                                            <td>{{ $email->email }}</td>
                                            <td><span class="badge badge-danger">{{ $email->count }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted py-4">No data available</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-ban me-2"></i>Top Attacking IPs (This Week)</h4>
                </div>
                <div class="card-body p-0">
                    @if($stats['top_ips']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>IP Address</th>
                                        <th>Attempts</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['top_ips'] as $ip)
                                        <tr>
                                            <td><code>{{ $ip->ip_address }}</code></td>
                                            <td><span class="badge badge-warning">{{ $ip->count }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted py-4">No data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-tools me-2"></i>Security Actions</h4>
                </div>
                <div class="card-body">
                    @can('security.manage')
                        <form action="{{ route('admin.security.clear-old') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-warning" onclick="return confirm('Clear failed login attempts older than 30 days?')">
                                <i class="fas fa-broom me-2"></i>Clear Old Attempts (30+ days)
                            </button>
                        </form>
                    @endcan
                    <p class="text-muted mt-3 mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Accounts are automatically locked for 15 minutes after 5 failed login attempts.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
