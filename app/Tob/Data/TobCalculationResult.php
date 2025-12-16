<?php

declare(strict_types=1);

namespace App\Tob\Data;

use App\Tob\Enums\TobRate;

/**
 * Result of a TOB calculation for a single transaction.
 *
 * Contains the original transaction, applied rate, and calculated tax.
 * Tracks whether the cap (plafond) was applied.
 */
readonly class TobCalculationResult
{
    public function __construct(
        public GenericTransaction $transaction,
        public TobRate $rate,
        public float $calculatedTax,    // Raw tax: amount × rate
        public float $appliedTax,       // Actual tax after cap
        public bool $capApplied,
    ) {}

    /**
     * Tax savings due to the cap (0 if cap not applied).
     */
    public function taxSavings(): float
    {
        return $this->capApplied
            ? round($this->calculatedTax - $this->appliedTax, 2)
            : 0.0;
    }

    /**
     * Effective tax rate after cap.
     */
    public function effectiveRate(): float
    {
        if ($this->transaction->totalAmountEur <= 0) {
            return 0.0;
        }

        return round($this->appliedTax / $this->transaction->totalAmountEur, 6);
    }
}
