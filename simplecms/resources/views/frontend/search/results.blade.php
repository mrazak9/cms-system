@extends('frontend.layouts.app')

@section('title', 'Search Results' . ($searchQuery ? ': ' . $searchQuery : '') . ' - ' . ($settings['site_name'] ?? 'SimpleCMS'))
@section('meta_description', 'Search results for ' . ($searchQuery ?? 'posts'))

@section('content')
    {{-- Breadcrumb --}}
    <section class="bg-light py-3">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Blog</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Search Results</li>
                </ol>
            </nav>
        </div>
    </section>

    {{-- Search Header --}}
    <section class="bg-primary text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-6 fw-bold mb-3">
                        <i class="fas fa-search me-2"></i>Search Results
                    </h1>
                    @if($searchQuery)
                        <p class="lead mb-0">Found <strong>{{ $resultsCount }}</strong> {{ Str::plural('result', $resultsCount) }} for "<strong>{{ $searchQuery }}</strong>"</p>
                    @else
                        <p class="lead mb-0">Browse <strong>{{ $resultsCount }}</strong> {{ Str::plural('post', $resultsCount) }}</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Search Content --}}
    <section class="section">
        <div class="container">
            <div class="row g-4">
                {{-- Sidebar with Filters --}}
                <div class="col-lg-3">
                    <div class="sticky-top" style="top: 20px;">
                        {{-- Search Form --}}
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title fw-bold mb-3"><i class="fas fa-search me-2"></i>Search</h5>
                                <form action="{{ route('search') }}" method="GET" id="searchForm">
                                    <div class="mb-3">
                                        <input type="search"
                                               name="q"
                                               class="form-control"
                                               placeholder="Search posts..."
                                               value="{{ $searchQuery }}"
                                               id="searchInput"
                                               autocomplete="off">
                                        <div id="searchSuggestions" class="list-group mt-2" style="display: none; position: absolute; z-index: 1000; width: calc(100% - 30px);"></div>
                                    </div>

                                    <hr>

                                    <h6 class="fw-bold mb-3">Filters</h6>

                                    {{-- Category Filter --}}
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Category</label>
                                        <select name="category" class="form-select form-select-sm">
                                            <option value="">All Categories</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ $categoryFilter == $cat->id ? 'selected' : '' }}>
                                                    {{ $cat->name }} ({{ $cat->posts_count }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Tag Filter --}}
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Tag</label>
                                        <select name="tag" class="form-select form-select-sm">
                                            <option value="">All Tags</option>
                                            @foreach($tags as $tag)
                                                <option value="{{ $tag->id }}" {{ $tagFilter == $tag->id ? 'selected' : '' }}>
                                                    {{ $tag->name }} ({{ $tag->posts_count }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Date Range Filter --}}
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Date From</label>
                                        <input type="date" name="date_from" class="form-control form-control-sm" value="{{ $dateFrom }}">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Date To</label>
                                        <input type="date" name="date_to" class="form-control form-control-sm" value="{{ $dateTo }}">
                                    </div>

                                    {{-- Sort By --}}
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Sort By</label>
                                        <select name="sort_by" class="form-select form-select-sm">
                                            <option value="latest" {{ $sortBy == 'latest' ? 'selected' : '' }}>Latest First</option>
                                            <option value="oldest" {{ $sortBy == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                            <option value="popular" {{ $sortBy == 'popular' ? 'selected' : '' }}>Most Popular</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-sm w-100 mb-2">
                                        <i class="fas fa-filter me-2"></i>Apply Filters
                                    </button>
                                    <a href="{{ route('search') }}" class="btn btn-outline-secondary btn-sm w-100">
                                        <i class="fas fa-times me-2"></i>Clear All
                                    </a>
                                </form>
                            </div>
                        </div>

                        {{-- Active Filters --}}
                        @if($searchQuery || $categoryFilter || $tagFilter || $dateFrom || $dateTo)
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3">Active Filters</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        @if($searchQuery)
                                            <span class="badge bg-primary">
                                                Query: {{ $searchQuery }}
                                                <a href="{{ route('search') }}" class="text-white ms-1"><i class="fas fa-times"></i></a>
                                            </span>
                                        @endif
                                        @if($categoryFilter)
                                            @php $cat = $categories->find($categoryFilter); @endphp
                                            @if($cat)
                                                <span class="badge bg-info">
                                                    Category: {{ $cat->name }}
                                                </span>
                                            @endif
                                        @endif
                                        @if($tagFilter)
                                            @php $tag = $tags->find($tagFilter); @endphp
                                            @if($tag)
                                                <span class="badge bg-success">
                                                    Tag: {{ $tag->name }}
                                                </span>
                                            @endif
                                        @endif
                                        @if($dateFrom)
                                            <span class="badge bg-warning text-dark">
                                                From: {{ $dateFrom }}
                                            </span>
                                        @endif
                                        @if($dateTo)
                                            <span class="badge bg-warning text-dark">
                                                To: {{ $dateTo }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Main Content --}}
                <div class="col-lg-9">
                    @if($posts->count() > 0)
                        <div class="row g-4">
                            @foreach($posts as $post)
                                <div class="col-12">
                                    <article class="card border-0 shadow-sm h-100 hover-lift">
                                        <div class="row g-0">
                                            {{-- Featured Image --}}
                                            @if($post->featured_image)
                                                <div class="col-md-4">
                                                    <a href="{{ route('blog.show', $post->slug) }}">
                                                        <img src="{{ asset('storage/' . $post->featured_image) }}"
                                                             class="img-fluid h-100 w-100"
                                                             alt="{{ $post->title }}"
                                                             style="object-fit: cover; min-height: 200px;">
                                                    </a>
                                                </div>
                                            @endif

                                            {{-- Post Content --}}
                                            <div class="{{ $post->featured_image ? 'col-md-8' : 'col-12' }}">
                                                <div class="card-body p-4">
                                                    {{-- Meta Info --}}
                                                    <div class="d-flex align-items-center mb-3 flex-wrap gap-2">
                                                        @if($post->category)
                                                            <a href="{{ route('blog.category', $post->category->slug) }}" class="badge bg-primary text-decoration-none">
                                                                {{ $post->category->name }}
                                                            </a>
                                                        @endif
                                                        <span class="text-muted small">
                                                            <i class="far fa-calendar me-1"></i>{{ $post->published_at->format('M d, Y') }}
                                                        </span>
                                                        @if($post->views_count)
                                                            <span class="text-muted small">
                                                                <i class="far fa-eye me-1"></i>{{ $post->views_count }} views
                                                            </span>
                                                        @endif
                                                    </div>

                                                    {{-- Title with Highlighting --}}
                                                    <h4 class="card-title mb-3">
                                                        <a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none text-dark">
                                                            {!! $searchQuery ? preg_replace('/(' . preg_quote($searchQuery, '/') . ')/i', '<mark>$1</mark>', $post->title) : $post->title !!}
                                                        </a>
                                                    </h4>

                                                    {{-- Excerpt with Highlighting --}}
                                                    @if($post->excerpt)
                                                        <p class="card-text text-muted mb-3">
                                                            {!! $searchQuery ? preg_replace('/(' . preg_quote($searchQuery, '/') . ')/i', '<mark>$1</mark>', $post->excerpt) : $post->excerpt !!}
                                                        </p>
                                                    @else
                                                        <p class="card-text text-muted mb-3">
                                                            {!! $searchQuery ? preg_replace('/(' . preg_quote($searchQuery, '/') . ')/i', '<mark>$1</mark>', Str::limit(strip_tags($post->content), 150)) : Str::limit(strip_tags($post->content), 150) !!}
                                                        </p>
                                                    @endif

                                                    {{-- Tags --}}
                                                    @if($post->tags && $post->tags->count() > 0)
                                                        <div class="mb-3">
                                                            @foreach($post->tags->take(3) as $postTag)
                                                                <a href="{{ route('blog.tag', $postTag->slug) }}" class="badge bg-light text-dark text-decoration-none me-1">
                                                                    <i class="fas fa-tag me-1"></i>{{ $postTag->name }}
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                    {{-- Read More --}}
                                                    <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-outline-primary btn-sm">
                                                        Read More <i class="fas fa-arrow-right ms-1"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-5">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-search fa-5x text-muted"></i>
                            </div>
                            <h3 class="mb-3">No Results Found</h3>
                            @if($searchQuery)
                                <p class="text-muted mb-4">We couldn't find any posts matching "<strong>{{ $searchQuery }}</strong>"</p>
                                <div class="mb-4">
                                    <p class="fw-bold">Search Tips:</p>
                                    <ul class="list-unstyled text-muted">
                                        <li>• Try different keywords</li>
                                        <li>• Check your spelling</li>
                                        <li>• Use fewer or more general keywords</li>
                                        <li>• Remove filters to broaden your search</li>
                                    </ul>
                                </div>
                            @endif
                            <a href="{{ route('blog.index') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i>Browse All Posts
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important;
    }

    mark {
        background-color: #fff3cd;
        padding: 2px 4px;
        border-radius: 3px;
        font-weight: 600;
    }

    .bg-primary {
        background-color: var(--primary-color) !important;
    }

    .text-primary {
        color: var(--primary-color) !important;
    }

    .sticky-top {
        position: sticky;
    }

    #searchSuggestions {
        max-height: 300px;
        overflow-y: auto;
    }

    .list-group-item-suggestion {
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .list-group-item-suggestion:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush

@push('scripts')
<script>
    // Search Autocomplete
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    const suggestionsDiv = document.getElementById('searchSuggestions');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value;

            if (query.length < 2) {
                suggestionsDiv.style.display = 'none';
                return;
            }

            searchTimeout = setTimeout(function() {
                fetch(`{{ route('search.suggestions') }}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            let html = '';
                            data.forEach(item => {
                                html += `
                                    <a href="${item.url}" class="list-group-item list-group-item-action list-group-item-suggestion">
                                        <div class="d-flex align-items-center">
                                            ${item.image ? `<img src="${item.image}" class="me-2 rounded" style="width: 40px; height: 40px; object-fit: cover;">` : ''}
                                            <div class="flex-grow-1">
                                                <small>${item.title}</small>
                                            </div>
                                        </div>
                                    </a>
                                `;
                            });
                            suggestionsDiv.innerHTML = html;
                            suggestionsDiv.style.display = 'block';
                        } else {
                            suggestionsDiv.style.display = 'none';
                        }
                    })
                    .catch(() => {
                        suggestionsDiv.style.display = 'none';
                    });
            }, 300);
        });

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.style.display = 'none';
            }
        });
    }
</script>
@endpush
