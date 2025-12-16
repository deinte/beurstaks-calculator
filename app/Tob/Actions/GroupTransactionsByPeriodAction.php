<?php

declare(strict_types=1);

namespace App\Tob\Actions;

use App\Tob\Data\TobCalculationResult;
use App\Tob\Data\TobPeriodSummary;
use Illuminate\Support\Collection;

/**
 * Group calculated transactions by declaration period.
 *
 * Belgian TOB must be declared per period:
 * - January + February are combined (deadline: end of April)
 * - Each other month is separate (deadline: end of month + 2)
 */
class GroupTransactionsByPeriodAction
{
    /**
     * Group calculation results by period and create summaries.
     *
     * @param  Collection<int, TobCalculationResult>  $results
     * @return Collection<int, TobPeriodSummary>
     */
    public function execute(Collection $results): Collection
    {
        if ($results->isEmpty()) {
            return collect();
        }

        return $results
            // Group by period key (e.g., "2024-01-02" or "2024-03")
            ->groupBy(fn (TobCalculationResult $result) => $result->transaction->getPeriodKey())
            // Create a summary for each period
            ->map(fn (Collection $periodResults, string $periodKey) => TobPeriodSummary::fromResults($periodKey, $periodResults)
            )
            // Sort by period (chronological order)
            ->sortKeys()
            // Re-index the collection
            ->values();
    }
}
