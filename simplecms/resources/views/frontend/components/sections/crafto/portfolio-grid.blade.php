{{-- Portfolio Grid Component --}}
@props(['section' => null, 'content' => []])

@php
    $heading = $content['heading'] ?? 'Our Portfolio';
    $subheading = $content['subheading'] ?? 'Recent Work';
    $description = $content['description'] ?? '';
    $columns = $content['columns'] ?? 3;
    $projects = $content['projects'] ?? [];
    $backgroundColor = $content['background_color'] ?? '#ffffff';
    $animation = $content['animation'] ?? true;

    $columnClass = match($columns) {
        2 => 'row-cols-lg-2',
        4 => 'row-cols-lg-4',
        default => 'row-cols-lg-3'
    };

    if (empty($projects)) {
        $projects = [
            ['image' => asset('crafto/images/portfolio-01.jpg'), 'title' => 'Modern Website Design', 'category' => 'Web Design', 'url' => '#'],
            ['image' => asset('crafto/images/portfolio-02.jpg'), 'title' => 'Brand Identity', 'category' => 'Branding', 'url' => '#'],
            ['image' => asset('crafto/images/portfolio-03.jpg'), 'title' => 'Mobile App Development', 'category' => 'Development', 'url' => '#'],
            ['image' => asset('crafto/images/portfolio-04.jpg'), 'title' => 'E-commerce Platform', 'category' => 'Web Development', 'url' => '#'],
            ['image' => asset('crafto/images/portfolio-05.jpg'), 'title' => 'Marketing Campaign', 'category' => 'Marketing', 'url' => '#'],
            ['image' => asset('crafto/images/portfolio-06.jpg'), 'title' => 'Corporate Branding', 'category' => 'Branding', 'url' => '#']
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
            data-anime='{ "el": "childs", "translateY": [50, 0], "opacity": [0,1], "duration": 800, "delay": 0, "staggervalue": 200, "easing": "easeOutQuad" }'
            @endif>
            @foreach($projects as $project)
                <div class="col mb-30px">
                    <div class="portfolio-card">
                        <div class="portfolio-image">
                            <img src="{{ $project['image'] }}" alt="{{ $project['title'] }}">
                            <div class="portfolio-overlay">
                                <div class="portfolio-content">
                                    <span class="portfolio-category">{{ $project['category'] }}</span>
                                    <h4 class="portfolio-title">{{ $project['title'] }}</h4>
                                    <a href="{{ $project['url'] ?? '#' }}" class="portfolio-link">
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
    .portfolio-card {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.08);
    }
    .portfolio-image {
        position: relative;
        padding-top: 75%; /* 4:3 aspect ratio */
        overflow: hidden;
    }
    .portfolio-image img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .portfolio-card:hover .portfolio-image img {
        transform: scale(1.1);
    }
    .portfolio-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to top, rgba(0, 57, 227, 0.95), rgba(0, 57, 227, 0.7));
        opacity: 0;
        transition: opacity 0.4s ease;
        display: flex;
        align-items: flex-end;
        padding: 30px;
    }
    .portfolio-card:hover .portfolio-overlay {
        opacity: 1;
    }
    .portfolio-content {
        width: 100%;
        transform: translateY(20px);
        transition: transform 0.4s ease;
    }
    .portfolio-card:hover .portfolio-content {
        transform: translateY(0);
    }
    .portfolio-category {
        color: #ffffff;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: block;
        margin-bottom: 8px;
        opacity: 0.9;
    }
    .portfolio-title {
        color: #ffffff;
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 15px;
    }
    .portfolio-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: #ffffff;
        color: #0039e3;
        border-radius: 50%;
        transition: all 0.3s ease;
    }
    .portfolio-link:hover {
        transform: scale(1.1);
        background: #0039e3;
        color: #ffffff;
    }
    .text-base-color {
        color: #0039e3;
    }
</style>
