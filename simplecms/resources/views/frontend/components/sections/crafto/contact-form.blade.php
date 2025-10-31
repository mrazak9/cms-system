{{-- Contact Form Component --}}
@props(['section' => null, 'content' => []])

@php
    $heading = $content['heading'] ?? 'Get In Touch';
    $subheading = $content['subheading'] ?? 'Contact Us';
    $description = $content['description'] ?? '';
    $email = $content['email'] ?? 'info@example.com';
    $phone = $content['phone'] ?? '+1 234 567 8900';
    $address = $content['address'] ?? '123 Main Street, City, Country';
    $showMap = $content['show_map'] ?? false;
    $mapEmbedUrl = $content['map_embed_url'] ?? '';
    $backgroundColor = $content['background_color'] ?? '#ffffff';
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

        <div class="row">
            <div class="col-lg-8 mb-30px">
                <div class="contact-form-box">
                    <form action="{{ route('contact.submit') }}" method="POST" class="contact-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-20px">
                                <input type="text" name="name" class="form-control" placeholder="Your Name *" required>
                            </div>
                            <div class="col-md-6 mb-20px">
                                <input type="email" name="email" class="form-control" placeholder="Your Email *" required>
                            </div>
                            <div class="col-md-6 mb-20px">
                                <input type="tel" name="phone" class="form-control" placeholder="Phone Number">
                            </div>
                            <div class="col-md-6 mb-20px">
                                <input type="text" name="subject" class="form-control" placeholder="Subject">
                            </div>
                            <div class="col-12 mb-20px">
                                <textarea name="message" class="form-control" rows="6" placeholder="Your Message *" required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-large">
                                    Send Message <i class="fa-solid fa-paper-plane ms-10px"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-4 mb-30px">
                <div class="contact-info-box">
                    <div class="contact-info-item mb-30px">
                        <div class="contact-info-icon">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div class="contact-info-content">
                            <h5>Email</h5>
                            <a href="mailto:{{ $email }}">{{ $email }}</a>
                        </div>
                    </div>

                    <div class="contact-info-item mb-30px">
                        <div class="contact-info-icon">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div class="contact-info-content">
                            <h5>Phone</h5>
                            <a href="tel:{{ str_replace(' ', '', $phone) }}">{{ $phone }}</a>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div class="contact-info-content">
                            <h5>Address</h5>
                            <p>{{ $address }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($showMap && $mapEmbedUrl)
            <div class="row mt-5">
                <div class="col-12">
                    <div class="map-container">
                        <iframe src="{{ $mapEmbedUrl }}" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<style>
    .contact-form-box {
        background: #f8f9fa;
        padding: 40px;
        border-radius: 8px;
    }
    .contact-form .form-control {
        border: 1px solid #e0e0e0;
        padding: 15px 20px;
        border-radius: 6px;
        font-size: 15px;
    }
    .contact-form .form-control:focus {
        border-color: #0039e3;
        box-shadow: 0 0 0 0.2rem rgba(0, 57, 227, 0.1);
    }
    .contact-form .btn-primary {
        background-color: #0039e3;
        border-color: #0039e3;
        padding: 15px 40px;
        font-weight: 600;
        border-radius: 6px;
    }
    .contact-info-box {
        background: #f8f9fa;
        padding: 40px;
        border-radius: 8px;
        height: 100%;
    }
    .contact-info-item {
        display: flex;
        gap: 20px;
    }
    .contact-info-icon {
        width: 50px;
        height: 50px;
        background: #0039e3;
        color: #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .contact-info-content h5 {
        font-weight: 700;
        margin-bottom: 8px;
    }
    .contact-info-content a {
        color: #666;
        text-decoration: none;
    }
    .contact-info-content a:hover {
        color: #0039e3;
    }
    .contact-info-content p {
        margin: 0;
        color: #666;
    }
    .map-container {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
    }
    .text-base-color {
        color: #0039e3;
    }
</style>
