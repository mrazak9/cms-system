{{-- Features - 3 Column Grid --}}
<section class="section features-1 py-5 bg-light">
    <div class="container">
        {{-- Section Header --}}
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                @if(isset($content['subheading']) && $content['subheading'])
                    <p class="text-primary fw-bold mb-2 text-uppercase small">{{ $content['subheading'] }}</p>
                @endif

                <h2 class="display-5 fw-bold mb-3">
                    {{ $content['heading'] ?? 'Our Amazing Features' }}
                </h2>
            </div>
        </div>

        {{-- Features Grid --}}
        @if(isset($content['features']) && is_array($content['features']))
            <div class="row g-4">
                @foreach($content['features'] as $index => $feature)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <div class="card border-0 shadow-sm h-100 text-center p-4 hover-lift">
                            <div class="card-body">
                                @if(isset($feature['icon']))
                                    <div class="icon-box bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                                        <i class="fas {{ $feature['icon'] }} fa-2x text-primary"></i>
                                    </div>
                                @endif

                                <h4 class="card-title fw-bold mb-3">
                                    {{ $feature['title'] ?? 'Feature Title' }}
                                </h4>

                                <p class="card-text text-muted">
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

<style>
    .features-1 .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .features-1 .hover-lift:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important;
    }
</style>
