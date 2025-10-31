{{-- FAQ Accordion Component --}}
@props(['section' => null, 'content' => []])

@php
    $heading = $content['heading'] ?? 'Frequently Asked Questions';
    $subheading = $content['subheading'] ?? 'FAQ';
    $description = $content['description'] ?? '';
    $faqs = $content['faqs'] ?? [];
    $backgroundColor = $content['background_color'] ?? '#ffffff';

    if (empty($faqs)) {
        $faqs = [
            ['question' => 'What services do you offer?', 'answer' => 'We offer a comprehensive range of services including web design, development, branding, and digital marketing to help your business succeed online.'],
            ['question' => 'How long does a typical project take?', 'answer' => 'Project timelines vary depending on scope and complexity. A simple website might take 2-4 weeks, while larger projects can take 8-12 weeks or more.'],
            ['question' => 'What is your pricing structure?', 'answer' => 'Our pricing is project-based and depends on your specific requirements. We offer flexible packages and can provide a detailed quote after understanding your needs.'],
            ['question' => 'Do you provide ongoing support?', 'answer' => 'Yes! We offer various support and maintenance packages to ensure your website stays updated, secure, and performs optimally.'],
            ['question' => 'Can you work with our existing brand?', 'answer' => 'Absolutely! We can work within your existing brand guidelines or help you develop a new brand identity from scratch.']
        ];
    }

    $accordionId = 'faq-accordion-' . ($section->id ?? uniqid());
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

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="accordion faq-accordion" id="{{ $accordionId }}">
                    @foreach($faqs as $index => $faq)
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="heading-{{ $accordionId }}-{{ $index }}">
                                <button class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }}" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse-{{ $accordionId }}-{{ $index }}"
                                    aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse-{{ $accordionId }}-{{ $index }}">
                                    {{ $faq['question'] }}
                                </button>
                            </h3>
                            <div id="collapse-{{ $accordionId }}-{{ $index }}"
                                class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                aria-labelledby="heading-{{ $accordionId }}-{{ $index }}" data-bs-parent="#{{ $accordionId }}">
                                <div class="accordion-body">
                                    {{ $faq['answer'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .faq-accordion .accordion-item {
        border: none;
        margin-bottom: 15px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
    }
    .faq-accordion .accordion-button {
        background-color: #ffffff;
        color: #232323;
        font-weight: 600;
        font-size: 16px;
        padding: 20px 25px;
        border: none;
        box-shadow: none;
    }
    .faq-accordion .accordion-button:not(.collapsed) {
        background-color: #0039e3;
        color: #ffffff;
    }
    .faq-accordion .accordion-button:focus {
        box-shadow: none;
    }
    .faq-accordion .accordion-body {
        padding: 20px 25px;
        color: #666;
        line-height: 1.8;
    }
    .text-base-color {
        color: #0039e3;
    }
</style>
