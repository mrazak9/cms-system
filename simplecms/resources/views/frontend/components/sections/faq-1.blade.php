{{-- FAQ - Accordion --}}
<section class="section faq-1 py-5">
    <div class="container">
        {{-- Section Header --}}
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                @if(isset($content['subheading']) && $content['subheading'])
                    <p class="text-primary fw-bold mb-2 text-uppercase small">{{ $content['subheading'] }}</p>
                @endif

                <h2 class="display-5 fw-bold mb-3">
                    {{ $content['heading'] ?? 'Frequently Asked Questions' }}
                </h2>
            </div>
        </div>

        {{-- FAQ Accordion --}}
        @if(isset($content['faqs']) && is_array($content['faqs']))
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="accordion" id="faqAccordion">
                        @foreach($content['faqs'] as $index => $faq)
                            <div class="accordion-item border-0 shadow-sm mb-3" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                                <h2 class="accordion-header" id="heading{{ $index }}">
                                    <button class="accordion-button {{ $index != 0 ? 'collapsed' : '' }} fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                                        {{ $faq['question'] ?? 'Question goes here?' }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" aria-labelledby="heading{{ $index }}" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-muted">
                                        {{ $faq['answer'] ?? 'Answer goes here.' }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<style>
    .faq-1 .accordion-button {
        background-color: #f8f9fa;
    }

    .faq-1 .accordion-button:not(.collapsed) {
        background-color: var(--primary-color);
        color: white;
    }

    .faq-1 .accordion-button:focus {
        box-shadow: none;
    }
</style>
