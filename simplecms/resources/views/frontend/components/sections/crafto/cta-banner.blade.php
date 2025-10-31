{{-- CTA Banner Component --}}
@props([
    'section' => null,
    'content' => []
])

@php
    $title = $content['title'] ?? 'Ready to get started?';
    $subtitle = $content['subtitle'] ?? '';
    $description = $content['description'] ?? 'Take your business to the next level with our solutions';
    $buttonText = $content['button_text'] ?? 'Get Started';
    $buttonUrl = $content['button_url'] ?? '#';
    $button2Text = $content['button_2_text'] ?? '';
    $button2Url = $content['button_2_url'] ?? '#';
    $backgroundColor = $content['background_color'] ?? '#0039e3';
    $backgroundImage = $content['background_image'] ?? '';
    $textColor = $content['text_color'] ?? '#ffffff';
    $layout = $content['layout'] ?? 'centered'; // centered, left-right, with-image
    $overlayOpacity = $content['overlay_opacity'] ?? '0.9';
@endphp

@if($layout === 'left-right')
    {{-- Left-Right Layout --}}
    <section class="py-0" style="background-color: {{ $backgroundColor }}; @if($backgroundImage) background-image: url({{ $backgroundImage }}); background-size: cover; background-position: center; @endif">
        @if($backgroundImage)
            <div class="opacity-light bg-gradient-dark-transparent" style="opacity: {{ $overlayOpacity }};"></div>
        @endif
        <div class="container position-relative">
            <div class="row justify-content-center align-items-center py-5">
                <div class="col-lg-6 col-md-8 text-center text-lg-start sm-mb-20px">
                    @if($subtitle)
                        <span class="d-block mb-5px opacity-7" style="color: {{ $textColor }};">{{ $subtitle }}</span>
                    @endif
                    <h4 class="fw-600 ls-minus-1px mb-0" style="color: {{ $textColor }};">{{ $title }}</h4>
                    @if($description)
                        <p class="mt-10px mb-0 opacity-8" style="color: {{ $textColor }};">{{ $description }}</p>
                    @endif
                </div>
                <div class="col-lg-6 col-md-4 text-center text-lg-end">
                    @if($buttonText)
                        <a href="{{ $buttonUrl }}" class="btn btn-extra-large btn-white btn-rounded btn-box-shadow me-15px xs-me-0 xs-mb-15px">
                            {{ $buttonText }}
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
    </section>

@elseif($layout === 'with-image')
    {{-- With Image Layout --}}
    <section style="background-color: {{ $backgroundColor }}; @if($backgroundImage) background-image: url({{ $backgroundImage }}); background-size: cover; background-position: center; @endif">
        @if($backgroundImage)
            <div class="opacity-light bg-gradient-dark-transparent" style="opacity: {{ $overlayOpacity }};"></div>
        @endif
        <div class="container position-relative">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-6 col-md-10 md-mb-50px text-center text-lg-start">
                    @if($subtitle)
                        <span class="text-uppercase fw-600 mb-10px d-block opacity-7" style="color: {{ $textColor }};">{{ $subtitle }}</span>
                    @endif
                    <h2 class="fw-700 ls-minus-2px mb-20px" style="color: {{ $textColor }};">{{ $title }}</h2>
                    @if($description)
                        <p class="w-90 md-w-100 mb-30px opacity-8" style="color: {{ $textColor }};">{{ $description }}</p>
                    @endif
                    <div class="d-inline-block">
                        @if($buttonText)
                            <a href="{{ $buttonUrl }}" class="btn btn-extra-large btn-white btn-rounded btn-box-shadow me-15px xs-me-0 xs-mb-15px">
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
                <div class="col-lg-5 offset-lg-1 col-md-10 text-center">
                    @if(isset($content['cta_image']))
                        <img src="{{ $content['cta_image'] }}" alt="{{ $title }}" class="border-radius-6px" />
                    @endif
                </div>
            </div>
        </div>
    </section>

@else
    {{-- Centered Layout (Default) --}}
    <section style="background-color: {{ $backgroundColor }}; @if($backgroundImage) background-image: url({{ $backgroundImage }}); background-size: cover; background-position: center; @endif">
        @if($backgroundImage)
            <div class="opacity-light bg-gradient-dark-transparent" style="opacity: {{ $overlayOpacity }};"></div>
        @endif
        <div class="container position-relative">
            <div class="row justify-content-center">
                <div class="col-xxl-7 col-xl-8 col-lg-9 col-md-10 text-center">
                    @if($subtitle)
                        <span class="text-uppercase fw-600 mb-10px d-block opacity-7" style="color: {{ $textColor }};">{{ $subtitle }}</span>
                    @endif
                    <h2 class="fw-700 ls-minus-2px mb-20px" style="color: {{ $textColor }};">{{ $title }}</h2>
                    @if($description)
                        <p class="w-80 md-w-100 mx-auto mb-35px opacity-8 fs-19" style="color: {{ $textColor }};">{{ $description }}</p>
                    @endif
                    <div class="d-inline-block">
                        @if($buttonText)
                            <a href="{{ $buttonUrl }}" class="btn btn-extra-large btn-white btn-rounded btn-box-shadow me-20px xs-me-0 xs-mb-15px">
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
@endif

<style>
    .bg-gradient-dark-transparent {
        background: linear-gradient(to right, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.5));
    }
</style>
