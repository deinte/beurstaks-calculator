<?php

declare(strict_types=1);

namespace App\Tob\Data;

use App\Tob\Enums\TobRate;
use Illuminate\Support\Facades\Cache;

/**
 * Ticker suggestions loaded from config/tickers.json.
 *
 * This allows easy editing of known tickers without touching PHP code.
 * The JSON file is cached for performance.
 */
class TickerSuggestion
{
    private const CONFIG_PATH = 'config/tickers.json';

    private const CACHE_KEY = 'tob_tickers';

    private const CACHE_TTL = 86400; // 24 hours

    /**
     * Get all known tickers from the JSON config.
     *
     * @return array<string, array{rate: string, name: string, type: string}>
     */
    public static function all(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            $path = base_path(self::CONFIG_PATH);

            if (! file_exists($path)) {
                return [];
            }

            $content = file_get_contents($path);
            $data = json_decode($content, true);

            return $data['tickers'] ?? [];
        });
    }

    /**
     * Clear the ticker cache (call after editing tickers.json).
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Get suggestion for a ticker.
     *
     * @return array{rate: string, name: string, type: string}|null
     */
    public static function get(string $ticker): ?array
    {
        $ticker = strtoupper(trim($ticker));
        $tickers = self::all();

        return $tickers[$ticker] ?? null;
    }

    /**
     * Get the suggested TobRate enum for a ticker.
     */
    public static function getRate(string $ticker): ?TobRate
    {
        $suggestion = self::get($ticker);

        if ($suggestion === null) {
            return null;
        }

        return TobRate::tryFrom($suggestion['rate']);
    }

    /**
     * Check if we have a suggestion for this ticker.
     */
    public static function has(string $ticker): bool
    {
        return self::get($ticker) !== null;
    }

    /**
     * Get suggestions for multiple tickers.
     *
     * @param  array<string>  $tickers
     * @return array<string, array{rate: string|null, name: string|null, type: string|null, suggested: bool}>
     */
    public static function getMultiple(array $tickers): array
    {
        $result = [];

        foreach ($tickers as $ticker) {
            $ticker = strtoupper(trim($ticker));
            $suggestion = self::get($ticker);

            $result[$ticker] = $suggestion !== null
                ? [...$suggestion, 'suggested' => true]
                : ['rate' => null, 'name' => null, 'type' => null, 'suggested' => false];
        }

        return $result;
    }

    /**
     * Count of known tickers.
     */
    public static function count(): int
    {
        return count(self::all());
    }
}
