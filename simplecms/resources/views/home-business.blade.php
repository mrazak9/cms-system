@extends('frontend.layouts.crafto')

@section('title', 'Home - ' . ($settings['site_name'] ?? 'SimpleCMS'))

@push('styles')
{{-- Business Demo Specific CSS --}}
<link rel="stylesheet" href="{{ asset('crafto/demos/business/business.css') }}" />
@endpush

@section('content')
{{-- Hero Slider Section --}}
<section class="section-dark p-0 bg-dark-gray">
    <div class="swiper lg-no-parallax magic-cursor  full-screen md-h-600px sm-h-500px ipad-top-space-margin swiper-light-pagination" data-slider-options='{ "slidesPerView": 1, "loop": true, "parallax": true, "speed": 1000, "pagination": { "el": ".swiper-pagination-bullets", "clickable": true }, "autoplay": { "delay": 4000, "disableOnInteraction": false },  "keyboard": { "enabled": true, "onlyInViewport": true }, "effect": "slide" }'>
        <div class="swiper-wrapper">
            {{-- Slider Item 1 --}}
            <div class="swiper-slide overflow-hidden">
                <div class="cover-background position-absolute top-0 start-0 w-100 h-100" data-swiper-parallax="500" style="background-image:url('{{ asset('crafto/images/demo-business-slider-bg-01.jpg') }}');">
                    <div class="opacity-light bg-gradient-sherpa-blue-black"></div>
                    <div class="container h-100" data-swiper-parallax="-500">
                        <div class="row align-items-center h-100">
                            <div class="col-xl-7 col-lg-8 col-md-10 position-relative text-white text-center text-md-start">
                                <span class="fs-20 opacity-6 mb-25px sm-mb-15px d-inline-block fw-300">{{ $themeSettings['hero_slide_1_subtitle'] ?? 'Best solutions for your business' }}</span>
                                <h1 class="alt-font w-90 xl-w-100 text-shadow-double-large ls-minus-2px">{{ str_replace($themeSettings['hero_slide_1_highlight'] ?? 'great business', '<span class="fw-600">' . ($themeSettings['hero_slide_1_highlight'] ?? 'great business') . '</span>', $themeSettings['hero_slide_1_title'] ?? 'Agency for your great business') }}</h1>
                                <a href="{{ route('blog.index') }}" class="btn btn-extra-large btn-rounded with-rounded btn-base-color btn-box-shadow box-shadow-extra-large mt-20px sm-mt-0">Get started now<span class="bg-white text-base-color"><i class="fas fa-arrow-right"></i></span></a>
                            </div>
                        </div>
                        <div class="position-absolute bottom-minus-45px"><span class="alt-font number text-base-color opacity-3 fs-190 fw-600 ls-minus-5px">01</span></div>
                    </div>
                </div>
            </div>

            {{-- Slider Item 2 --}}
            <div class="swiper-slide overflow-hidden">
                <div class="cover-background position-absolute top-0 start-0 w-100 h-100" data-swiper-parallax="500" style="background-image:url('{{ asset('crafto/images/demo-business-slider-bg-02.jpg') }}');">
                    <div class="opacity-light bg-gradient-sherpa-blue-black"></div>
                    <div class="container h-100" data-swiper-parallax="-500">
                        <div class="row align-items-center h-100">
                            <div class="col-xl-7 col-lg-8 col-md-10 position-relative text-white text-center text-md-start">
                                <span class="fs-20 opacity-6 mb-25px sm-mb-15px d-inline-block fw-300">{{ $themeSettings['hero_slide_2_subtitle'] ?? 'Delivering beautiful digital products' }}</span>
                                <h1 class="alt-font w-90 xl-w-100 text-shadow-double-large ls-minus-2px">{!! str_replace($themeSettings['hero_slide_2_highlight'] ?? 'marketing', '<span class="fw-600">' . ($themeSettings['hero_slide_2_highlight'] ?? 'marketing') . '</span>', $themeSettings['hero_slide_2_title'] ?? 'Shape the future of marketing') !!}</h1>
                                <a href="{{ route('blog.index') }}" class="btn btn-extra-large btn-rounded with-rounded btn-base-color btn-box-shadow box-shadow-extra-large mt-20px sm-mt-0">Get started now<span class="bg-white text-base-color"><i class="fa-solid fa-arrow-right"></i></span></a>
                            </div>
                            <div class="position-absolute bottom-minus-45px"><span class="alt-font number text-base-color opacity-3 fs-190 fw-600 ls-minus-5px">02</span></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Slider Item 3 --}}
            <div class="swiper-slide overflow-hidden">
                <div class="cover-background position-absolute top-0 start-0 w-100 h-100" data-swiper-parallax="500" style="background-image:url('{{ asset('crafto/images/demo-business-slider-bg-03.jpg') }}');">
                    <div class="opacity-light bg-gradient-sherpa-blue-black"></div>
                    <div class="container h-100" data-swiper-parallax="-500">
                        <div class="row align-items-center h-100">
                            <div class="col-xl-7 col-lg-8 col-md-10 position-relative text-white text-center text-md-start">
                                <span class="fs-20 opacity-6 mb-25px sm-mb-15px d-inline-block fw-300">{{ $themeSettings['hero_slide_3_subtitle'] ?? 'Business strategies and top ideas' }}</span>
                                <h1 class="alt-font w-90 xl-w-100 text-shadow-double-large ls-minus-2px">{!! str_replace($themeSettings['hero_slide_3_highlight'] ?? 'small business', '<span class="fw-600">' . ($themeSettings['hero_slide_3_highlight'] ?? 'small business') . '</span>', $themeSettings['hero_slide_3_title'] ?? 'Provide solutions to small business') !!}</h1>
                                <a href="{{ route('blog.index') }}" class="btn btn-extra-large btn-rounded with-rounded btn-base-color btn-box-shadow box-shadow-extra-large mt-20px sm-mt-0">Get started now<span class="bg-white text-base-color"><i class="fa-solid fa-arrow-right"></i></span></a>
                            </div>
                            <div class="position-absolute bottom-minus-45px"><span class="alt-font number text-base-color opacity-3 fs-190 fw-600 ls-minus-5px">03</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="swiper-pagination swiper-pagination-clickable swiper-pagination-bullets"></div>
    </div>
</section>

{{-- Features Bar Section --}}
<section class="border-bottom border-color-extra-medium-gray pt-40px pb-40px overflow-hidden">
    <div class="container">
        <div class="row row-cols-1 row-cols-lg-4 row-cols-sm-2 justify-content-center align-items-center">
            <div class="col icon-with-text-style-08 md-mb-30px text-center text-sm-start">
                <div class="feature-box feature-box-left-icon-middle d-inline-flex align-middle">
                    <div class="feature-box-icon me-10px">
                        <i class="bi bi-shield-check icon-very-medium text-base-color"></i>
                    </div>
                    <div class="feature-box-content">
                        <span class="alt-font fw-500 text-dark-gray d-block lh-26">World-class services</span>
                    </div>
                </div>
            </div>
            <div class="col icon-with-text-style-08 md-mb-30px text-center text-sm-start">
                <div class="feature-box feature-box-left-icon-middle d-inline-flex align-middle">
                    <div class="feature-box-icon me-10px">
                        <i class="bi bi-hourglass icon-very-medium text-base-color"></i>
                    </div>
                    <div class="feature-box-content">
                        <span class="alt-font fw-500 text-dark-gray d-block lh-26">Experience strategy</span>
                    </div>
                </div>
            </div>
            <div class="col icon-with-text-style-08 xs-mb-30px text-center text-sm-start">
                <div class="feature-box feature-box-left-icon-middle d-inline-flex align-middle">
                    <div class="feature-box-icon me-10px">
                        <i class="bi bi-award icon-very-medium text-base-color"></i>
                    </div>
                    <div class="feature-box-content">
                        <span class="alt-font fw-500 text-dark-gray d-block lh-26">Award winning agency</span>
                    </div>
                </div>
            </div>
            <div class="col icon-with-text-style-08 text-center text-sm-start">
                <div class="feature-box feature-box-left-icon-middle d-inline-flex align-middle">
                    <div class="feature-box-icon me-10px">
                        <i class="bi bi-briefcase icon-very-medium text-base-color"></i>
                    </div>
                    <div class="feature-box-content">
                        <span class="alt-font fw-500 text-dark-gray d-block lh-26">Grow your business</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Services Section --}}
<section>
    <div class="container">
        <div class="row justify-content-center mb-3">
            <div class="col-lg-7 col-md-9 text-center">
                <span class="fs-16 text-base-color fw-600 ls-1px text-uppercase mb-10px d-block">What we offer</span>
                <h3 class="alt-font text-dark-gray fw-600 ls-minus-1px">Business services</h3>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-lg-3 row-cols-md-2 justify-content-center" data-anime='{ "el": "childs", "translateY": [30, 0], "opacity": [0,1], "duration": 600, "delay": 0, "staggervalue": 200, "easing": "easeOutQuad" }'>
            {{-- Service 1 --}}
            <div class="col mb-30px">
                <div class="services-box-style-01 hover-box h-100 transition-inner-all">
                    <div class="position-relative box-image overflow-hidden border-radius-4px">
                        <img src="{{ asset('crafto/images/demo-business-services-01.jpg') }}" alt="">
                        <div class="box-overlay bg-gradient-base-color-transparent"></div>
                        <span class="d-flex justify-content-center align-items-center mx-auto icon-box absolute-middle-center z-index-1 w-90px h-90px rounded-circle bg-white box-shadow-quadruple-large">
                            <i class="feather icon-feather-briefcase icon-extra-large text-base-color"></i>
                        </span>
                    </div>
                    <div class="p-35px lg-p-25px last-paragraph-no-margin">
                        <span class="d-inline-block alt-font text-dark-gray mb-5px fw-600 fs-18">Business planning</span>
                        <p>Build strategies that grow your business and achieve success.</p>
                        <div class="mt-15px">
                            <i class="feather icon-feather-arrow-right icon-small text-base-color"></i>
                            <span class="text-decoration-line-bottom text-base-color fw-600">Explore services</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Service 2 --}}
            <div class="col mb-30px">
                <div class="services-box-style-01 hover-box h-100 transition-inner-all">
                    <div class="position-relative box-image overflow-hidden border-radius-4px">
                        <img src="{{ asset('crafto/images/demo-business-services-02.jpg') }}" alt="">
                        <div class="box-overlay bg-gradient-base-color-transparent"></div>
                        <span class="d-flex justify-content-center align-items-center mx-auto icon-box absolute-middle-center z-index-1 w-90px h-90px rounded-circle bg-white box-shadow-quadruple-large">
                            <i class="feather icon-feather-bar-chart-2 icon-extra-large text-base-color"></i>
                        </span>
                    </div>
                    <div class="p-35px lg-p-25px last-paragraph-no-margin">
                        <span class="d-inline-block alt-font text-dark-gray mb-5px fw-600 fs-18">Market research</span>
                        <p>Understand your market and make informed decisions.</p>
                        <div class="mt-15px">
                            <i class="feather icon-feather-arrow-right icon-small text-base-color"></i>
                            <span class="text-decoration-line-bottom text-base-color fw-600">Explore services</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Service 3 --}}
            <div class="col mb-30px">
                <div class="services-box-style-01 hover-box h-100 transition-inner-all">
                    <div class="position-relative box-image overflow-hidden border-radius-4px">
                        <img src="{{ asset('crafto/images/demo-business-services-03.jpg') }}" alt="">
                        <div class="box-overlay bg-gradient-base-color-transparent"></div>
                        <span class="d-flex justify-content-center align-items-center mx-auto icon-box absolute-middle-center z-index-1 w-90px h-90px rounded-circle bg-white box-shadow-quadruple-large">
                            <i class="feather icon-feather-globe icon-extra-large text-base-color"></i>
                        </span>
                    </div>
                    <div class="p-35px lg-p-25px last-paragraph-no-margin">
                        <span class="d-inline-block alt-font text-dark-gray mb-5px fw-600 fs-18">Digital solutions</span>
                        <p>Transform your business with cutting-edge technology.</p>
                        <div class="mt-15px">
                            <i class="feather icon-feather-arrow-right icon-small text-base-color"></i>
                            <span class="text-decoration-line-bottom text-base-color fw-600">Explore services</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Latest Posts Section --}}
@if($posts && $posts->count() > 0)
<section class="bg-very-light-gray">
    <div class="container">
        <div class="row justify-content-center mb-3">
            <div class="col-lg-7 col-md-9 text-center">
                <span class="fs-16 text-base-color fw-600 ls-1px text-uppercase mb-10px d-block">Our insights</span>
                <h3 class="alt-font text-dark-gray fw-600 ls-minus-1px">Latest from blog</h3>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-lg-3 row-cols-md-2 justify-content-center">
            @foreach($posts->take(3) as $post)
                <div class="col mb-30px">
                    <div class="card border-0 border-radius-5px box-shadow-quadruple-large">
                        @if($post->featured_image)
                            <div class="blog-image">
                                <a href="{{ route('blog.show', $post->slug) }}">
                                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-100 border-radius-5px" style="height: 250px; object-fit: cover;">
                                </a>
                            </div>
                        @endif
                        <div class="card-body p-35px lg-p-25px">
                            <a href="{{ route('blog.show', $post->slug) }}" class="card-title mb-15px fw-600 text-dark-gray d-inline-block">{{ $post->title }}</a>
                            <p class="mb-20px">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                            <div class="d-flex align-items-center">
                                <span class="fs-14 text-uppercase text-medium-gray">{{ $post->published_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA Section --}}
<section class="bg-base-color py-0">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8 text-center text-lg-start">
                <h4 class="text-white fw-600 ls-minus-1px mb-0">Ready to grow your business with us?</h4>
            </div>
            <div class="col-lg-4 text-center text-lg-end mt-20px mt-lg-0">
                <a href="{{ route('blog.index') }}" class="btn btn-extra-large btn-white btn-rounded btn-box-shadow">Get started now</a>
            </div>
        </div>
    </div>
</section>
@endsection
