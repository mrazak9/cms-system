{{-- About Left Image Component --}}
@props([
    'section' => null,
    'content' => []
])

@php
    $image = $content['image'] ?? asset('crafto/images/demo-corporate-about.jpg');
    $badge = $content['badge'] ?? '';
    $subtitle = $content['subtitle'] ?? 'About Us';
    $title = $content['title'] ?? 'We are creative digital agency';
    $description = $content['description'] ?? 'Lorem ipsum dolor sit amet consectetur adipiscing elit do eiusmod tempor incididunt ut labore et dolore magna ut enim ad minim veniam nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.';
    $features = $content['features'] ?? [];
    $buttonText = $content['button_text'] ?? 'Learn More';
    $buttonUrl = $content['button_url'] ?? '#';
    $backgroundColor = $content['background_color'] ?? '#ffffff';
    $imageAnimation = $content['image_animation'] ?? true;

    // Default features if none provided
    if (empty($features)) {
        $features = [
            'Professional Design',
            'Creative Solutions',
            'Expert Team',
            'Quality Service'
        ];
    }
@endphp

<section style="background-color: {{ $backgroundColor }};">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            {{-- Image Column --}}
            <div class="col-lg-6 col-md-10 md-mb-50px"
                @if($imageAnimation)
                data-anime='{ "effect": "slide", "color": "#005153", "direction": "lr", "easing": "easeOutQuad", "delay": 50 }'
                @endif>
                <figure class="position-relative m-0">
                    <img src="{{ $image }}" alt="{{ $title }}" class="w-100 border-radius-6px" />
                    @if($badge)
                        <div class="atropos-rotate-scroll-right position-absolute top-50px lg-top-30px left-minus-30px lg-left-0px" data-bottom-top="transform: translateY(-50px)" data-top-bottom="transform: translateY(50px)">
                            <div class="fs-170 lg-fs-130 md-fs-90 fw-600 text-outline text-outline-width-2px text-outline-color-base-color opacity-3 font-playfair-display" data-fancy-text='{ "opacity": [0, 1], "viewport": true, "string": ["{{ $badge }}"] }'></div>
                        </div>
                    @endif
                </figure>
            </div>

            {{-- Content Column --}}
            <div class="col-xl-5 offset-xl-1 col-lg-6 col-md-10 text-center text-lg-start">
                @if($subtitle)
                    <span class="text-base-color fw-600 mb-15px text-uppercase d-block">{{ $subtitle }}</span>
                @endif
                <h2 class="fw-700 text-dark-gray ls-minus-2px mb-20px">{{ $title }}</h2>
                <p class="w-90 md-w-100 mb-30px">{{ $description }}</p>

                @if(count($features) > 0)
                    <ul class="p-0 mb-35px list-style-02">
                        @foreach($features as $feature)
                            <li class="pb-10px mb-10px border-bottom border-color-extra-medium-gray">
                                <i class="feather icon-feather-arrow-right-circle text-base-color icon-small me-10px"></i>
                                <span class="fw-500 text-dark-gray">{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif

                @if($buttonText)
                    <a href="{{ $buttonUrl }}" class="btn btn-large btn-dark-gray btn-rounded btn-box-shadow">
                        {{ $buttonText }}<i class="fa-solid fa-arrow-right ms-10px"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>

<style>
    .text-base-color {
        color: #0039e3;
    }
    .text-outline-color-base-color {
        -webkit-text-stroke-color: #0039e3;
    }
</style>
