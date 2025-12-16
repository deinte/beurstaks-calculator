<?php

declare(strict_types=1);

namespace App\Content\Actions;

use App\Content\Data\MarkdownPage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\MarkdownConverter;
use Symfony\Component\Yaml\Yaml;

/**
 * Parse a Markdown file with YAML front matter.
 *
 * Caches the parsed result for performance, with automatic
 * invalidation when the file is modified.
 */
class ParseMarkdownPageAction
{
    public function execute(string $slug): ?MarkdownPage
    {
        $filePath = resource_path("content/pages/{$slug}.md");

        if (! file_exists($filePath)) {
            return null;
        }

        // Cache key includes file modification time for auto-invalidation
        $fileModTime = filemtime($filePath);
        $cacheKey = "markdown-page:{$slug}:{$fileModTime}";
        $cacheTtl = config('tob.cache.markdown_ttl', 86400);

        return Cache::remember(
            $cacheKey,
            $cacheTtl,
            fn () => $this->parseFile($filePath, $slug)
        );
    }

    private function parseFile(string $path, string $slug): MarkdownPage
    {
        $content = file_get_contents($path);

        // Split front matter and body
        if (! preg_match('/^---\s*\n(.*?)\n---\s*\n(.*)$/s', $content, $matches)) {
            // No front matter, use entire content as body
            $frontMatter = [];
            $markdown = $content;
        } else {
            $frontMatter = Yaml::parse($matches[1]) ?? [];
            $markdown = $matches[2];
        }

        // Configure CommonMark with table support
        $environment = new Environment([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new TableExtension);

        $converter = new MarkdownConverter($environment);
        $html = $converter->convert($markdown)->getContent();

        return new MarkdownPage(
            slug: $slug,
            title: $frontMatter['title'] ?? 'Untitled',
            description: $frontMatter['description'] ?? null,
            content: $html,
            lastUpdated: isset($frontMatter['last_updated'])
                ? Carbon::parse($frontMatter['last_updated'])
                : null,
            sources: $frontMatter['sources'] ?? [],
            faqs: $frontMatter['faqs'] ?? [],
        );
    }
}
