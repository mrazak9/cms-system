{{-- Features - Icon List --}}
<section class="section features-2 py-5">
    <div class="container">
        {{-- Section Header --}}
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="display-5 fw-bold mb-3">
                    {{ $content['heading'] ?? 'Why Choose Us' }}
                </h2>
            </div>
        </div>

        {{-- Features List --}}
        @if(isset($content['features']) && is_array($content['features']))
            <div class="row g-4">
                @foreach($content['features'] as $index => $feature)
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <div class="d-flex align-items-start">
                            @if(isset($feature['icon']))
                                <div class="flex-shrink-0 me-3">
                                    <div class="icon-box bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                        <i class="fas {{ $feature['icon'] }} fa-lg text-primary"></i>
                                    </div>
                                </div>
                            @endif

                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-2">
                                    {{ $feature['title'] ?? 'Feature Title' }}
                                </h5>
                                <p class="text-muted mb-0">
                                    {{ $feature['description'] ?? 'Feature description goes here.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
