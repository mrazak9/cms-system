{{-- Pricing Table Component --}}
@props([
    'section' => null,
    'content' => []
])

@php
    $heading = $content['heading'] ?? 'Choose Your Plan';
    $subheading = $content['subheading'] ?? 'Pricing';
    $description = $content['description'] ?? '';
    $plans = $content['plans'] ?? [];
    $backgroundColor = $content['background_color'] ?? '#ffffff';
    $animation = $content['animation'] ?? true;

    // Default plans if none provided
    if (empty($plans)) {
        $plans = [
            [
                'name' => 'Basic',
                'price' => '29',
                'currency' => '$',
                'period' => 'month',
                'description' => 'Perfect for individuals',
                'features' => [
                    '10 GB Storage',
                    '5 Email Accounts',
                    'Basic Support',
                    '1 Website'
                ],
                'button_text' => 'Get Started',
                'button_url' => '#',
                'highlighted' => false
            ],
            [
                'name' => 'Professional',
                'price' => '59',
                'currency' => '$',
                'period' => 'month',
                'description' => 'Great for small businesses',
                'features' => [
                    '50 GB Storage',
                    'Unlimited Email Accounts',
                    'Priority Support',
                    '10 Websites',
                    'Free SSL Certificate'
                ],
                'button_text' => 'Get Started',
                'button_url' => '#',
                'highlighted' => true
            ],
            [
                'name' => 'Enterprise',
                'price' => '99',
                'currency' => '$',
                'period' => 'month',
                'description' => 'For large organizations',
                'features' => [
                    'Unlimited Storage',
                    'Unlimited Email Accounts',
                    '24/7 Premium Support',
                    'Unlimited Websites',
                    'Free SSL & CDN',
                    'Dedicated Server'
                ],
                'button_text' => 'Contact Us',
                'button_url' => '#',
                'highlighted' => false
            ]
        ];
    }
@endphp

<section style="background-color: {{ $backgroundColor }};">
    <div class="container">
        @if($heading || $subheading || $description)
            <div class="row justify-content-center mb-5">
                <div class="col-lg-7 text-center">
                    @if($subheading)
                        <span class="text-base-color fw-600 mb-5px text-uppercase d-block">{{ $subheading }}</span>
                    @endif
                    @if($heading)
                        <h2 class="fw-700 text-dark-gray ls-minus-2px">{{ $heading }}</h2>
                    @endif
                    @if($description)
                        <p class="w-85 md-w-100 mx-auto">{{ $description }}</p>
                    @endif
                </div>
            </div>
        @endif

        <div class="row row-cols-1 row-cols-md-3 justify-content-center"
            @if($animation)
            data-anime='{ "el": "childs", "translateY": [50, 0], "opacity": [0,1], "duration": 800, "delay": 0, "staggervalue": 200, "easing": "easeOutQuad" }'
            @endif>
            @foreach($plans as $plan)
                <div class="col mb-30px">
                    <div class="pricing-table-style-01 {{ isset($plan['highlighted']) && $plan['highlighted'] ? 'popular' : '' }}">
                        @if(isset($plan['highlighted']) && $plan['highlighted'])
                            <div class="popular-badge">
                                <span class="badge bg-base-color text-white">Most Popular</span>
                            </div>
                        @endif

                        <div class="pricing-header">
                            <h3 class="pricing-title fw-700 text-dark-gray mb-10px">{{ $plan['name'] }}</h3>
                            @if(isset($plan['description']))
                                <p class="text-muted mb-20px">{{ $plan['description'] }}</p>
                            @endif
                            <div class="pricing-price">
                                <span class="currency">{{ $plan['currency'] ?? '$' }}</span>
                                <span class="price fw-800">{{ $plan['price'] }}</span>
                                <span class="period">/ {{ $plan['period'] ?? 'month' }}</span>
                            </div>
                        </div>

                        <div class="pricing-body">
                            <ul class="pricing-features list-unstyled">
                                @foreach($plan['features'] as $feature)
                                    <li>
                                        <i class="fa-solid fa-check text-base-color me-10px"></i>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="pricing-footer">
                            <a href="{{ $plan['button_url'] }}" class="btn {{ isset($plan['highlighted']) && $plan['highlighted'] ? 'btn-primary' : 'btn-outline-primary' }} btn-block">
                                {{ $plan['button_text'] }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
    .pricing-table-style-01 {
        background: #ffffff;
        border-radius: 8px;
        padding: 40px 30px;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .pricing-table-style-01:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    }

    .pricing-table-style-01.popular {
        border: 2px solid #0039e3;
        transform: scale(1.05);
    }

    .pricing-table-style-01.popular:hover {
        transform: scale(1.05) translateY(-10px);
    }

    .popular-badge {
        position: absolute;
        top: -15px;
        right: 30px;
    }

    .popular-badge .badge {
        padding: 8px 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .pricing-header {
        text-align: center;
        padding-bottom: 30px;
        border-bottom: 1px solid #f0f0f0;
        margin-bottom: 30px;
    }

    .pricing-price {
        margin-top: 20px;
    }

    .pricing-price .currency {
        font-size: 24px;
        vertical-align: super;
        color: #0039e3;
    }

    .pricing-price .price {
        font-size: 56px;
        line-height: 1;
        color: #0039e3;
    }

    .pricing-price .period {
        font-size: 16px;
        color: #666;
    }

    .pricing-body {
        flex-grow: 1;
        margin-bottom: 30px;
    }

    .pricing-features li {
        padding: 12px 0;
        border-bottom: 1px solid #f5f5f5;
        color: #666;
    }

    .pricing-features li:last-child {
        border-bottom: none;
    }

    .pricing-footer .btn-block {
        width: 100%;
        padding: 15px;
        font-weight: 600;
        border-radius: 6px;
    }

    .text-base-color, .bg-base-color {
        color: #0039e3 !important;
        background-color: #0039e3 !important;
    }

    .btn-primary {
        background-color: #0039e3;
        border-color: #0039e3;
    }

    .btn-outline-primary {
        color: #0039e3;
        border-color: #0039e3;
    }

    .btn-outline-primary:hover {
        background-color: #0039e3;
        color: #ffffff;
    }

    @media (max-width: 991px) {
        .pricing-table-style-01.popular {
            transform: scale(1);
        }

        .pricing-table-style-01.popular:hover {
            transform: translateY(-10px);
        }
    }
</style>
