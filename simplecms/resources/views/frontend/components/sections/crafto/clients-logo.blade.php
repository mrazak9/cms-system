{{-- Clients Logo Component --}}
@props(['section' => null, 'content' => []])

@php
    $heading = $content['heading'] ?? '';
    $subheading = $content['subheading'] ?? '';
    $clients = $content['clients'] ?? [];
    $grayscale = $content['grayscale'] ?? true;
    $backgroundColor = $content['background_color'] ?? '#f8f9fa';

    if (empty($clients)) {
        $clients = [
            ['logo' => asset('crafto/images/logo-1.png'), 'name' => 'Client 1', 'url' => '#'],
            ['logo' => asset('crafto/images/logo-2.png'), 'name' => 'Client 2', 'url' => '#'],
            ['logo' => asset('crafto/images/logo-3.png'), 'name' => 'Client 3', 'url' => '#'],
            ['logo' => asset('crafto/images/logo-4.png'), 'name' => 'Client 4', 'url' => '#'],
            ['logo' => asset('crafto/images/logo-5.png'), 'name' => 'Client 5', 'url' => '#'],
            ['logo' => asset('crafto/images/logo-6.png'), 'name' => 'Client 6', 'url' => '#']
        ];
    }
@endphp

<section class="clients-section" style="background-color: {{ $backgroundColor }};">
    <div class="container">
        @if($heading || $subheading)
            <div class="row justify-content-center mb-5">
                <div class="col-lg-7 text-center">
                    @if($subheading)
                        <span class="text-base-color fw-600 mb-5px text-uppercase d-block">{{ $subheading }}</span>
                    @endif
                    @if($heading)
                        <h2 class="fw-700 text-dark-gray ls-minus-2px">{{ $heading }}</h2>
                    @endif
                </div>
            </div>
        @endif

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 align-items-center justify-content-center">
            @foreach($clients as $client)
                <div class="col text-center mb-30px">
                    <a href="{{ $client['url'] ?? '#' }}" class="client-logo {{ $grayscale ? 'grayscale' : '' }}">
                        <img src="{{ $client['logo'] }}" alt="{{ $client['name'] }}" class="img-fluid">
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
    .clients-section {
        padding: 80px 0;
    }
    .client-logo {
        display: inline-block;
        opacity: 0.7;
        transition: all 0.3s ease;
    }
    .client-logo.grayscale img {
        filter: grayscale(100%);
    }
    .client-logo:hover {
        opacity: 1;
    }
    .client-logo:hover img {
        filter: grayscale(0%);
    }
    .text-base-color {
        color: #0039e3;
    }
</style>
