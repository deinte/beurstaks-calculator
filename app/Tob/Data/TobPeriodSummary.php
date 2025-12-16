<?php

declare(strict_types=1);

namespace App\Tob\Data;

use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Summary of TOB calculations for a declaration period.
 *
 * Groups all transactions and their calculated taxes for a single
 * declaration period (e.g., "March 2025" or "January-February 2025").
 */
readonly class TobPeriodSummary
{
    public function __construct(
        public string $periodKey,
        public string $periodLabel,
        public Carbon $deadline,
        public float $totalTax,
        public int $transactionCount,
        /** @var Collection<int, TobCalculationResult> */
        public Collection $results,
    ) {}

    /**
     * Create a summary from a collection of calculation results.
     *
     * @param  Collection<int, TobCalculationResult>  $results
     */
    public static function fromResults(string $periodKey, Collection $results): self
    {
        $firstResult = $results->first();
        $deadline = $firstResult->transaction->getDeadline();

        return new self(
            periodKey: $periodKey,
            periodLabel: self::formatPeriodLabel($periodKey),
            deadline: $deadline,
            totalTax: round($results->sum('appliedTax'), 2),
            transactionCount: $results->count(),
            results: $results,
        );
    }

    /**
     * Format a period key into a human-readable label.
     */
    private static function formatPeriodLabel(string $periodKey): string
    {
        $parts = explode('-', $periodKey);
        $year = $parts[0];

        // Combined Jan-Feb period
        if (count($parts) === 3) {
            return "Januari - Februari {$year}";
        }

        // Single month period
        $date = Carbon::createFromFormat('Y-m', $periodKey);

        return ucfirst($date->locale('nl')->translatedFormat('F Y'));
    }

    /**
     * Check if the deadline has passed.
     */
    public function isOverdue(): bool
    {
        return $this->deadline->isPast();
    }

    /**
     * Days until deadline (negative if overdue).
     */
    public function daysUntilDeadline(): int
    {
        return (int) now()->startOfDay()->diffInDays($this->deadline, false);
    }

    /**
     * Get total transaction amount for this period.
     */
    public function totalAmount(): float
    {
        return round(
            $this->results->sum(fn (TobCalculationResult $r) => $r->transaction->totalAmountEur),
            2
        );
    }

    /**
     * Count of transactions where cap was applied.
     */
    public function cappedTransactionCount(): int
    {
        return $this->results->filter(fn (TobCalculationResult $r) => $r->capApplied)->count();
    }
}
