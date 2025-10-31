{{-- Services Cards Component --}}
@props([
    'section' => null,
    'content' => []
])

@php
    $heading = $content['heading'] ?? 'Our Services';
    $subheading = $content['subheading'] ?? '';
    $description = $content['description'] ?? '';
    $columns = $content['columns'] ?? 3; // 2, 3, or 4
    $services = $content['services'] ?? [];
    $backgroundColor = $content['background_color'] ?? '#ffffff';
    $animation = $content['animation'] ?? true;
    $cardStyle = $content['card_style'] ?? 'icon-top'; // icon-top, icon-left, number

    // Column classes
    $columnClass = match($columns) {
        2 => 'row-cols-lg-2',
        4 => 'row-cols-lg-4',
        default => 'row-cols-lg-3'
    };

    // Default services if none provided
    if (empty($services)) {
        $services = [
            [
                'icon' => 'line-icon-Coding',
                'number' => '01',
                'title' => 'Web Development',
                'description' => 'Build modern, responsive websites with the latest technologies.',
                'link' => '#',
                'link_text' => 'Learn more'
            ],
            [
                'icon' => 'line-icon-Smartphone-3',
                'number' => '02',
                'title' => 'Mobile Apps',
                'description' => 'Create native and cross-platform mobile applications.',
                'link' => '#',
                'link_text' => 'Learn more'
            ],
            [
                'icon' => 'line-icon-Rocket',
                'number' => '03',
                'title' => 'Digital Marketing',
                'description' => 'Grow your business with effective digital marketing strategies.',
                'link' => '#',
                'link_text' => 'Learn more'
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

        <div class="row row-cols-1 {{ $columnClass }} row-cols-md-2 justify-content-center"
            @if($animation)
            data-anime='{ "el": "childs", "translateY": [30, 0], "opacity": [0,1], "duration": 600, "delay": 0, "staggervalue": 200, "easing": "easeOutQuad" }'
            @endif>
            @foreach($services as $service)
                <div class="col mb-30px">
                    @if($cardStyle === 'number')
                        {{-- Number Style Card --}}
                        <div class="services-box-style-01 hover-box">
                            <div class="position-relative box-image border-radius-6px overflow-hidden">
                                @if(isset($service['image']))
                                    <img src="{{ $service['image'] }}" alt="{{ $service['title'] }}" />
                                @else
                                    <div class="bg-base-color" style="height: 300px;"></div>
                                @endif
                                <div class="box-overlay bg-gradient-base-color"></div>
                                <span class="d-flex fw-600 text-white fs-130 position-absolute left-30px bottom-30px opacity-2">{{ $service['number'] }}</span>
                            </div>
                            <div class="position-relative p-35px lg-p-25px bg-white box-shadow-quadruple-large">
                                <span class="d-inline-block fs-20 fw-600 text-dark-gray mb-5px">{{ $service['title'] }}</span>
                                <p class="mb-20px">{{ $service['description'] }}</p>
                                @if(isset($service['link']) && isset($service['link_text']))
                                    <a href="{{ $service['link'] }}" class="btn btn-link btn-hover-animation-switch btn-extra-large text-dark-gray p-0 text-uppercase-inherit">
                                        <span>
                                            <span class="btn-text">{{ $service['link_text'] }}</span>
                                            <span class="btn-icon"><i class="feather icon-feather-arrow-right"></i></span>
                                            <span class="btn-icon"><i class="feather icon-feather-arrow-right"></i></span>
                                        </span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @elseif($cardStyle === 'icon-left')
                        {{-- Icon Left Style Card --}}
                        <div class="services-box-style-02 bg-white border-radius-6px overflow-hidden box-shadow-quadruple-large">
                            <div class="d-flex">
                                <div class="flex-shrink-0 bg-base-color p-40px text-center">
                                    <i class="{{ $service['icon'] }} icon-extra-large text-white"></i>
                                </div>
                                <div class="p-35px lg-p-25px">
                                    <span class="d-inline-block fs-20 fw-600 text-dark-gray mb-10px">{{ $service['title'] }}</span>
                                    <p class="mb-20px">{{ $service['description'] }}</p>
                                    @if(isset($service['link']) && isset($service['link_text']))
                                        <a href="{{ $service['link'] }}" class="text-dark-gray text-decoration-line-bottom fw-600">{{ $service['link_text'] }} <i class="feather icon-feather-arrow-right ms-5px"></i></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Icon Top Style Card (Default) --}}
                        <div class="services-box-style-03 bg-white border-radius-6px overflow-hidden box-shadow-quadruple-large text-center hover-box p-40px lg-p-30px">
                            <div class="feature-box-icon mb-25px">
                                <i class="{{ $service['icon'] }} icon-extra-large text-base-color"></i>
                            </div>
                            <span class="d-inline-block fs-20 fw-600 text-dark-gray mb-15px">{{ $service['title'] }}</span>
                            <p class="mb-25px">{{ $service['description'] }}</p>
                            @if(isset($service['link']) && isset($service['link_text']))
                                <a href="{{ $service['link'] }}" class="btn btn-link btn-hover-animation-switch btn-extra-large text-dark-gray p-0">
                                    <span>
                                        <span class="btn-text">{{ $service['link_text'] }}</span>
                                        <span class="btn-icon"><i class="feather icon-feather-arrow-right"></i></span>
                                        <span class="btn-icon"><i class="feather icon-feather-arrow-right"></i></span>
                                    </span>
                                </a>
                            @endif
                        </div>
                    @endif
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
    .box-overlay.bg-gradient-base-color {
        background: linear-gradient(to bottom, rgba(0, 57, 227, 0.5), rgba(65, 50, 224, 0.8));
    }
</style>
