@extends('frontend.layouts.crafto')

@section('title', 'Home - ' . ($settings['site_name'] ?? 'SimpleCMS'))

@push('styles')
{{-- Corporate Demo Specific CSS --}}
<link rel="stylesheet" href="{{ asset('crafto/demos/corporate/corporate.css') }}" />
@endpush

@section('content')
{{-- Hero Section --}}
<section class="p-0 bg-gradient-very-light-gray-transparent full-screen md-h-600px sm-h-500px ipad-top-space-margin position-relative">
    <div class="container h-100">
        <div class="row align-items-center h-100">
            <div class="col-xl-6 col-lg-7 col-md-8 position-relative z-index-1 md-mb-30px">
                <span class="fs-18 fw-600 text-base-color text-uppercase mb-20px d-block">Corporate business solutions</span>
                <h1 class="alt-font text-dark-gray fw-700 ls-minus-3px mb-30px">Modern approach to <span class="text-highlight">business<span class="bg-base-color h-10px opacity-2 separator-animation"></span></span></h1>
                <p class="w-90 lg-w-100 mb-35px fs-19 lh-32">Powerful solutions for growing companies and enterprises.</p>
                <a href="{{ route('blog.index') }}" class="btn btn-extra-large btn-base-color btn-rounded btn-box-shadow">Discover more<i class="fa-solid fa-arrow-right ms-10px"></i></a>
            </div>
            <div class="col-xl-6 col-lg-5 text-center">
                <img src="{{ asset('crafto/images/demo-corporate-slider-img.png') }}" alt="" data-bottom-top="transform: translateY(50px)" data-top-bottom="transform: translateY(-50px)">
            </div>
        </div>
    </div>
</section>

{{-- Features Section --}}
<section class="bg-white">
    <div class="container">
        <div class="row justify-content-center mb-3">
            <div class="col-lg-7 text-center">
                <span class="fs-16 text-base-color fw-600 ls-1px text-uppercase d-block mb-10px">Our expertise</span>
                <h3 class="alt-font text-dark-gray fw-700 ls-minus-2px">Professional services</h3>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-lg-4 row-cols-md-2 justify-content-center">
            {{-- Feature 1 --}}
            <div class="col text-center mb-30px">
                <div class="feature-box p-18 border-radius-6px box-shadow-small">
                    <div class="feature-box-icon mb-20px">
                        <i class="line-icon-Bar-Chart icon-extra-large text-base-color"></i>
                    </div>
                    <div class="feature-box-content">
                        <span class="d-block alt-font text-dark-gray fw-600 fs-18 mb-5px">Strategic planning</span>
                        <p class="mb-0">Build long-term strategies for sustainable growth</p>
                    </div>
                </div>
            </div>
            {{-- Feature 2 --}}
            <div class="col text-center mb-30px">
                <div class="feature-box p-18 border-radius-6px box-shadow-small">
                    <div class="feature-box-icon mb-20px">
                        <i class="line-icon-Consulting icon-extra-large text-base-color"></i>
                    </div>
                    <div class="feature-box-content">
                        <span class="d-block alt-font text-dark-gray fw-600 fs-18 mb-5px">Business consulting</span>
                        <p class="mb-0">Expert guidance for your business challenges</p>
                    </div>
                </div>
            </div>
            {{-- Feature 3 --}}
            <div class="col text-center mb-30px">
                <div class="feature-box p-18 border-radius-6px box-shadow-small">
                    <div class="feature-box-icon mb-20px">
                        <i class="line-icon-Target icon-extra-large text-base-color"></i>
                    </div>
                    <div class="feature-box-content">
                        <span class="d-block alt-font text-dark-gray fw-600 fs-18 mb-5px">Market analysis</span>
                        <p class="mb-0">Data-driven insights for better decisions</p>
                    </div>
                </div>
            </div>
            {{-- Feature 4 --}}
            <div class="col text-center mb-30px">
                <div class="feature-box p-18 border-radius-6px box-shadow-small">
                    <div class="feature-box-icon mb-20px">
                        <i class="line-icon-Gear-2 icon-extra-large text-base-color"></i>
                    </div>
                    <div class="feature-box-content">
                        <span class="d-block alt-font text-dark-gray fw-600 fs-18 mb-5px">Process optimization</span>
                        <p class="mb-0">Streamline operations for maximum efficiency</p>
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
            <div class="col-lg-7 text-center">
                <span class="fs-16 text-base-color fw-600 ls-1px text-uppercase d-block mb-10px">Latest insights</span>
                <h3 class="alt-font text-dark-gray fw-700 ls-minus-2px">From our blog</h3>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-lg-3 row-cols-md-2">
            @foreach($posts->take(3) as $post)
                <div class="col mb-30px">
                    <div class="card border-0 h-100 box-shadow-small">
                        @if($post->featured_image)
                            <img src="{{ asset('storage/' . $post->featured_image) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 220px; object-fit: cover;">
                        @endif
                        <div class="card-body p-30px">
                            <span class="text-base-color fs-14 mb-10px d-inline-block">{{ $post->published_at->format('M d, Y') }}</span>
                            <a href="{{ route('blog.show', $post->slug) }}" class="card-title d-block fw-600 text-dark-gray mb-15px">{{ $post->title }}</a>
                            <p class="mb-0">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA Section --}}
<section class="bg-base-color">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 text-center text-lg-start mb-20px mb-lg-0">
                <h3 class="text-white fw-600 mb-0">Let's work together on your next project</h3>
            </div>
            <div class="col-lg-4 text-center text-lg-end">
                <a href="{{ route('blog.index') }}" class="btn btn-extra-large btn-white btn-rounded">Contact us</a>
            </div>
        </div>
    </div>
</section>
@endsection
