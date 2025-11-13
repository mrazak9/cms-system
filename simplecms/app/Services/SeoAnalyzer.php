<?php

namespace App\Services;

use Illuminate\Support\Str;

class SeoAnalyzer
{
    /**
     * Analyze content for SEO score
     */
    public function analyzeContent($title, $content, $metaDescription = null, $slug = null): array
    {
        $score = 100;
        $issues = [];
        $suggestions = [];
        $passed = [];

        // Title checks
        $titleLength = strlen($title);
        if ($titleLength < 30) {
            $score -= 10;
            $issues[] = 'Title is too short (should be 30-60 characters)';
            $suggestions[] = 'Expand your title to be more descriptive';
        } elseif ($titleLength > 60) {
            $score -= 10;
            $issues[] = 'Title is too long (should be 30-60 characters)';
            $suggestions[] = 'Shorten your title to avoid truncation in search results';
        } else {
            $passed[] = 'Title length is optimal';
        }

        // Meta description checks
        if ($metaDescription) {
            $metaLength = strlen($metaDescription);
            if ($metaLength < 120) {
                $score -= 10;
                $issues[] = 'Meta description is too short (should be 120-160 characters)';
                $suggestions[] = 'Add more detail to your meta description';
            } elseif ($metaLength > 160) {
                $score -= 10;
                $issues[] = 'Meta description is too long (should be 120-160 characters)';
                $suggestions[] = 'Shorten meta description to avoid truncation';
            } else {
                $passed[] = 'Meta description length is optimal';
            }
        } else {
            $score -= 15;
            $issues[] = 'Meta description is missing';
            $suggestions[] = 'Add a compelling meta description';
        }

        // Content length check
        $wordCount = str_word_count(strip_tags($content));
        if ($wordCount < 300) {
            $score -= 15;
            $issues[] = 'Content is too short (minimum 300 words recommended)';
            $suggestions[] = 'Add more valuable content to improve SEO';
        } else {
            $passed[] = "Content length is good ({$wordCount} words)";
        }

        // Heading checks
        $h1Count = substr_count(strtolower($content), '<h1');
        $h2Count = substr_count(strtolower($content), '<h2');

        if ($h1Count == 0) {
            $score -= 10;
            $issues[] = 'No H1 heading found in content';
            $suggestions[] = 'Add at least one H1 heading';
        } elseif ($h1Count > 1) {
            $score -= 5;
            $issues[] = 'Multiple H1 headings found (should be only one)';
            $suggestions[] = 'Use only one H1 heading per page';
        } else {
            $passed[] = 'H1 heading structure is correct';
        }

        if ($h2Count == 0) {
            $score -= 5;
            $issues[] = 'No H2 headings found';
            $suggestions[] = 'Add subheadings (H2) to structure your content';
        } else {
            $passed[] = "Good use of subheadings ({$h2Count} H2 tags)";
        }

        // Image alt text check
        preg_match_all('/<img[^>]+>/i', $content, $images);
        $imagesWithoutAlt = 0;
        foreach ($images[0] as $img) {
            if (!preg_match('/alt=["\'][^"\']*["\']/i', $img) || preg_match('/alt=["\']["\']/i', $img)) {
                $imagesWithoutAlt++;
            }
        }

        if (count($images[0]) > 0) {
            if ($imagesWithoutAlt > 0) {
                $score -= 10;
                $issues[] = "{$imagesWithoutAlt} image(s) missing alt text";
                $suggestions[] = 'Add descriptive alt text to all images';
            } else {
                $passed[] = 'All images have alt text';
            }
        }

        // Slug check
        if ($slug) {
            $slugLength = strlen($slug);
            if ($slugLength > 75) {
                $score -= 5;
                $issues[] = 'URL slug is too long';
                $suggestions[] = 'Shorten the URL slug for better readability';
            } else {
                $passed[] = 'URL slug length is good';
            }

            if (preg_match('/[^a-z0-9\-]/', $slug)) {
                $score -= 5;
                $issues[] = 'URL slug contains special characters';
                $suggestions[] = 'Use only lowercase letters, numbers, and hyphens in URL';
            }
        }

        // Keyword density check (simple check for title words in content)
        $titleWords = array_filter(explode(' ', strtolower($title)), function($word) {
            return strlen($word) > 3;
        });

        $contentLower = strtolower(strip_tags($content));
        $keywordsFound = 0;
        foreach ($titleWords as $word) {
            if (strpos($contentLower, $word) !== false) {
                $keywordsFound++;
            }
        }

        if ($keywordsFound == 0 && count($titleWords) > 0) {
            $score -= 10;
            $issues[] = 'Title keywords not found in content';
            $suggestions[] = 'Include your title keywords naturally in the content';
        } else {
            $passed[] = 'Title keywords appear in content';
        }

        // External links check
        $externalLinks = preg_match_all('/<a[^>]+href=["\']https?:\/\/[^"\']+/i', $content);
        if ($externalLinks > 0) {
            $passed[] = "Content includes {$externalLinks} external link(s)";
        }

        // Ensure score doesn't go below 0
        $score = max(0, $score);

        // Determine grade
        $grade = $this->getGrade($score);

        return [
            'score' => $score,
            'grade' => $grade,
            'issues' => $issues,
            'suggestions' => $suggestions,
            'passed' => $passed,
            'word_count' => $wordCount,
            'readability' => $this->calculateReadability($content),
        ];
    }

    /**
     * Calculate readability score (simplified Flesch Reading Ease)
     */
    protected function calculateReadability($content): string
    {
        $text = strip_tags($content);
        $sentences = preg_split('/[.!?]+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $words = str_word_count($text);
        $syllables = $this->countSyllables($text);

        if (count($sentences) == 0 || $words == 0) {
            return 'Unknown';
        }

        $avgWordsPerSentence = $words / count($sentences);
        $avgSyllablesPerWord = $syllables / $words;

        // Simplified readability score
        $score = 206.835 - 1.015 * $avgWordsPerSentence - 84.6 * $avgSyllablesPerWord;

        if ($score >= 90) return 'Very Easy';
        if ($score >= 80) return 'Easy';
        if ($score >= 70) return 'Fairly Easy';
        if ($score >= 60) return 'Standard';
        if ($score >= 50) return 'Fairly Difficult';
        if ($score >= 30) return 'Difficult';
        return 'Very Difficult';
    }

    /**
     * Count syllables in text (simplified)
     */
    protected function countSyllables($text): int
    {
        $words = str_word_count(strtolower($text), 1);
        $syllables = 0;

        foreach ($words as $word) {
            $syllables += max(1, preg_match_all('/[aeiouy]+/', $word));
        }

        return $syllables;
    }

    /**
     * Get letter grade from score
     */
    protected function getGrade($score): string
    {
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        if ($score >= 60) return 'D';
        return 'F';
    }
}
