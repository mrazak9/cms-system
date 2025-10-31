{{-- Stats Counter Component --}}
@props([
    'section' => null,
    'content' => []
])

@php
    $heading = $content['heading'] ?? '';
    $subheading = $content['subheading'] ?? '';
    $stats = $content['stats'] ?? [];
    $backgroundColor = $content['background_color'] ?? '#0039e3';
    $textColor = $content['text_color'] ?? '#ffffff';
    $animation = $content['animation'] ?? true;

    // Default stats if none provided
    if (empty($stats)) {
        $stats = [
            [
                'number' => '2500',
                'suffix' => '+',
                'label' => 'Happy Clients',
                'icon' => 'fa-users'
            ],
            [
                'number' => '1800',
                'suffix' => '+',
                'label' => 'Projects Completed',
                'icon' => 'fa-check-circle'
            ],
            [
                'number' => '15',
                'suffix' => '',
                'label' => 'Years Experience',
                'icon' => 'fa-trophy'
            ],
            [
                'number' => '98',
                'suffix' => '%',
                'label' => 'Client Satisfaction',
                'icon' => 'fa-smile'
            ]
        ];
    }
@endphp

<section class="stats-counter-section" style="background-color: {{ $backgroundColor }}; color: {{ $textColor }};">
    <div class="container">
        @if($heading || $subheading)
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    @if($subheading)
                        <span class="text-uppercase fw-600 mb-10px d-block" style="color: {{ $textColor }}; opacity: 0.8;">{{ $subheading }}</span>
                    @endif
                    @if($heading)
                        <h2 class="fw-700 ls-minus-2px" style="color: {{ $textColor }};">{{ $heading }}</h2>
                    @endif
                </div>
            </div>
        @endif

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 text-center counter-style-01"
            @if($animation)
            data-anime='{ "el": "childs", "translateY": [30, 0], "opacity": [0,1], "duration": 600, "delay": 0, "staggervalue": 200, "easing": "easeOutQuad" }'
            @endif>
            @foreach($stats as $stat)
                <div class="col mb-30px">
                    <div class="counter-box">
                        @if(isset($stat['icon']))
                            <i class="fas {{ $stat['icon'] }} fa-3x mb-20px" style="color: {{ $textColor }}; opacity: 0.9;"></i>
                        @endif
                        <h2 class="vertical-counter d-inline-flex fw-800 mb-5px ls-minus-2px" style="color: {{ $textColor }};">
                            <span class="counter" data-to="{{ $stat['number'] }}" data-speed="2000">0</span>
                            @if(isset($stat['suffix']) && $stat['suffix'])
                                <span>{{ $stat['suffix'] }}</span>
                            @endif
                        </h2>
                        <span class="d-block fw-500" style="color: {{ $textColor }}; opacity: 0.8;">{{ $stat['label'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
    .stats-counter-section {
        padding: 80px 0;
    }

    .counter-box {
        padding: 20px;
    }

    .counter-box .counter {
        font-size: 3.5rem;
        line-height: 1;
    }

    @media (max-width: 767px) {
        .stats-counter-section {
            padding: 60px 0;
        }

        .counter-box .counter {
            font-size: 2.5rem;
        }
    }
</style>

@if($animation)
@push('scripts')
<script>
    // Counter animation
    document.addEventListener('DOMContentLoaded', function() {
        const counters = document.querySelectorAll('.counter');

        const animateCounter = (counter) => {
            const target = parseInt(counter.getAttribute('data-to'));
            const speed = parseInt(counter.getAttribute('data-speed')) || 2000;
            const increment = target / (speed / 16); // 60fps
            let current = 0;

            const updateCounter = () => {
                current += increment;
                if (current < target) {
                    counter.textContent = Math.floor(current);
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = target;
                }
            };

            updateCounter();
        };

        // Intersection Observer for scroll-triggered animation
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    if (!counter.classList.contains('animated')) {
                        counter.classList.add('animated');
                        animateCounter(counter);
                    }
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(counter => observer.observe(counter));
    });
</script>
@endpush
@endif
