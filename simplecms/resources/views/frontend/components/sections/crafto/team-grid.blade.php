{{-- Team Grid Component --}}
@props([
    'section' => null,
    'content' => []
])

@php
    $heading = $content['heading'] ?? 'Meet Our Team';
    $subheading = $content['subheading'] ?? '';
    $description = $content['description'] ?? '';
    $columns = $content['columns'] ?? 4; // 2, 3, or 4
    $members = $content['members'] ?? [];
    $backgroundColor = $content['background_color'] ?? '#ffffff';
    $animation = $content['animation'] ?? true;

    // Column classes
    $columnClass = match($columns) {
        2 => 'row-cols-lg-2',
        3 => 'row-cols-lg-3',
        default => 'row-cols-lg-4'
    };

    // Default team members if none provided
    if (empty($members)) {
        $members = [
            [
                'image' => asset('crafto/images/team-1.jpg'),
                'name' => 'John Doe',
                'position' => 'CEO & Founder',
                'bio' => 'Passionate about creating innovative solutions.',
                'social' => [
                    'facebook' => '#',
                    'twitter' => '#',
                    'linkedin' => '#'
                ]
            ],
            [
                'image' => asset('crafto/images/team-2.jpg'),
                'name' => 'Jane Smith',
                'position' => 'Creative Director',
                'bio' => 'Expert in design and user experience.',
                'social' => [
                    'facebook' => '#',
                    'twitter' => '#',
                    'instagram' => '#'
                ]
            ],
            [
                'image' => asset('crafto/images/team-3.jpg'),
                'name' => 'Mike Johnson',
                'position' => 'Lead Developer',
                'bio' => 'Building scalable web applications.',
                'social' => [
                    'github' => '#',
                    'linkedin' => '#'
                ]
            ],
            [
                'image' => asset('crafto/images/team-4.jpg'),
                'name' => 'Sarah Wilson',
                'position' => 'Marketing Manager',
                'bio' => 'Driving growth through strategic marketing.',
                'social' => [
                    'twitter' => '#',
                    'linkedin' => '#'
                ]
            ]
        ];
    }
@endphp

<section style="background-color: {{ $backgroundColor }};">
    <div class="container">
        @if($heading || $subheading || $description)
            <div class="row justify-content-center mb-3">
                <div class="col-xxl-6 col-xl-7 col-lg-8 col-md-9 text-center">
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

        <div class="row row-cols-1 {{ $columnClass }} row-cols-sm-2 justify-content-center"
            @if($animation)
            data-anime='{ "el": "childs", "translateY": [30, 0], "opacity": [0,1], "duration": 600, "delay": 0, "staggervalue": 200, "easing": "easeOutQuad" }'
            @endif>
            @foreach($members as $member)
                <div class="col mb-30px">
                    <div class="team-style-01 border-radius-6px overflow-hidden box-shadow-quadruple-large">
                        <figure class="m-0">
                            @if(isset($member['image']))
                                <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}" style="height: 350px; width: 100%; object-fit: cover;" />
                            @else
                                <div class="bg-gradient-base-color d-flex align-items-center justify-content-center" style="height: 350px;">
                                    <span class="text-white fs-100 fw-600">{{ substr($member['name'], 0, 1) }}</span>
                                </div>
                            @endif
                            <figcaption class="d-flex flex-column align-items-start justify-content-center">
                                <div class="team-member-name">
                                    <span class="member-name d-block text-white fw-600 fs-18 lh-24 mb-5px">{{ $member['name'] }}</span>
                                    <span class="member-designation d-block text-white opacity-7 fs-15 lh-22">{{ $member['position'] }}</span>
                                </div>
                                @if(isset($member['social']) && count($member['social']) > 0)
                                    <div class="elements-social social-icon-style-02 mt-20px">
                                        <ul class="small-icon light">
                                            @if(isset($member['social']['facebook']))
                                                <li><a class="facebook" href="{{ $member['social']['facebook'] }}" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
                                            @endif
                                            @if(isset($member['social']['twitter']))
                                                <li><a class="twitter" href="{{ $member['social']['twitter'] }}" target="_blank"><i class="fa-brands fa-twitter"></i></a></li>
                                            @endif
                                            @if(isset($member['social']['instagram']))
                                                <li><a class="instagram" href="{{ $member['social']['instagram'] }}" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
                                            @endif
                                            @if(isset($member['social']['linkedin']))
                                                <li><a class="linkedin" href="{{ $member['social']['linkedin'] }}" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a></li>
                                            @endif
                                            @if(isset($member['social']['github']))
                                                <li><a class="github" href="{{ $member['social']['github'] }}" target="_blank"><i class="fa-brands fa-github"></i></a></li>
                                            @endif
                                        </ul>
                                    </div>
                                @endif
                            </figcaption>
                        </figure>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
    .text-base-color {
        color: #0039e3;
    }
    .bg-gradient-base-color {
        background: linear-gradient(135deg, #0039e3 0%, #4132e0 100%);
    }
    .team-style-01 figure {
        position: relative;
        overflow: hidden;
    }
    .team-style-01 figcaption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 30px;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
        transform: translateY(70%);
        transition: transform 0.3s ease;
    }
    .team-style-01:hover figcaption {
        transform: translateY(0);
    }
</style>
