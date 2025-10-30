{{-- Testimonials - Carousel --}}
<section class="section testimonials-1 py-5 bg-light">
    <div class="container">
        {{-- Section Header --}}
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                @if(isset($content['subheading']) && $content['subheading'])
                    <p class="text-primary fw-bold mb-2 text-uppercase small">{{ $content['subheading'] }}</p>
                @endif

                <h2 class="display-5 fw-bold mb-3">
                    {{ $content['heading'] ?? 'What Our Clients Say' }}
                </h2>
            </div>
        </div>

        {{-- Testimonials Carousel --}}
        @if(isset($content['testimonials']) && is_array($content['testimonials']))
            <div id="testimonialsCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($content['testimonials'] as $index => $testimonial)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <div class="card border-0 shadow-sm p-5 text-center">
                                        {{-- Avatar --}}
                                        @if(isset($testimonial['avatar']) && $testimonial['avatar'])
                                            <img src="{{ asset($testimonial['avatar']) }}" class="rounded-circle mx-auto mb-4" alt="{{ $testimonial['name'] ?? 'Client' }}" style="width: 80px; height: 80px; object-fit: cover;">
                                        @else
                                            <div class="bg-primary text-white rounded-circle mx-auto mb-4 d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                                                {{ isset($testimonial['name']) ? substr($testimonial['name'], 0, 1) : 'C' }}
                                            </div>
                                        @endif

                                        {{-- Rating --}}
                                        @if(isset($testimonial['rating']) && $testimonial['rating'])
                                            <div class="mb-3">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $testimonial['rating'] ? 'text-warning' : 'text-muted' }}"></i>
                                                @endfor
                                            </div>
                                        @endif

                                        {{-- Content --}}
                                        <blockquote class="blockquote mb-4">
                                            <p class="lead">"{{ $testimonial['content'] ?? 'Testimonial content goes here.' }}"</p>
                                        </blockquote>

                                        {{-- Name & Position --}}
                                        <div class="mb-0">
                                            <strong class="d-block">{{ $testimonial['name'] ?? 'Client Name' }}</strong>
                                            <small class="text-muted">{{ $testimonial['position'] ?? 'Position, Company' }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Carousel Controls --}}
                @if(count($content['testimonials']) > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon bg-primary rounded-circle p-3" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon bg-primary rounded-circle p-3" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                @endif
            </div>
        @endif
    </div>
</section>
