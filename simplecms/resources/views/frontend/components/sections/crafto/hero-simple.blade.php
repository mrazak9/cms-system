{{-- Hero Simple Component --}}
@props([
    'section' => null,
    'content' => []
])

@php
    $title = $content['title'] ?? 'Welcome to Our Website';
    $subtitle = $content['subtitle'] ?? 'Build Amazing Experiences';
    $description = $content['description'] ?? '';
    $buttonText = $content['button_text'] ?? 'Get Started';
    $buttonUrl = $content['button_url'] ?? '#';
    $button2Text = $content['button_2_text'] ?? '';
    $button2Url = $content['button_2_url'] ?? '#';
    $backgroundImage = $content['background_image'] ?? asset('crafto/images/demo-corporate-slider-bg.jpg');
    $textColor = $content['text_color'] ?? '#ffffff';
    $overlayOpacity = $content['overlay_opacity'] ?? '0.5';
    $height = $content['height'] ?? 'default'; // default, small, large

    // Height classes
    $heightClass = match($height) {
        'small' => 'py-8',
        'large' => 'extra-very-small-screen',
        default => 'ipad-top-space-margin'
    };
@endphp

<section class="cover-background page-title-big-typography {{ $heightClass }}" style="background-image: url({{ $backgroundImage }});">
    <div class="opacity-light bg-gradient-dark-transparent" style="opacity: {{ $overlayOpacity }};"></div>
    <div class="container">
        <div class="row align-items-center justify-content-center extra-very-small-screen">
            <div class="col-xxl-6 col-xl-7 col-lg-8 col-md-10 position-relative text-center page-title-extra-large" style="color: {{ $textColor }};">
                @if($subtitle)
                    <h2 class="fw-400 ls-minus-05px mb-20px" style="color: {{ $textColor }};">{{ $subtitle }}</h2>
                @endif

                <h1 class="fw-700 mb-20px ls-minus-2px" style="color: {{ $textColor }};">{{ $title }}</h1>

                @if($description)
                    <p class="fs-20 lh-32 w-70 xl-w-80 lg-w-90 md-w-100 mx-auto mb-35px" style="color: {{ $textColor }};">{{ $description }}</p>
                @endif

                <div class="d-inline-block">
                    @if($buttonText)
                        <a href="{{ $buttonUrl }}" class="btn btn-extra-large btn-yellow btn-rounded btn-box-shadow me-20px xs-me-0 xs-mb-15px">
                            {{ $buttonText }}<i class="fa-solid fa-arrow-right ms-10px"></i>
                        </a>
                    @endif

                    @if($button2Text)
                        <a href="{{ $button2Url }}" class="btn btn-extra-large btn-transparent-white-light btn-rounded">
                            {{ $button2Text }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .btn-yellow {
        background-color: #ffb822;
        color: #232323;
        border: none;
    }
    .btn-yellow:hover {
        background-color: #e6a520;
        color: #232323;
    }
</style>
