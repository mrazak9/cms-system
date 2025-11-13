<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Page;
use App\Models\Setting;

class SchemaService
{
    /**
     * Generate Organization schema
     */
    public function getOrganizationSchema(): array
    {
        $siteName = Setting::get('site_name', config('app.name'));
        $siteUrl = url('/');
        $logo = Setting::get('site_logo');

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $siteName,
            'url' => $siteUrl,
            'logo' => $logo ? asset('storage/' . $logo) : null,
            'sameAs' => array_filter([
                Setting::get('social_facebook'),
                Setting::get('social_twitter'),
                Setting::get('social_linkedin'),
                Setting::get('social_instagram'),
            ]),
        ];
    }

    /**
     * Generate WebSite schema
     */
    public function getWebSiteSchema(): array
    {
        $siteName = Setting::get('site_name', config('app.name'));
        $siteUrl = url('/');

        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $siteName,
            'url' => $siteUrl,
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => route('search') . '?q={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    /**
     * Generate Article schema for blog post
     */
    public function getArticleSchema(Post $post): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $post->title,
            'description' => $post->meta_description ?? $post->excerpt,
            'image' => $post->featured_image ? asset('storage/' . $post->featured_image) : null,
            'datePublished' => $post->published_at?->toIso8601String(),
            'dateModified' => $post->updated_at->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $post->author->name,
            ],
            'publisher' => $this->getOrganizationSchema(),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => route('blog.show', $post->slug),
            ],
        ];
    }

    /**
     * Generate WebPage schema
     */
    public function getWebPageSchema(Page $page): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $page->title,
            'description' => $page->meta_description,
            'url' => route('pages.show', $page->slug),
            'datePublished' => $page->created_at->toIso8601String(),
            'dateModified' => $page->updated_at->toIso8601String(),
        ];
    }

    /**
     * Generate Breadcrumb schema
     */
    public function getBreadcrumbSchema(array $breadcrumbs): array
    {
        $items = [];
        $position = 1;

        foreach ($breadcrumbs as $name => $url) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $name,
                'item' => $url,
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }

    /**
     * Convert schema array to JSON-LD script tag
     */
    public function toJsonLd(array $schema): string
    {
        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
    }
}
