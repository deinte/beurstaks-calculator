<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

class SitemapController
{
    public function __invoke(): Response
    {
        $pages = $this->getContentPages();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Static pages
        $staticPages = [
            ['url' => route('home'), 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['url' => route('calculator'), 'priority' => '0.9', 'changefreq' => 'monthly'],
            ['url' => route('tickers'), 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['url' => route('sources'), 'priority' => '0.6', 'changefreq' => 'monthly'],
        ];

        foreach ($staticPages as $page) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars($page['url']) . '</loc>';
            $xml .= '<changefreq>' . $page['changefreq'] . '</changefreq>';
            $xml .= '<priority>' . $page['priority'] . '</priority>';
            $xml .= '</url>';
        }

        // Content pages from markdown
        foreach ($pages as $page) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars(route('page.show', $page['slug'])) . '</loc>';
            $xml .= '<lastmod>' . $page['lastmod'] . '</lastmod>';
            $xml .= '<changefreq>monthly</changefreq>';
            $xml .= '<priority>0.7</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

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
