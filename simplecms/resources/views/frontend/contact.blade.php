@extends('frontend.layouts.app')

@section('title', 'Contact Us - ' . ($settings['site_name'] ?? 'SimpleCMS'))
@section('meta_description', 'Get in touch with us. Send us a message and we will get back to you as soon as possible.')

@section('content')
<div class="section bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <h1 class="display-4 fw-bold mb-3">Contact Us</h1>
                    <p class="lead text-muted">
                        Have a question or feedback? We'd love to hear from you.
                    </p>
                </div>

                {{-- Success/Error Messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Contact Form --}}
                <div class="card shadow-sm border-0">
                    <div class="card-body p-5">
                        <form action="{{ route('contact.store') }}" method="POST" id="contactForm">
                            @csrf

                            <div class="mb-4">
                                <label for="name" class="form-label fw-semibold">
                                    Your Name <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control form-control-lg @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name') }}"
                                       placeholder="John Doe"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold">
                                    Email Address <span class="text-danger">*</span>
                                </label>
                                <input type="email"
                                       class="form-control form-control-lg @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       placeholder="john@example.com"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="subject" class="form-label fw-semibold">
                                    Subject <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control form-control-lg @error('subject') is-invalid @enderror"
                                       id="subject"
                                       name="subject"
                                       value="{{ old('subject') }}"
                                       placeholder="How can we help you?"
                                       required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="message" class="form-label fw-semibold">
                                    Message <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('message') is-invalid @enderror"
                                          id="message"
                                          name="message"
                                          rows="6"
                                          placeholder="Tell us more about your inquiry..."
                                          required>{{ old('message') }}</textarea>
                                <small class="form-text text-muted">Minimum 10 characters</small>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Contact Information (Optional) --}}
                <div class="row mt-5">
                    <div class="col-md-4 text-center mb-4 mb-md-0">
                        <div class="mb-3">
                            <i class="fas fa-envelope fa-2x text-primary"></i>
                        </div>
                        <h5 class="fw-semibold">Email</h5>
                        <p class="text-muted">
                            {{ $settings['contact_email'] ?? 'info@example.com' }}
                        </p>
                    </div>
                    <div class="col-md-4 text-center mb-4 mb-md-0">
                        <div class="mb-3">
                            <i class="fas fa-phone fa-2x text-primary"></i>
                        </div>
                        <h5 class="fw-semibold">Phone</h5>
                        <p class="text-muted">
                            {{ $settings['contact_phone'] ?? '+1 (555) 123-4567' }}
                        </p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="mb-3">
                            <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
                        </div>
                        <h5 class="fw-semibold">Location</h5>
                        <p class="text-muted">
                            {{ $settings['contact_address'] ?? '123 Main St, City, Country' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Form validation feedback
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        const message = document.getElementById('message').value;
        if (message.length < 10) {
            e.preventDefault();
            alert('Message must be at least 10 characters long.');
            return false;
        }
    });
</script>
@endpush
