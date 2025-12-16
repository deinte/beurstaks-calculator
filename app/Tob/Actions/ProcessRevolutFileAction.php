<?php

declare(strict_types=1);

namespace App\Tob\Actions;

use App\Tob\Data\GenericTransaction;
use App\Tob\Data\TickerInfo;
use App\Tob\Data\TickerSuggestion;
use App\Tob\Mappers\RevolutMapper;
use App\Tob\Services\TobCalculatorService;
use Illuminate\Support\LazyCollection;

/**
 * Process a Revolut transaction file.
 *
 * This action:
 * 1. Parses the uploaded file using RevolutMapper
 * 2. Extracts unique tickers with their transaction counts and amounts
 * 3. Enriches tickers with rate suggestions from the database
 */
class ProcessRevolutFileAction
{
    public function __construct(
        private readonly TobCalculatorService $calculatorService,
    ) {}

    /**
     * Process a Revolut file and return extracted ticker information.
     *
     * @return array{transactions: LazyCollection<int, GenericTransaction>, tickers: array<int, TickerInfo>}
     */
    public function execute(string $filePath): array
    {
        $mapper = new RevolutMapper();
        $transactions = $mapper->parse($filePath);

        $rawTickers = $this->calculatorService->extractUniqueTickers($transactions);

        $tickers = array_map(function (array $ticker): TickerInfo {
            $suggestion = TickerSuggestion::get($ticker['ticker']);

            return new TickerInfo(
                ticker: $ticker['ticker'],
                count: $ticker['count'],
                totalAmount: $ticker['totalAmount'],
                suggestedRate: $suggestion['rate'] ?? null,
                suggestedName: $suggestion['name'] ?? null,
                suggestedType: $suggestion['type'] ?? null,
            );
        }, $rawTickers);

        return [
            'transactions' => $transactions,
            'tickers' => $tickers,
        ];
    }

    /**
     * Initialize ticker rates based on suggestions.
     *
     * @param  array<int, TickerInfo>  $tickers
     * @return array<string, string|null>
     */
    public function initializeRates(array $tickers): array
    {
        $rates = [];

        foreach ($tickers as $ticker) {
            $rates[$ticker->ticker] = $ticker->suggestedRate;
        }

        return $rates;
    }
}
