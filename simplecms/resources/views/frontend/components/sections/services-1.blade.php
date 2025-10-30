{{-- Services - Card Layout --}}
<section class="section services-1 py-5">
    <div class="container">
        {{-- Section Header --}}
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                @if(isset($content['subheading']) && $content['subheading'])
                    <p class="text-primary fw-bold mb-2 text-uppercase small">{{ $content['subheading'] }}</p>
                @endif

                <h2 class="display-5 fw-bold mb-3">
                    {{ $content['heading'] ?? 'Our Services' }}
                </h2>
            </div>
        </div>

        {{-- Services Grid --}}
        @if(isset($content['services']) && is_array($content['services']))
            <div class="row g-4">
                @foreach($content['services'] as $index => $service)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <div class="card border-0 shadow-sm h-100 hover-lift">
                            <div class="card-body p-4 text-center">
                                @if(isset($service['icon']))
                                    <div class="icon-box bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                                        <i class="fas {{ $service['icon'] }} fa-2x text-primary"></i>
                                    </div>
                                @endif

                                <h4 class="card-title fw-bold mb-3">
                                    {{ $service['title'] ?? 'Service Title' }}
                                </h4>

                                <p class="card-text text-muted mb-4">
                                    {{ $service['description'] ?? 'Service description goes here.' }}
                                </p>

                                @if(isset($service['link']) && $service['link'])
                                    <a href="{{ $service['link'] }}" class="btn btn-outline-primary">
                                        Learn More <i class="fas fa-arrow-right ms-2"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<style>
    .services-1 .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .services-1 .hover-lift:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important;
    }
</style>
