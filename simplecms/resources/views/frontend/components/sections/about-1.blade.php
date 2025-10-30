{{-- About Us - Two Column --}}
<section class="section about-1 py-5">
    <div class="container">
        <div class="row align-items-center g-5">
            {{-- Image Column --}}
            <div class="col-lg-6" data-aos="fade-right">
                @if(isset($content['image']) && $content['image'])
                    <img src="{{ asset($content['image']) }}" alt="{{ $content['heading'] ?? 'About Us' }}" class="img-fluid rounded shadow-lg">
                @else
                    <div class="bg-light rounded shadow-lg d-flex align-items-center justify-content-center" style="height: 400px;">
                        <i class="fas fa-image fa-5x text-muted opacity-50"></i>
                    </div>
                @endif
            </div>

            {{-- Content Column --}}
            <div class="col-lg-6" data-aos="fade-left">
                @if(isset($content['subheading']) && $content['subheading'])
                    <p class="text-primary fw-bold mb-2 text-uppercase small">{{ $content['subheading'] }}</p>
                @endif

                <h2 class="display-5 fw-bold mb-4">
                    {{ $content['heading'] ?? 'About Our Company' }}
                </h2>

                @if(isset($content['content']) && $content['content'])
                    <p class="lead text-muted mb-4">
                        {{ $content['content'] }}
                    </p>
                @endif

                {{-- Stats --}}
                @if(isset($content['stats']) && is_array($content['stats']))
                    <div class="row g-4 mt-4">
                        @foreach($content['stats'] as $stat)
                            <div class="col-sm-4">
                                <div class="text-center">
                                    <h3 class="fw-bold text-primary mb-2">{{ $stat['number'] ?? '0' }}</h3>
                                    <p class="text-muted mb-0">{{ $stat['label'] ?? '' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
