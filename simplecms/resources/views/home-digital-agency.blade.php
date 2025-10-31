@extends('frontend.layouts.crafto')

@section('title', 'Home - ' . ($settings['site_name'] ?? 'SimpleCMS'))

@push('styles')
{{-- Digital Agency Demo Specific CSS --}}
<link rel="stylesheet" href="{{ asset('crafto/demos/digital-agency/digital-agency.css') }}" />
@endpush

@section('content')
{{-- Hero Section --}}
<section class="p-0 full-screen md-h-600px sm-h-500px ipad-top-space-margin position-relative bg-gradient-quartz-white-transparent">
    <div class="container h-100">
        <div class="row align-items-center h-100">
            <div class="col-lg-6 col-md-8 position-relative z-index-1 md-mb-30px">
                <h1 class="alt-font text-dark-gray fw-700 ls-minus-4px mb-30px">Creative digital <span class="text-highlight">agency<span class="bg-base-color h-10px opacity-2 separator-animation"></span></span></h1>
                <p class="w-85 lg-w-100 mb-35px fs-20 lh-34">We create unique digital experiences that inspire and engage your audience.</p>
                <a href="{{ route('blog.index') }}" class="btn btn-large btn-dark-gray btn-rounded btn-box-shadow">View our work<i class="fa-solid fa-arrow-right ms-10px"></i></a>
            </div>
            <div class="col-lg-6 position-relative">
                <div class="outside-box-right-15 xl-outside-box-right-20 sm-outside-box-right-0">
                    <img src="{{ asset('crafto/images/demo-digital-agency-home-img.png') }}" alt="" class="w-100" data-bottom-top="transform: translateY(-50px)" data-top-bottom="transform: translateY(50px)">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Services Section --}}
<section>
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-lg-7 text-center">
                <span class="fs-16 text-base-color fw-600 ls-1px text-uppercase d-block mb-10px">What we do</span>
                <h3 class="alt-font text-dark-gray fw-700 ls-minus-2px">Our expertise</h3>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-lg-3 row-cols-md-2 g-4">
            {{-- Service 1 --}}
            <div class="col">
                <div class="icon-with-text-style-02 transition-inner-all">
                    <div class="feature-box bg-white border-radius-6px p-18 box-shadow-small h-100">
                        <div class="feature-box-icon mb-20px">
                            <i class="line-icon-Tablet icon-extra-large text-base-color"></i>
                        </div>
                        <div class="feature-box-content">
                            <span class="d-block alt-font text-dark-gray fw-600 fs-19 mb-5px">Web design</span>
                            <p class="mb-0">Beautiful and functional websites that convert visitors into customers.</p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Service 2 --}}
            <div class="col">
                <div class="icon-with-text-style-02 transition-inner-all">
                    <div class="feature-box bg-white border-radius-6px p-18 box-shadow-small h-100">
                        <div class="feature-box-icon mb-20px">
                            <i class="line-icon-Fountain-Pen icon-extra-large text-base-color"></i>
                        </div>
                        <div class="feature-box-content">
                            <span class="d-block alt-font text-dark-gray fw-600 fs-19 mb-5px">Branding</span>
                            <p class="mb-0">Create memorable brand identities that resonate with your audience.</p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Service 3 --}}
            <div class="col">
                <div class="icon-with-text-style-02 transition-inner-all">
                    <div class="feature-box bg-white border-radius-6px p-18 box-shadow-small h-100">
                        <div class="feature-box-icon mb-20px">
                            <i class="line-icon-Video-5 icon-extra-large text-base-color"></i>
                        </div>
                        <div class="feature-box-content">
                            <span class="d-block alt-font text-dark-gray fw-600 fs-19 mb-5px">Digital marketing</span>
                            <p class="mb-0">Drive growth with data-driven marketing strategies and campaigns.</p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Service 4 --}}
            <div class="col">
                <div class="icon-with-text-style-02 transition-inner-all">
                    <div class="feature-box bg-white border-radius-6px p-18 box-shadow-small h-100">
                        <div class="feature-box-icon mb-20px">
                            <i class="line-icon-Mobile icon-extra-large text-base-color"></i>
                        </div>
                        <div class="feature-box-content">
                            <span class="d-block alt-font text-dark-gray fw-600 fs-19 mb-5px">App development</span>
                            <p class="mb-0">Build powerful mobile and web applications for your business.</p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Service 5 --}}
            <div class="col">
                <div class="icon-with-text-style-02 transition-inner-all">
                    <div class="feature-box bg-white border-radius-6px p-18 box-shadow-small h-100">
                        <div class="feature-box-icon mb-20px">
                            <i class="line-icon-Idea-5 icon-extra-large text-base-color"></i>
                        </div>
                        <div class="feature-box-content">
                            <span class="d-block alt-font text-dark-gray fw-600 fs-19 mb-5px">Strategy</span>
                            <p class="mb-0">Develop winning strategies that align with your business goals.</p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Service 6 --}}
            <div class="col">
                <div class="icon-with-text-style-02 transition-inner-all">
                    <div class="feature-box bg-white border-radius-6px p-18 box-shadow-small h-100">
                        <div class="feature-box-icon mb-20px">
                            <i class="line-icon-Pen icon-extra-large text-base-color"></i>
                        </div>
                        <div class="feature-box-content">
                            <span class="d-block alt-font text-dark-gray fw-600 fs-19 mb-5px">Content creation</span>
                            <p class="mb-0">Engaging content that tells your story and connects with people.</p>
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
            <div class="col-lg-7 text-center">
                <span class="fs-16 text-base-color fw-600 ls-1px text-uppercase d-block mb-10px">Our blog</span>
                <h3 class="alt-font text-dark-gray fw-700 ls-minus-2px">Latest articles</h3>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-lg-3 row-cols-md-2">
            @foreach($posts->take(3) as $post)
                <div class="col mb-30px">
                    <article class="card border-0 box-shadow-small h-100">
                        @if($post->featured_image)
                            <div class="position-relative">
                                <a href="{{ route('blog.show', $post->slug) }}">
                                    <img src="{{ asset('storage/' . $post->featured_image) }}" class="card-img-top border-radius-4px" alt="{{ $post->title }}" style="height: 220px; object-fit: cover;">
                                </a>
                            </div>
                        @endif
                        <div class="card-body p-30px">
                            <a href="{{ route('blog.show', $post->slug) }}" class="card-title d-block alt-font fw-600 text-dark-gray mb-10px fs-19">{{ $post->title }}</a>
                            <p class="mb-20px">{{ Str::limit(strip_tags($post->content), 90) }}</p>
                            <span class="text-base-color fs-13 text-uppercase fw-600">{{ $post->published_at->format('M d, Y') }}</span>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA Section --}}
<section class="bg-dark-gray">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 text-center text-lg-start mb-20px mb-lg-0">
                <h3 class="text-white fw-600 ls-minus-1px mb-0">Ready to start your next project?</h3>
            </div>
            <div class="col-lg-4 text-center text-lg-end">
                <a href="{{ route('blog.index') }}" class="btn btn-large btn-white btn-rounded">Let's talk</a>
            </div>
        </div>
    </div>
</section>
@endsection
