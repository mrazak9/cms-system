{{-- Hero Style 2 - With Video --}}
<section class="hero-section hero-2 position-relative bg-dark text-white">
    @if(isset($content['video_url']) && $content['video_url'])
        <div class="position-absolute top-0 start-0 w-100 h-100">
            <iframe
                width="100%"
                height="100%"
                src="{{ $content['video_url'] }}?autoplay=1&mute=1&controls=0&loop=1&playlist={{ basename($content['video_url']) }}"
                frameborder="0"
                allow="autoplay; encrypted-media"
                allowfullscreen
                style="pointer-events: none;">
            </iframe>
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: {{ $content['background_overlay'] ?? 'rgba(0, 0, 0, 0.5)' }};"></div>
        </div>
    @endif

    <div class="container position-relative py-5" style="z-index: 2;">
        <div class="row align-items-center min-vh-60 py-5">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-3 fw-bold mb-4">
                    {{ $content['heading'] ?? 'Innovative Solutions for Modern Business' }}
                </h1>

                @if(isset($content['subheading']) && $content['subheading'])
                    <p class="lead mb-4 fs-4">
                        {{ $content['subheading'] }}
                    </p>
                @endif

                @if(isset($content['button_text']) && $content['button_text'])
                    <div class="mt-4">
                        <a href="{{ $content['button_link'] ?? '#' }}" class="btn btn-light btn-lg px-5 py-3">
                            {{ $content['button_text'] }}
                            <i class="fas fa-play ms-2"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<style>
    .hero-2 .min-vh-60 {
        min-height: 60vh;
    }
</style>
