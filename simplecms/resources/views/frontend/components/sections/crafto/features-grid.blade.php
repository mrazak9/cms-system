{{-- Features Grid Component --}}
@props([
    'section' => null,
    'content' => []
])

@php
    $heading = $content['heading'] ?? 'Our Features';
    $subheading = $content['subheading'] ?? '';
    $description = $content['description'] ?? '';
    $columns = $content['columns'] ?? 3; // 2, 3, or 4
    $features = $content['features'] ?? [];
    $backgroundColor = $content['background_color'] ?? '#f7f7f7';
    $animation = $content['animation'] ?? true;

    // Column classes
    $columnClass = match($columns) {
        2 => 'row-cols-lg-2',
        4 => 'row-cols-lg-4',
        default => 'row-cols-lg-3'
    };

    // Default features if none provided
    if (empty($features)) {
        $features = [
            [
                'icon' => 'line-icon-File-Edit',
                'icon_type' => 'line-icon',
                'title' => 'Content Management',
                'description' => 'Create and manage unlimited pages and blog posts with rich content editor.'
            ],
            [
                'icon' => 'line-icon-Bar-Chart',
                'icon_type' => 'line-icon',
                'title' => 'Analytics',
                'description' => 'Track your website performance with built-in analytics and insights.'
            ],
            [
                'icon' => 'line-icon-Photo-Album',
                'icon_type' => 'line-icon',
                'title' => 'Media Library',
                'description' => 'Upload and organize your images and files with intuitive media manager.'
            ]
        ];
    }
@endphp

<section style="background-color: {{ $backgroundColor }};">
    <div class="container">
        @if($heading || $subheading || $description)
            <div class="row justify-content-center mb-3">
                <div class="col-xxl-6 col-xl-7 col-lg-8 col-md-9 text-center">
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

        <div class="row row-cols-1 {{ $columnClass }} row-cols-md-2 justify-content-center mb-6"
            @if($animation)
            data-anime='{ "el": "childs", "translateY": [50, 0], "opacity": [0,1], "duration": 1200, "delay": 0, "staggervalue": 150, "easing": "easeOutQuad" }'
            @endif>
            @foreach($features as $feature)
                <div class="col icon-with-text-style-08 transition-inner-all mb-30px">
                    <div class="feature-box feature-box-left-icon-middle bg-white border-radius-6px overflow-hidden box-shadow-quadruple-large box-shadow-quadruple-large-hover">
                        <div class="feature-box-icon">
                            @if(isset($feature['icon_type']) && $feature['icon_type'] === 'feather')
                                <i class="feather icon-feather-{{ $feature['icon'] }} icon-extra-large text-base-color"></i>
                            @elseif(isset($feature['icon_type']) && $feature['icon_type'] === 'fontawesome')
                                <i class="fa-solid fa-{{ $feature['icon'] }} icon-extra-large text-base-color"></i>
                            @else
                                <i class="{{ $feature['icon'] }} icon-extra-large text-base-color"></i>
                            @endif
                        </div>
                        <div class="feature-box-content">
                            <span class="d-inline-block fs-19 fw-600 text-dark-gray mb-5px">{{ $feature['title'] }}</span>
                            <p>{{ $feature['description'] }}</p>
                        </div>
                        <div class="feature-box-overlay bg-base-color"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
    .bg-base-color {
        background-color: #0039e3;
    }
    .text-base-color {
        color: #0039e3;
    }
    .feature-box-overlay {
        background: linear-gradient(to right, #0039e3, #4132e0);
    }
</style>
