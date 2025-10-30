{{-- About Us - Single Column --}}
<section class="section about-2 py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="display-5 fw-bold mb-4" data-aos="fade-up">
                    {{ $content['heading'] ?? 'Our Story' }}
                </h2>

                @if(isset($content['content']) && $content['content'])
                    <p class="lead text-muted mb-4" data-aos="fade-up" data-aos-delay="100">
                        {{ $content['content'] }}
                    </p>
                @endif

                @if(isset($content['button_text']) && $content['button_text'])
                    <div class="mt-4" data-aos="fade-up" data-aos-delay="200">
                        <a href="{{ $content['button_link'] ?? '#' }}" class="btn btn-primary btn-lg px-5 py-3">
                            {{ $content['button_text'] }}
                            <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
