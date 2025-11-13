@extends('admin.layouts.app')

@section('title', 'Search Statistics')
@section('page-title', 'Search Statistics & Analytics')

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item active">Search Statistics</div>
@endsection

@section('content')
    {{-- Date Range Filter --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="form-inline">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label>Date From</label>
                                <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                            </div>
                            <div class="col-md-4">
                                <label>Date To</label>
                                <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Apply Filter
                                </button>
                                <a href="{{ route('admin.search-statistics.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary"><i class="fas fa-search"></i></div>
                <div class="card-wrap">
                    <div class="card-header"><h4>Total Searches (Period)</h4></div>
                    <div class="card-body">{{ number_format($stats['total_searches_period']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success"><i class="fas fa-search-plus"></i></div>
                <div class="card-wrap">
                    <div class="card-header"><h4>Unique Queries</h4></div>
                    <div class="card-body">{{ number_format($stats['unique_queries']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning"><i class="fas fa-chart-line"></i></div>
                <div class="card-wrap">
                    <div class="card-header"><h4>Avg Results</h4></div>
                    <div class="card-body">{{ $stats['avg_results_per_search'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="card-wrap">
                    <div class="card-header"><h4>No Results</h4></div>
                    <div class="card-body">{{ number_format($stats['searches_with_no_results']) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Search Trends Chart --}}
    @if($searchTrends->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Search Trends</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="searchTrendsChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        {{-- Popular Searches --}}
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h4>Popular Searches</h4>
                    <div class="card-header-action">
                        <span class="badge badge-primary">Top 20</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Query</th>
                                    <th class="text-center">Searches</th>
                                    <th class="text-center">Avg Results</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($popularSearches as $index => $search)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $search->query }}</strong>
                                            <br>
                                            <a href="{{ route('search') }}?q={{ urlencode($search->query) }}" target="_blank" class="small text-muted">
                                                <i class="fas fa-external-link-alt"></i> View Results
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-info">{{ $search->search_count }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $search->avg_results > 0 ? 'success' : 'danger' }}">
                                                {{ round($search->avg_results, 1) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No search data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Searches with No Results --}}
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h4>Searches with No Results</h4>
                    <div class="card-header-action">
                        <span class="badge badge-danger">Top 20</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Query</th>
                                    <th class="text-center">Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($noResultsSearches as $index => $search)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $search->query }}</strong>
                                            <br>
                                            <small class="text-danger">
                                                <i class="fas fa-info-circle"></i> Consider creating content for this
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-danger">{{ $search->search_count }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">No data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Top Filters Used --}}
    @if($topFilters->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Most Used Filters</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($topFilters as $filter => $count)
                                <div class="col-md-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                        <div>
                                            <strong>{{ ucfirst($filter) }}</strong>
                                        </div>
                                        <div>
                                            <span class="badge badge-primary badge-lg">{{ $count }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Recent Searches --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Recent Searches</h4>
                    <div class="card-header-action">
                        <span class="badge badge-info">Last 50</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Query</th>
                                    <th>User</th>
                                    <th class="text-center">Results</th>
                                    <th>IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentSearches as $search)
                                    <tr>
                                        <td>
                                            <small>{{ $search->created_at->format('M d, Y H:i') }}</small>
                                            <br>
                                            <small class="text-muted">{{ $search->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $search->query }}</strong>
                                            @if($search->filters && count($search->filters) > 0)
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-filter"></i> Filters applied: {{ count($search->filters) }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($search->user)
                                                {{ $search->user->name }}
                                                <br>
                                                <small class="text-muted">{{ $search->user->email }}</small>
                                            @else
                                                <span class="text-muted">Guest</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $search->results_count > 0 ? 'success' : 'danger' }}">
                                                {{ $search->results_count }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $search->ip_address }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No recent searches</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
@if($searchTrends->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Search Trends Chart
    const ctx = document.getElementById('searchTrendsChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($searchTrends->pluck('date')->map(function($date) {
                    return \Carbon\Carbon::parse($date)->format('M d');
                })) !!},
                datasets: [{
                    label: 'Searches',
                    data: {!! json_encode($searchTrends->pluck('count')) !!},
                    borderColor: 'rgb(99, 102, 241)',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }
</script>
@endif
@endpush
