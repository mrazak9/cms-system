{{-- Gallery - Grid Layout --}}
<section class="section gallery-1 py-5 bg-light">
    <div class="container">
        {{-- Section Header --}}
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                @if(isset($content['subheading']) && $content['subheading'])
                    <p class="text-primary fw-bold mb-2 text-uppercase small">{{ $content['subheading'] }}</p>
                @endif

                <h2 class="display-5 fw-bold mb-3">
                    {{ $content['heading'] ?? 'Our Portfolio' }}
                </h2>
            </div>
        </div>

        {{-- Gallery Grid --}}
        @if(isset($content['images']) && is_array($content['images']))
            @php
                $columns = $content['columns'] ?? 3;
                $colClass = $columns == 3 ? 'col-lg-4' : ($columns == 4 ? 'col-lg-3' : 'col-lg-6');
            @endphp

            <div class="row g-4">
                @foreach($content['images'] as $index => $image)
                    <div class="{{ $colClass }} col-md-6" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <div class="gallery-item position-relative overflow-hidden rounded shadow">
                            <img src="{{ asset($image['url'] ?? '/images/placeholder.jpg') }}" alt="{{ $image['title'] ?? 'Gallery Image' }}" class="img-fluid w-100" style="height: 300px; object-fit: cover;">

                            {{-- Overlay --}}
                            <div class="gallery-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                                <div class="text-center text-white">
                                    @if(isset($image['title']))
                                        <h5 class="fw-bold">{{ $image['title'] }}</h5>
                                    @endif
                                    <a href="{{ asset($image['url'] ?? '#') }}" class="btn btn-light btn-sm mt-2" data-lightbox="gallery">
                                        <i class="fas fa-search-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<style>
    .gallery-1 .gallery-overlay {
        background: rgba(0, 0, 0, 0.7);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .gallery-1 .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }

    .gallery-1 .gallery-item {
        transition: transform 0.3s ease;
    }

    .gallery-1 .gallery-item:hover {
        transform: scale(1.05);
    }
</style>
