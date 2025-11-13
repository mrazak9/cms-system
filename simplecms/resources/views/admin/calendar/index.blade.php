@extends('admin.layouts.app')

@section('title', 'Editorial Calendar')

@section('content')
<div class="section-header">
    <h1>Editorial Calendar</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item">Editorial Calendar</div>
    </div>
</div>

<div class="section-body">
    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-secondary">
                    <i class="far fa-file"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Drafts</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['draft'] }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="far fa-clock"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Pending Review</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['pending_review'] }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-info">
                    <i class="far fa-calendar-alt"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Scheduled</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['scheduled'] }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Published This Month</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['published_this_month'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ $date->format('F Y') }}</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.calendar.index', ['month' => $date->copy()->subMonth()->month, 'year' => $date->copy()->subMonth()->year]) }}"
                           class="btn btn-primary">
                            <i class="fas fa-chevron-left"></i> Previous
                        </a>
                        <a href="{{ route('admin.calendar.index', ['month' => now()->month, 'year' => now()->year]) }}"
                           class="btn btn-secondary">
                            Today
                        </a>
                        <a href="{{ route('admin.calendar.index', ['month' => $date->copy()->addMonth()->month, 'year' => $date->copy()->addMonth()->year]) }}"
                           class="btn btn-primary">
                            Next <i class="fas fa-chevron-right"></i>
                        </a>
                        <a href="{{ route('admin.calendar.upcoming') }}"
                           class="btn btn-info">
                            <i class="fas fa-list"></i> Upcoming
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th width="14%">Sunday</th>
                                    <th width="14%">Monday</th>
                                    <th width="14%">Tuesday</th>
                                    <th width="14%">Wednesday</th>
                                    <th width="14%">Thursday</th>
                                    <th width="14%">Friday</th>
                                    <th width="14%">Saturday</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $startOfMonth = $date->copy()->startOfMonth();
                                    $endOfMonth = $date->copy()->endOfMonth();
                                    $startOfCalendar = $startOfMonth->copy()->startOfWeek();
                                    $endOfCalendar = $endOfMonth->copy()->endOfWeek();
                                    $currentDate = $startOfCalendar->copy();
                                @endphp

                                @while($currentDate <= $endOfCalendar)
                                    <tr>
                                        @for($i = 0; $i < 7; $i++)
                                            @php
                                                $dateKey = $currentDate->format('Y-m-d');
                                                $isCurrentMonth = $currentDate->month == $date->month;
                                                $isToday = $currentDate->isToday();
                                                $dayContent = $calendar[$dateKey] ?? null;
                                            @endphp
                                            <td class="align-top p-2"
                                                style="height: 120px; {{ !$isCurrentMonth ? 'background-color: #f8f9fa;' : '' }} {{ $isToday ? 'border: 2px solid #6777ef;' : '' }}">
                                                <div class="font-weight-bold mb-1" style="{{ !$isCurrentMonth ? 'color: #ccc;' : '' }}">
                                                    {{ $currentDate->day }}
                                                </div>
                                                @if($dayContent)
                                                    @foreach($dayContent['posts'] as $post)
                                                        <div class="mb-1">
                                                            <a href="{{ route('admin.posts.edit', $post->id) }}"
                                                               class="badge {{ $post->getWorkflowStatusBadgeClass() }} d-block text-left small"
                                                               style="white-space: normal; text-align: left;">
                                                                <i class="fas fa-file-alt"></i>
                                                                {{ Str::limit($post->title, 30) }}
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                    @foreach($dayContent['pages'] as $page)
                                                        <div class="mb-1">
                                                            <a href="{{ route('admin.pages.edit', $page->id) }}"
                                                               class="badge {{ $page->getWorkflowStatusBadgeClass() }} d-block text-left small"
                                                               style="white-space: normal; text-align: left;">
                                                                <i class="fas fa-file"></i>
                                                                {{ Str::limit($page->title, 30) }}
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </td>
                                            @php $currentDate->addDay(); @endphp
                                        @endfor
                                    </tr>
                                @endwhile
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Legend -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Legend</h4>
                </div>
                <div class="card-body">
                    <span class="badge badge-secondary mr-2">Draft</span>
                    <span class="badge badge-warning mr-2">Pending Review</span>
                    <span class="badge badge-info mr-2">Scheduled</span>
                    <span class="badge badge-success mr-2">Published</span>
                    <span class="badge badge-dark mr-2">Archived</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
