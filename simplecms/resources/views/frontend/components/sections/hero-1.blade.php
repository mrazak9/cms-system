{{-- Hero Style 1 - Full Width --}}
<section class="hero-section hero-1 position-relative" style="background-color: {{ $content['background_color'] ?? '#1a202c' }}; color: {{ $content['text_color'] ?? '#ffffff' }};">
    @if(isset($content['image']) && $content['image'])
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background-image: url('{{ asset($content['image']) }}'); background-size: cover; background-position: center; opacity: 0.3;"></div>
    @endif

    <div class="container position-relative py-5" style="z-index: 1;">
        <div class="row align-items-center min-vh-50 py-5">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-3 fw-bold mb-4" data-aos="fade-up">
                    {{ $content['heading'] ?? 'Welcome to Your Amazing Website' }}
                </h1>

                @if(isset($content['subheading']) && $content['subheading'])
                    <p class="lead mb-4 fs-4" data-aos="fade-up" data-aos-delay="100">
                        {{ $content['subheading'] }}
                    </p>
                @endif

                @if(isset($content['button_text']) && $content['button_text'])
                    <div class="mt-4" data-aos="fade-up" data-aos-delay="200">
                        <a href="{{ $content['button_link'] ?? '#' }}" class="btn btn-lg px-5 py-3" style="background-color: {{ $content['button_color'] ?? '#3182ce' }}; color: white; border: none;">
                            {{ $content['button_text'] }}
                            <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<style>
    .hero-1 .min-vh-50 {
        min-height: 50vh;
    }

    .hero-1 .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
    }
</style>
