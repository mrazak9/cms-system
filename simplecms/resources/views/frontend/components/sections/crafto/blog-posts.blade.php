{{-- Blog Posts Grid Component --}}
@props([
    'section' => null,
    'content' => []
])

@php
    $heading = $content['heading'] ?? 'Latest News';
    $subheading = $content['subheading'] ?? 'Blog';
    $description = $content['description'] ?? '';
    $columns = $content['columns'] ?? 3; // 2, 3, or 4
    $showExcerpt = $content['show_excerpt'] ?? true;
    $showDate = $content['show_date'] ?? true;
    $showAuthor = $content['show_author'] ?? true;
    $showCategory = $content['show_category'] ?? true;
    $posts = $content['posts'] ?? [];
    $backgroundColor = $content['background_color'] ?? '#ffffff';
    $animation = $content['animation'] ?? true;

    // Column classes
    $columnClass = match($columns) {
        2 => 'row-cols-lg-2',
        4 => 'row-cols-lg-4',
        default => 'row-cols-lg-3'
    };

    // Default posts if none provided
    if (empty($posts)) {
        $posts = [
            [
                'image' => asset('crafto/images/demo-corporate-blog-01.jpg'),
                'title' => 'The Future of Web Design',
                'excerpt' => 'Discover the latest trends and technologies shaping the future of web design...',
                'date' => '2025-10-15',
                'author' => 'John Doe',
                'category' => 'Design',
                'url' => '#'
            ],
            [
                'image' => asset('crafto/images/demo-corporate-blog-02.jpg'),
                'title' => 'Building Scalable Applications',
                'excerpt' => 'Learn best practices for building applications that scale with your business...',
                'date' => '2025-10-20',
                'author' => 'Jane Smith',
                'category' => 'Development',
                'url' => '#'
            ],
            [
                'image' => asset('crafto/images/demo-corporate-blog-03.jpg'),
                'title' => 'UX Design Principles',
                'excerpt' => 'Essential principles every designer should know to create better user experiences...',
                'date' => '2025-10-25',
                'author' => 'Mike Johnson',
                'category' => 'UX Design',
                'url' => '#'
            ]
        ];
    }
@endphp

<section style="background-color: {{ $backgroundColor }};">
    <div class="container">
        @if($heading || $subheading || $description)
            <div class="row justify-content-center mb-5">
                <div class="col-lg-7 text-center">
                    @if($subheading)
                        <span class="text-base-color fw-600 mb-5px text-uppercase d-block">{{ $subheading }}</span>
                    @endif
                    @if($heading)
                        <h2 class="fw-700 text-dark-gray ls-minus-2px">{{ $heading }}</h2>
                    @endif
                    @if($description)
                        <p class="w-85 md-w-100 mx-auto">{{ $description }}</p>
                    @endif
                </div>
            </div>
        @endif

        <div class="row row-cols-1 {{ $columnClass }} row-cols-sm-2"
            @if($animation)
            data-anime='{ "el": "childs", "translateY": [30, 0], "opacity": [0,1], "duration": 600, "delay": 0, "staggervalue": 200, "easing": "easeOutQuad" }'
            @endif>
            @foreach($posts as $post)
                <div class="col mb-30px">
                    <div class="blog-card">
                        @if(isset($post['image']))
                            <div class="blog-card-image">
                                <a href="{{ $post['url'] ?? '#' }}">
                                    <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}">
                                </a>
                                @if($showCategory && isset($post['category']))
                                    <span class="blog-category">{{ $post['category'] }}</span>
                                @endif
                            </div>
                        @endif

                        <div class="blog-card-content">
                            @if(($showDate && isset($post['date'])) || ($showAuthor && isset($post['author'])))
                                <div class="blog-meta mb-15px">
                                    @if($showDate && isset($post['date']))
                                        <span class="blog-date">
                                            <i class="fa-regular fa-calendar me-5px"></i>
                                            {{ date('M d, Y', strtotime($post['date'])) }}
                                        </span>
                                    @endif
                                    @if($showAuthor && isset($post['author']))
                                        <span class="blog-author">
                                            <i class="fa-regular fa-user me-5px"></i>
                                            {{ $post['author'] }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <h4 class="blog-title">
                                <a href="{{ $post['url'] ?? '#' }}">{{ $post['title'] }}</a>
                            </h4>

                            @if($showExcerpt && isset($post['excerpt']))
                                <p class="blog-excerpt">{{ $post['excerpt'] }}</p>
                            @endif

                            <a href="{{ $post['url'] ?? '#' }}" class="blog-read-more">
                                Read More <i class="fa-solid fa-arrow-right ms-5px"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
    .blog-card {
        background: #ffffff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .blog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
    }

    .blog-card-image {
        position: relative;
        overflow: hidden;
        padding-top: 66.67%; /* 3:2 aspect ratio */
    }

    .blog-card-image img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .blog-card:hover .blog-card-image img {
        transform: scale(1.05);
    }

    .blog-category {
        position: absolute;
        top: 20px;
        left: 20px;
        background: #0039e3;
        color: #ffffff;
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        z-index: 1;
    }

    .blog-card-content {
        padding: 30px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .blog-meta {
        display: flex;
        gap: 20px;
        font-size: 13px;
        color: #999;
    }

    .blog-meta span {
        display: flex;
        align-items: center;
    }

    .blog-title {
        margin-bottom: 15px;
        line-height: 1.4;
    }

    .blog-title a {
        color: #232323;
        text-decoration: none;
        font-size: 20px;
        font-weight: 700;
        transition: color 0.3s ease;
    }

    .blog-title a:hover {
        color: #0039e3;
    }

    .blog-excerpt {
        color: #666;
        line-height: 1.7;
        margin-bottom: 20px;
        flex-grow: 1;
    }

    .blog-read-more {
        color: #0039e3;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
    }

    .blog-read-more:hover {
        gap: 8px;
    }

    .text-base-color {
        color: #0039e3;
    }
</style>
