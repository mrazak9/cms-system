{{-- Call to Action - Centered --}}
<section class="section cta-1 py-5" style="background-color: {{ $content['background_color'] ?? '#3182ce' }}; color: {{ $content['text_color'] ?? '#ffffff' }};">
    <div class="container py-4">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="display-5 fw-bold mb-3" data-aos="fade-up">
                    {{ $content['heading'] ?? 'Ready to Get Started?' }}
                </h2>

                @if(isset($content['subheading']) && $content['subheading'])
                    <p class="lead mb-4" data-aos="fade-up" data-aos-delay="100">
                        {{ $content['subheading'] }}
                    </p>
                @endif

                <div class="d-flex gap-3 justify-content-center flex-wrap" data-aos="fade-up" data-aos-delay="200">
                    @if(isset($content['button_text']) && $content['button_text'])
                        <a href="{{ $content['button_link'] ?? '#' }}" class="btn btn-light btn-lg px-5 py-3">
                            {{ $content['button_text'] }}
                            <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    @endif

                    @if(isset($content['secondary_button_text']) && $content['secondary_button_text'])
                        <a href="{{ $content['secondary_button_link'] ?? '#' }}" class="btn btn-outline-light btn-lg px-5 py-3">
                            {{ $content['secondary_button_text'] }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .cta-1 .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
    }
</style>
