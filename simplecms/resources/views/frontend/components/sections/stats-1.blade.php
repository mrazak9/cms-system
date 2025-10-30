{{-- Statistics - Counter --}}
<section class="section stats-1 py-5" style="background-color: {{ $content['background_color'] ?? '#f7fafc' }};">
    <div class="container py-4">
        {{-- Section Header --}}
        @if(isset($content['heading']) && $content['heading'])
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="display-5 fw-bold mb-3">
                        {{ $content['heading'] }}
                    </h2>
                </div>
            </div>
        @endif

        {{-- Stats Grid --}}
        @if(isset($content['stats']) && is_array($content['stats']))
            <div class="row g-4 text-center">
                @foreach($content['stats'] as $index => $stat)
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <div class="stat-item p-4">
                            <div class="stat-number display-3 fw-bold text-primary mb-2">
                                {{ $stat['number'] ?? '0' }}
                            </div>
                            <div class="stat-label text-muted text-uppercase small fw-bold">
                                {{ $stat['label'] ?? 'Stat Label' }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<style>
    .stats-1 .stat-item {
        transition: transform 0.3s ease;
    }

    .stats-1 .stat-item:hover {
        transform: translateY(-10px);
    }
</style>
