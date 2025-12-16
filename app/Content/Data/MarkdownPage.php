<?php

declare(strict_types=1);

namespace App\Content\Data;

use Carbon\Carbon;

/**
 * Represents a parsed Markdown content page.
 */
readonly class MarkdownPage
{
    public function __construct(
        public string $slug,
        public string $title,
        public ?string $description,
        public string $content,
        public ?Carbon $lastUpdated,
        /** @var array<int, array{name: string, url: string}> */
        public array $sources,
        /** @var array<int, array{question: string, answer: string}> */
        public array $faqs = [],
    ) {}
}
