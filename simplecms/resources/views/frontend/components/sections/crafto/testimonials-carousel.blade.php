{{-- Testimonials Carousel Component --}}
@props([
    'section' => null,
    'content' => []
])

@php
    $heading = $content['heading'] ?? 'What Our Clients Say';
    $subheading = $content['subheading'] ?? '';
    $description = $content['description'] ?? '';
    $testimonials = $content['testimonials'] ?? [];
    $backgroundColor = $content['background_color'] ?? '#f7f7f7';
    $layout = $content['layout'] ?? 'carousel'; // carousel or grid
    $columns = $content['columns'] ?? 3; // for grid layout

    // Generate unique ID for carousel
    $carouselId = 'testimonials-' . ($section->id ?? uniqid());

    // Default testimonials if none provided
    if (empty($testimonials)) {
        $testimonials = [
            [
                'name' => 'John Anderson',
                'position' => 'CEO, TechCorp',
                'image' => asset('crafto/images/avatar-1.jpg'),
                'rating' => 5,
                'text' => 'Outstanding service and exceptional quality. The team exceeded our expectations and delivered results beyond what we imagined. Highly recommended for anyone looking for professional solutions.'
            ],
            [
                'name' => 'Sarah Miller',
                'position' => 'Marketing Director, StartupXYZ',
                'image' => asset('crafto/images/avatar-2.jpg'),
                'rating' => 5,
                'text' => 'Working with this team has been an absolute pleasure. Their attention to detail and commitment to excellence is remarkable. They truly understand client needs and deliver accordingly.'
            ],
            [
                'name' => 'David Chen',
                'position' => 'Founder, InnovateLabs',
                'image' => asset('crafto/images/avatar-3.jpg'),
                'rating' => 5,
                'text' => 'Professional, creative, and reliable. They transformed our vision into reality with their innovative approach and technical expertise. Best decision we made for our business.'
            ]
        ];
    }
@endphp

<section style="background-color: {{ $backgroundColor }};">
    <div class="container">
        @if($heading || $subheading || $description)
            <div class="row justify-content-center mb-5">
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

        @if($layout === 'carousel')
            {{-- Carousel Layout --}}
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="swiper slider-one-slide" data-slider-options='{ "slidesPerView": 1, "spaceBetween": 30, "loop": true, "autoplay": { "delay": 4000, "disableOnInteraction": false }, "pagination": { "el": ".slider-one-slide-pagination-{{ $carouselId }}", "clickable": true }, "navigation": { "nextEl": ".slider-one-slide-next-{{ $carouselId }}", "prevEl": ".slider-one-slide-prev-{{ $carouselId }}" }, "breakpoints": { "992": { "slidesPerView": 2 }, "1200": { "slidesPerView": 3 } }, "keyboard": { "enabled": true, "onlyInViewport": true }, "effect": "slide" }'>
                        <div class="swiper-wrapper">
                            @foreach($testimonials as $testimonial)
                                <div class="swiper-slide">
                                    <div class="testimonials-style-01 bg-white border-radius-6px overflow-hidden box-shadow-quadruple-large p-40px lg-p-30px">
                                        @if(isset($testimonial['rating']) && $testimonial['rating'] > 0)
                                            <div class="mb-20px">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fa-{{ $i <= $testimonial['rating'] ? 'solid' : 'regular' }} fa-star text-yellow fs-18"></i>
                                                @endfor
                                            </div>
                                        @endif
                                        <p class="mb-25px lh-32">{{ $testimonial['text'] }}</p>
                                        <div class="d-flex align-items-center">
                                            @if(isset($testimonial['image']))
                                                <img src="{{ $testimonial['image'] }}" alt="{{ $testimonial['name'] }}" class="rounded-circle me-15px" style="width: 60px; height: 60px; object-fit: cover;" />
                                            @else
                                                <div class="bg-gradient-base-color rounded-circle me-15px d-flex align-items-center justify-content-center text-white fw-600 fs-20" style="width: 60px; height: 60px;">
                                                    {{ substr($testimonial['name'], 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <span class="d-block fw-600 text-dark-gray lh-22">{{ $testimonial['name'] }}</span>
                                                <span class="fs-15 lh-22 text-medium-gray">{{ $testimonial['position'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        {{-- Pagination --}}
                        <div class="swiper-pagination slider-one-slide-pagination-{{ $carouselId }} swiper-pagination-style-2 swiper-pagination-clickable mt-40px"></div>
                    </div>
                </div>
            </div>
        @else
            {{-- Grid Layout --}}
            @php
                $columnClass = match($columns) {
                    2 => 'row-cols-lg-2',
                    4 => 'row-cols-lg-4',
                    default => 'row-cols-lg-3'
                };
            @endphp
            <div class="row row-cols-1 {{ $columnClass }} row-cols-md-2 justify-content-center" data-anime='{ "el": "childs", "translateY": [30, 0], "opacity": [0,1], "duration": 600, "delay": 0, "staggervalue": 200, "easing": "easeOutQuad" }'>
                @foreach($testimonials as $testimonial)
                    <div class="col mb-30px">
                        <div class="testimonials-style-01 bg-white border-radius-6px overflow-hidden box-shadow-quadruple-large p-40px lg-p-30px h-100">
                            @if(isset($testimonial['rating']) && $testimonial['rating'] > 0)
                                <div class="mb-20px">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa-{{ $i <= $testimonial['rating'] ? 'solid' : 'regular' }} fa-star text-yellow fs-18"></i>
                                    @endfor
                                </div>
                            @endif
                            <p class="mb-25px lh-32">{{ $testimonial['text'] }}</p>
                            <div class="d-flex align-items-center">
                                @if(isset($testimonial['image']))
                                    <img src="{{ $testimonial['image'] }}" alt="{{ $testimonial['name'] }}" class="rounded-circle me-15px" style="width: 60px; height: 60px; object-fit: cover;" />
                                @else
                                    <div class="bg-gradient-base-color rounded-circle me-15px d-flex align-items-center justify-content-center text-white fw-600 fs-20" style="width: 60px; height: 60px;">
                                        {{ substr($testimonial['name'], 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <span class="d-block fw-600 text-dark-gray lh-22">{{ $testimonial['name'] }}</span>
                                    <span class="fs-15 lh-22 text-medium-gray">{{ $testimonial['position'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<style>
    .text-base-color {
        color: #0039e3;
    }
    .text-yellow {
        color: #ffb822;
    }
    .bg-gradient-base-color {
        background: linear-gradient(135deg, #0039e3 0%, #4132e0 100%);
    }
</style>
