@extends('admin.layouts.app')

@section('title', 'Analytics Dashboard')
@section('page-title', 'Analytics Dashboard')

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item active">Analytics</div>
@endsection

@section('content')
    <!-- Time Range Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="form-inline">
                        <label class="mr-2">Time Range:</label>
                        <select name="days" class="form-control mr-2" onchange="this.form.submit()">
                            <option value="7" {{ $days == 7 ? 'selected' : '' }}>Last 7 Days</option>
                            <option value="30" {{ $days == 30 ? 'selected' : '' }}>Last 30 Days</option>
                            <option value="60" {{ $days == 60 ? 'selected' : '' }}>Last 60 Days</option>
                            <option value="90" {{ $days == 90 ? 'selected' : '' }}>Last 90 Days</option>
                        </select>
                        <a href="{{ route('admin.analytics.export', ['days' => $days]) }}" class="btn btn-primary ml-2">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total Views</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['total_views']) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Unique Visitors</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['unique_visitors']) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Views Today</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['views_today']) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-info">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Avg Daily Views</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['avg_daily_views'], 0) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4>Page Views Over Time</h4>
                </div>
                <div class="card-body">
                    <canvas id="viewsChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Device Types</h4>
                </div>
                <div class="card-body">
                    <canvas id="deviceChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>Top Browsers</h4>
                </div>
                <div class="card-body">
                    <canvas id="browserChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>Most Viewed Posts</h4>
                </div>
                <div class="card-body p-0">
                    @if($postsData->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Views</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($postsData->take(10) as $item)
                                        @if($item->content)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('admin.posts.edit', $item->content->id) }}">
                                                        {{ Str::limit($item->content->title, 50) }}
                                                    </a>
                                                </td>
                                                <td><span class="badge badge-primary">{{ number_format($item->views) }}</span></td>
                                            </tr>
                                        @endif
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

    <!-- Top Referrers -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Top Referrers</h4>
                </div>
                <div class="card-body p-0">
                    @if($topReferrers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Referrer</th>
                                        <th>Views</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topReferrers as $referrer)
                                        <tr>
                                            <td>
                                                <a href="{{ $referrer->referrer }}" target="_blank" rel="nofollow">
                                                    {{ Str::limit($referrer->referrer, 80) }}
                                                </a>
                                            </td>
                                            <td><span class="badge badge-success">{{ number_format($referrer->views) }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted py-4">No referrer data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Views Over Time Chart
const viewsCtx = document.getElementById('viewsChart').getContext('2d');
const viewsChart = new Chart(viewsCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($viewsByDate->pluck('date')) !!},
        datasets: [{
            label: 'Page Views',
            data: {!! json_encode($viewsByDate->pluck('views')) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true
            }
        }
    }
});

// Device Types Chart
const deviceCtx = document.getElementById('deviceChart').getContext('2d');
const deviceChart = new Chart(deviceCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($viewsByDevice->pluck('device_type')) !!},
        datasets: [{
            data: {!! json_encode($viewsByDevice->pluck('views')) !!},
            backgroundColor: [
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 206, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Browser Chart
const browserCtx = document.getElementById('browserChart').getContext('2d');
const browserChart = new Chart(browserCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($viewsByBrowser->pluck('browser')) !!},
        datasets: [{
            label: 'Views',
            data: {!! json_encode($viewsByBrowser->pluck('views')) !!},
            backgroundColor: 'rgba(54, 162, 235, 0.8)'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>
@endpush
