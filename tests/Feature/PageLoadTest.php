<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class PageLoadTest extends TestCase
{
    public function test_homepage_loads(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('TOB Calculator');
    }

    public function test_calculator_page_loads(): void
    {
        $response = $this->get(route('calculator'));

        $response->assertStatus(200);
        $response->assertSee('TOB Calculator');
    }

    public function test_ticker_database_page_loads(): void
    {
        $response = $this->get(route('tickers'));

        $response->assertStatus(200);
        $response->assertSee('Ticker');
    }

    public function test_rates_and_caps_info_page_loads(): void
    {
        $response = $this->get(route('page.show', 'rates-and-caps'));

        $response->assertStatus(200);
        $response->assertSee('Tarieven');
    }

    public function test_how_to_declare_info_page_loads(): void
    {
        $response = $this->get(route('page.show', 'how-to-declare'));

        $response->assertStatus(200);
        $response->assertSee('aangeven');
    }

    public function test_revolut_beurstaks_info_page_loads(): void
    {
        $response = $this->get(route('page.show', 'revolut-beurstaks'));

        $response->assertStatus(200);
        $response->assertSee('Revolut');
    }

    public function test_non_existent_info_page_returns_404(): void
    {
        $response = $this->get(route('page.show', 'non-existent-page'));

        $response->assertStatus(404);
    }

    public function test_sitemap_loads(): void
    {
        $response = $this->get(route('sitemap'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
        $response->assertSee('urlset', false);
        $response->assertSee(route('home'), false);
        $response->assertSee(route('calculator'), false);
        $response->assertSee('rates-and-caps', false);
    }
}
