@extends('frontend.layouts.crafto')

@section('title', 'Home - ' . config('app.name'))

@section('content')
{{-- Hero Section --}}
<section class="cover-background page-title-big-typography ipad-top-space-margin" style="background-image: url({{ asset('crafto/images/demo-corporate-slider-bg.jpg') }})">
    <div class="opacity-light bg-gradient-dark-transparent"></div>
    <div class="container">
        <div class="row align-items-center justify-content-center extra-very-small-screen">
            <div class="col-xxl-6 col-xl-7 col-lg-8 col-md-10 position-relative text-center page-title-extra-large">
                <h1 class="text-white fw-700 mb-20px ls-minus-2px">Welcome to {{ config('app.name') }}</h1>
                <h2 class="text-white fw-400 ls-minus-05px mb-35px">A powerful and flexible content management system</h2>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-extra-large btn-yellow btn-rounded btn-box-shadow me-20px xs-me-0">Get Started<i class="fa-solid fa-arrow-right ms-10px"></i></a>
                    <a href="{{ route('login') }}" class="btn btn-extra-large btn-transparent-white-light btn-rounded">Login</a>
                @else
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-extra-large btn-yellow btn-rounded btn-box-shadow">Go to Dashboard<i class="fa-solid fa-arrow-right ms-10px"></i></a>
                @endguest
            </div>
        </div>
    </div>
</section>

{{-- Features Section --}}
<section class="bg-very-light-gray">
    <div class="container">
        <div class="row row-cols-1 row-cols-lg-3 row-cols-md-2 justify-content-center mb-6" data-anime='{ "el": "childs", "translateY": [50, 0], "opacity": [0,1], "duration": 1200, "delay": 0, "staggervalue": 150, "easing": "easeOutQuad" }'>
            {{-- Feature Item 1 --}}
            <div class="col icon-with-text-style-08 transition-inner-all mb-30px">
                <div class="feature-box feature-box-left-icon-middle bg-white border-radius-6px overflow-hidden box-shadow-quadruple-large box-shadow-quadruple-large-hover">
                    <div class="feature-box-icon">
                        <i class="line-icon-File-Edit icon-extra-large text-base-color"></i>
                    </div>
                    <div class="feature-box-content">
                        <span class="d-inline-block fs-19 fw-600 text-dark-gray mb-5px">Pages & Posts</span>
                        <p>Create and manage unlimited pages and blog posts with rich content editor.</p>
                    </div>
                    <div class="feature-box-overlay bg-base-color"></div>
                </div>
            </div>
            {{-- Feature Item 2 --}}
            <div class="col icon-with-text-style-08 transition-inner-all mb-30px">
                <div class="feature-box feature-box-left-icon-middle bg-white border-radius-6px overflow-hidden box-shadow-quadruple-large box-shadow-quadruple-large-hover">
                    <div class="feature-box-icon">
                        <i class="line-icon-Bar-Chart icon-extra-large text-base-color"></i>
                    </div>
                    <div class="feature-box-content">
                        <span class="d-inline-block fs-19 fw-600 text-dark-gray mb-5px">Menu Management</span>
                        <p>Build custom navigation menus with drag & drop interface and nested support.</p>
                    </div>
                    <div class="feature-box-overlay bg-base-color"></div>
                </div>
            </div>
            {{-- Feature Item 3 --}}
            <div class="col icon-with-text-style-08 transition-inner-all mb-30px">
                <div class="feature-box feature-box-left-icon-middle bg-white border-radius-6px overflow-hidden box-shadow-quadruple-large box-shadow-quadruple-large-hover">
                    <div class="feature-box-icon">
                        <i class="line-icon-Photo-Album icon-extra-large text-base-color"></i>
                    </div>
                    <div class="feature-box-content">
                        <span class="d-inline-block fs-19 fw-600 text-dark-gray mb-5px">Media Library</span>
                        <p>Upload and organize your images and files with intuitive media manager.</p>
                    </div>
                    <div class="feature-box-overlay bg-base-color"></div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Latest Posts Section --}}
@if($posts && $posts->count() > 0)
<section>
    <div class="container">
        <div class="row mb-6">
            <div class="col-lg-7 text-center text-lg-start sm-mb-20px">
                <h3 class="fw-700 text-dark-gray ls-minus-2px">Latest Posts</h3>
            </div>
            <div class="col-lg-5 text-center text-lg-end">
                <a href="{{ route('blog.index') }}" class="btn btn-large btn-dark-gray btn-rounded btn-box-shadow">View All Posts<i class="fa-solid fa-arrow-right ms-10px"></i></a>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-lg-3 row-cols-md-2 justify-content-center" data-anime='{ "el": "childs", "translateY": [30, 0], "opacity": [0,1], "duration": 600, "delay": 0, "staggervalue": 200, "easing": "easeOutQuad" }'>
            @foreach($posts->take(3) as $post)
                <div class="col mb-30px">
                    {{-- Blog post item --}}
                    <div class="card border-0 border-radius-5px box-shadow-quadruple-large box-shadow-quadruple-large-hover">
                        @if($post->featured_image)
                            <div class="blog-image position-relative overflow-hidden border-radius-5px">
                                <a href="{{ route('blog.show', $post->slug) }}">
                                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-100" style="height: 250px; object-fit: cover;">
                                </a>
                                @if($post->category)
                                    <div class="blog-categories">
                                        <a href="{{ route('blog.category', $post->category->slug) }}" class="categories-btn bg-yellow text-dark-gray text-uppercase fw-700">{{ $post->category->name }}</a>
                                    </div>
                                @endif
                            </div>
                        @endif
                        <div class="card-body p-35px lg-p-25px">
                            <a href="{{ route('blog.show', $post->slug) }}" class="card-title mb-15px fw-700 lh-32 text-dark-gray d-inline-block">{{ $post->title }}</a>
                            <p class="mb-20px">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="author d-flex align-items-center">
                                    @if($post->author)
                                        <div class="avatar-sm bg-yellow text-dark-gray rounded-circle d-flex align-items-center justify-content-center me-10px" style="width: 40px; height: 40px; font-weight: 700;">
                                            {{ substr($post->author->name, 0, 1) }}
                                        </div>
                                        <span class="text-dark-gray fs-15 fw-600">{{ $post->author->name }}</span>
                                    @endif
                                </div>
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
        <div class="row justify-content-center align-items-center py-5">
            <div class="col-lg-6 col-md-8 text-center text-lg-start sm-mb-20px">
                <h4 class="text-white fw-600 ls-minus-1px mb-0">Ready to get started with SimpleCMS?</h4>
            </div>
            <div class="col-lg-6 col-md-4 text-center text-lg-end">
                @guest
                    <a href="{{ route('register') }}" class="btn btn-extra-large btn-white btn-rounded btn-box-shadow">Get Started Now</a>
                @else
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-extra-large btn-white btn-rounded btn-box-shadow">Go to Dashboard</a>
                @endguest
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .bg-base-color {
        background-color: #0039e3;
    }
    .text-base-color {
        color: #0039e3;
    }
    .btn-yellow {
        background-color: #ffb822;
        color: #232323;
    }
    .btn-yellow:hover {
        background-color: #e6a520;
        color: #232323;
    }
    .feature-box-overlay {
        background: linear-gradient(to right, #0039e3, #4132e0);
    }
</style>
@endpush
