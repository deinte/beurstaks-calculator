<?php

declare(strict_types=1);

namespace App\Tob\Services;

use App\Tob\Actions\CalculateTobForTransactionAction;
use App\Tob\Actions\GroupTransactionsByPeriodAction;
use App\Tob\Data\GenericTransaction;
use App\Tob\Data\TobPeriodSummary;
use App\Tob\Enums\TobRate;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

/**
 * Main service for calculating TOB taxes.
 *
 * Orchestrates the calculation flow:
 * 1. Receives transactions and rate mappings
 * 2. Calculates tax for each transaction
 * 3. Groups results by declaration period
 */
class TobCalculatorService
{
    public function __construct(
        private readonly CalculateTobForTransactionAction $calculateAction,
        private readonly GroupTransactionsByPeriodAction $groupAction,
    ) {}

    /**
     * Calculate TOB for a collection of transactions.
     *
     * @param  LazyCollection<int, GenericTransaction>  $transactions
     * @param  array<string, TobRate>  $tickerRates  Ticker => TobRate mapping
     * @return array{summaries: Collection<int, TobPeriodSummary>, unmapped: array<int, string>}
     */
    public function calculate(LazyCollection $transactions, array $tickerRates): array
    {
        $results = collect();
        $unmappedTickers = [];

        foreach ($transactions as $transaction) {
            $rate = $tickerRates[$transaction->ticker] ?? null;

            if ($rate === null) {
                // Track unmapped tickers but continue processing
                $unmappedTickers[$transaction->ticker] = true;

                continue;
            }

            $results->push(
                $this->calculateAction->execute($transaction, $rate)
            );
        }

        return [
            'summaries' => $this->groupAction->execute($results),
            'unmapped' => array_keys($unmappedTickers),
        ];
    }

    /**
     * Extract unique tickers from transactions for rate mapping UI.
     *
     * Returns an array of ticker info including count and total amount.
     *
     * @param  LazyCollection<int, GenericTransaction>  $transactions
     * @return array<int, array{ticker: string, count: int, totalAmount: float}>
     */
    public function extractUniqueTickers(LazyCollection $transactions): array
    {
        $tickers = [];

        foreach ($transactions as $transaction) {
            $ticker = $transaction->ticker;

            if (! isset($tickers[$ticker])) {
                $tickers[$ticker] = [
                    'ticker' => $ticker,
                    'count' => 0,
                    'totalAmount' => 0.0,
                ];
            }

            $tickers[$ticker]['count']++;
            $tickers[$ticker]['totalAmount'] += $transaction->totalAmountEur;
        }

        // Sort by ticker name
        ksort($tickers);

        return array_values($tickers);
    }

    /**
     * Calculate a quick summary without full period grouping.
     *
     * Useful for showing a preview before final calculation.
     *
     * @param  LazyCollection<int, GenericTransaction>  $transactions
     * @param  array<string, TobRate>  $tickerRates
     * @return array{totalTax: float, transactionCount: int, cappedCount: int}
     */
    public function quickSummary(LazyCollection $transactions, array $tickerRates): array
    {
        $totalTax = 0.0;
        $transactionCount = 0;
        $cappedCount = 0;

        foreach ($transactions as $transaction) {
            $rate = $tickerRates[$transaction->ticker] ?? null;

            if ($rate === null) {
                continue;
            }

            $result = $this->calculateAction->execute($transaction, $rate);

            $totalTax += $result->appliedTax;
            $transactionCount++;

            if ($result->capApplied) {
                $cappedCount++;
            }
        }

        return [
            'totalTax' => round($totalTax, 2),
            'transactionCount' => $transactionCount,
            'cappedCount' => $cappedCount,
        ];
    }
}
