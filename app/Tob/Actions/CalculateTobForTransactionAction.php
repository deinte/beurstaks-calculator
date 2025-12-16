<?php

declare(strict_types=1);

namespace App\Tob\Actions;

use App\Tob\Data\GenericTransaction;
use App\Tob\Data\TobCalculationResult;
use App\Tob\Enums\TobRate;

/**
 * Calculate TOB tax for a single transaction.
 *
 * This is the core calculation logic, intentionally simple and readable.
 *
 * The calculation formula:
 * 1. Raw tax = transaction amount × rate
 * 2. Applied tax = min(raw tax, cap)
 *
 * Sources:
 * - FOD Financiën: https://financien.belgium.be/nl/experten_partners/investeerders/taks-op-beursverrichtingen
 */
class CalculateTobForTransactionAction
{
    /**
     * Calculate the TOB for a transaction.
     */
    public function execute(GenericTransaction $transaction, TobRate $rate): TobCalculationResult
    {
        /*
        |----------------------------------------------------------------------
        | Step 1: Calculate raw tax
        |----------------------------------------------------------------------
        |
        | The raw tax is simply: transaction amount × rate
        | We use the absolute value to handle both buys and sells.
        |
        */
        $transactionAmount = abs($transaction->totalAmountEur);
        $rawTax = $transactionAmount * $rate->rate();

        /*
        |----------------------------------------------------------------------
        | Step 2: Apply the cap (plafond)
        |----------------------------------------------------------------------
        |
        | Belgian TOB has a maximum tax per transaction (the "plafond").
        | If the calculated tax exceeds this cap, only the cap amount is due.
        |
        | Caps by rate:
        | - 0.12% rate: €1,300 cap
        | - 0.35% rate: €1,600 cap
        | - 1.32% rate: €4,000 cap
        |
        */
        $cap = $rate->cap();
        $capApplied = $rawTax > $cap;
        $appliedTax = $capApplied ? $cap : $rawTax;

        /*
        |----------------------------------------------------------------------
        | Step 3: Round to 2 decimal places
        |----------------------------------------------------------------------
        |
        | Tax amounts are always rounded to 2 decimal places (cents).
        |
        */
        $calculatedTax = round($rawTax, 2);
        $appliedTax = round($appliedTax, 2);

        return new TobCalculationResult(
            transaction: $transaction,
            rate: $rate,
            calculatedTax: $calculatedTax,
            appliedTax: $appliedTax,
            capApplied: $capApplied,
        );
    }
}
