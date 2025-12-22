<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

class SitemapController
{
    public function __invoke(): Response
    {
        $contentPages = $this->getContentPages();

        $staticPages = [
            ['url' => route('home'), 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['url' => route('calculator'), 'priority' => '0.9', 'changefreq' => 'monthly'],
            ['url' => route('tickers'), 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['url' => route('sources'), 'priority' => '0.6', 'changefreq' => 'monthly'],
        ];

        $urlEntries = '';

        foreach ($staticPages as $page) {
            $loc = htmlspecialchars($page['url']);
            $urlEntries .= <<<XML
              <url>
                <loc>{$loc}</loc>
                <changefreq>{$page['changefreq']}</changefreq>
                <priority>{$page['priority']}</priority>
              </url>

            XML;
        }

        foreach ($contentPages as $page) {
            $loc = htmlspecialchars(route('page.show', $page['slug']));
            $urlEntries .= <<<XML
              <url>
                <loc>{$loc}</loc>
                <lastmod>{$page['lastmod']}</lastmod>
                <changefreq>monthly</changefreq>
                <priority>0.7</priority>
              </url>

            XML;
        }

        $xml = <<<XML
            <?xml version="1.0" encoding="UTF-8"?>
            <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
            {$urlEntries}</urlset>
            XML;

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    /**
     * @return array<int, array{slug: string, lastmod: string}>
     */
    private function getContentPages(): array
    {
        $pages = [];
        $path = resource_path('content/pages');

        if (! File::isDirectory($path)) {
            return $pages;
        }

        $files = File::files($path);

        foreach ($files as $file) {
            if ($file->getExtension() !== 'md') {
                continue;
            }

            $slug = $file->getFilenameWithoutExtension();
            $lastmod = date('Y-m-d', $file->getMTime());

            $pages[] = [
                'slug' => $slug,
                'lastmod' => $lastmod,
            ];
        }

        return $pages;
    }
}
