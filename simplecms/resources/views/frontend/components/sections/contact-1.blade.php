{{-- Contact Form - Modern --}}
<section class="section contact-1 py-5">
    <div class="container">
        {{-- Section Header --}}
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                @if(isset($content['subheading']) && $content['subheading'])
                    <p class="text-primary fw-bold mb-2 text-uppercase small">{{ $content['subheading'] }}</p>
                @endif

                <h2 class="display-5 fw-bold mb-3">
                    {{ $content['heading'] ?? 'Get In Touch' }}
                </h2>
            </div>
        </div>

        <div class="row g-5">
            {{-- Contact Form --}}
            <div class="col-lg-7">
                <form action="{{ route('contact.submit') }}" method="POST" class="needs-validation" novalidate>
                    @csrf

                    @if(isset($content['form_fields']) && is_array($content['form_fields']))
                        <div class="row g-3">
                            @if(in_array('name', $content['form_fields']))
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            @endif

                            @if(in_array('email', $content['form_fields']))
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            @endif

                            @if(in_array('phone', $content['form_fields']))
                                <div class="col-12">
                                    <label for="phone" class="form-label fw-bold">Phone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone">
                                </div>
                            @endif

                            @if(in_array('message', $content['form_fields']))
                                <div class="col-12">
                                    <label for="message" class="form-label fw-bold">Message <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                                </div>
                            @endif

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    Send Message <i class="fas fa-paper-plane ms-2"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                </form>
            </div>

            {{-- Contact Info --}}
            @if(isset($content['show_info']) && $content['show_info'])
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm h-100 p-4">
                        <div class="card-body">
                            <h4 class="fw-bold mb-4">Contact Information</h4>

                            @if(isset($content['email']) && $content['email'])
                                <div class="d-flex align-items-start mb-4">
                                    <div class="icon-box bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 50px; height: 50px;">
                                        <i class="fas fa-envelope text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Email</h6>
                                        <a href="mailto:{{ $content['email'] }}" class="text-muted text-decoration-none">{{ $content['email'] }}</a>
                                    </div>
                                </div>
                            @endif

                            @if(isset($content['phone']) && $content['phone'])
                                <div class="d-flex align-items-start mb-4">
                                    <div class="icon-box bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 50px; height: 50px;">
                                        <i class="fas fa-phone text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Phone</h6>
                                        <a href="tel:{{ $content['phone'] }}" class="text-muted text-decoration-none">{{ $content['phone'] }}</a>
                                    </div>
                                </div>
                            @endif

                            @if(isset($content['address']) && $content['address'])
                                <div class="d-flex align-items-start">
                                    <div class="icon-box bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 50px; height: 50px;">
                                        <i class="fas fa-map-marker-alt text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Address</h6>
                                        <p class="text-muted mb-0">{{ $content['address'] }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
