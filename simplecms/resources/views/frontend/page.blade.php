@extends('frontend.layouts.crafto')

@section('title', $page->title . ' - ' . ($settings['site_name'] ?? 'SimpleCMS'))
@section('meta_description', $page->meta_description ?? '')
@section('meta_keywords', $page->meta_keywords ?? '')
@section('canonical', url($page->slug))

@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": {{ json_encode($page->title) }},
    "description": {{ json_encode($page->meta_description ?? '') }},
    "url": {{ json_encode(url($page->slug)) }},
    "datePublished": {{ json_encode($page->created_at->toIso8601String()) }},
    "dateModified": {{ json_encode($page->updated_at->toIso8601String()) }},
    "publisher": {
        "@type": "Organization",
        "name": {{ json_encode($settings['site_name'] ?? 'SimpleCMS') }}
    }
}
</script>
@endpush

@section('content')
    {{-- Page Header (if not using sections or as default) --}}
    @if($page->sections->count() == 0)
        <section class="section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10 mx-auto">
                        <h1 class="display-4 fw-bold mb-4">{{ $page->title }}</h1>

                        {{-- Page Meta Info --}}
                        <div class="text-muted mb-4">
                            <small>
                                <i class="far fa-calendar me-2"></i>Last updated: {{ $page->updated_at->format('F d, Y') }}
                            </small>
                        </div>

                        <div class="content">
                            <p class="text-muted">This page doesn't have any content sections yet.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        {{-- Render Dynamic Page Sections --}}
        @foreach($page->sections as $section)
            @if($section->is_visible && $section->sectionTemplate)
                @php
                    // Handle both regular sections and namespaced sections (e.g., crafto.hero-simple)
                    $viewPath = 'frontend.components.sections.' . str_replace('.', '.', $section->sectionTemplate->blade_view);

                    // Get default fields - handle both JSON string and array
                    $defaultFields = $section->sectionTemplate->default_fields;
                    if (is_string($defaultFields)) {
                        $defaultFields = json_decode($defaultFields, true) ?? [];
                    } elseif (!is_array($defaultFields)) {
                        $defaultFields = [];
                    }

                    // Merge with section content
                    $mergedContent = array_merge(
                        $defaultFields,
                        $section->content ?? []
                    );
                @endphp

                {{-- Render the section component --}}
                @include($viewPath, [
                    'section' => $section,
                    'content' => $mergedContent
                ])
            @endif
        @endforeach
    @endif
@endsection

@push('styles')
<style>
    .content {
        font-size: 1.1rem;
        line-height: 1.8;
    }

    .content h1, .content h2, .content h3,
    .content h4, .content h5, .content h6 {
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .content p {
        margin-bottom: 1.5rem;
    }

    .content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1.5rem 0;
    }

    .content ul, .content ol {
        margin-bottom: 1.5rem;
        padding-left: 2rem;
    }

    .content li {
        margin-bottom: 0.5rem;
    }

    .content blockquote {
        border-left: 4px solid var(--primary-color);
        padding-left: 1.5rem;
        margin: 2rem 0;
        font-style: italic;
        color: #666;
    }

    .content code {
        background-color: #f4f4f4;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.9em;
    }

    .content pre {
        background-color: #f4f4f4;
        padding: 1rem;
        border-radius: 8px;
        overflow-x: auto;
        margin: 1.5rem 0;
    }

    .content table {
        width: 100%;
        margin: 1.5rem 0;
        border-collapse: collapse;
    }

    .content table th,
    .content table td {
        padding: 0.75rem;
        border: 1px solid #ddd;
    }

    .content table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
</style>
@endpush
