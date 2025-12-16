<?php

declare(strict_types=1);

namespace App\Tob\Enums;

/**
 * Belgian TOB (Taks op Beursverrichtingen) tax rates.
 *
 * There are three tax rates in Belgium, each with a maximum cap.
 * The rate that applies depends on the type of financial instrument.
 *
 * IMPORTANT: Keep this in sync with config/tob.php
 *
 * Sources:
 * - FOD Financiën: https://financien.belgium.be/nl/experten_partners/investeerders/taks-op-beursverrichtingen
 * - Wikifin: https://www.wikifin.be/nl/belasting-werk-en-inkomen/belastingaangifte/je-roerend-inkomen/de-belastingen-op-je-belgische
 *
 * Last verified: December 2025
 */
enum TobRate: string
{
    /**
     * LOW RATE: 0.12% (cap €1,300)
     * For: Accumulating ETFs (EEA), bonds, regulated real estate (GVV)
     */
    case LOW = 'low';

    /**
     * MEDIUM RATE: 0.35% (cap €1,600)
     * For: Individual stocks, distributing ETFs
     */
    case MEDIUM = 'medium';

    /**
     * HIGH RATE: 1.32% (cap €4,000)
     * For: Investment funds NOT registered in EEA
     */
    case HIGH = 'high';

    /*
    |--------------------------------------------------------------------------
    | Rate & Cap Values
    |--------------------------------------------------------------------------
    |
    | These are the actual values used in calculations.
    | Update these when Belgian tax law changes!
    |
    */

    /**
     * Get the tax rate as a decimal (e.g., 0.0012 for 0.12%).
     */
    public function rate(): float
    {
        return match ($this) {
            self::LOW => 0.0012,      // 0.12%
            self::MEDIUM => 0.0035,   // 0.35%
            self::HIGH => 0.0132,     // 1.32%
        };
    }

    /**
     * Get the maximum tax cap in EUR per transaction.
     */
    public function cap(): int
    {
        return match ($this) {
            self::LOW => 1300,        // €1,300 maximum
            self::MEDIUM => 1600,     // €1,600 maximum
            self::HIGH => 4000,       // €4,000 maximum
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Display Labels
    |--------------------------------------------------------------------------
    */

    /**
     * Get the percentage as a formatted string (Dutch format).
     */
    public function percentage(): string
    {
        return match ($this) {
            self::LOW => '0,12%',
            self::MEDIUM => '0,35%',
            self::HIGH => '1,32%',
        };
    }

    /**
     * Get a human-readable description (Dutch).
     */
    public function description(): string
    {
        return match ($this) {
            self::LOW => 'Accumulerende ETFs, obligaties, GVV (max €1.300)',
            self::MEDIUM => 'Aandelen, distribuerende ETFs (max €1.600)',
            self::HIGH => 'Niet-EER fondsen (max €4.000)',
        };
    }

    /**
     * Get a short description for tooltips.
     */
    public function shortDescription(): string
    {
        return match ($this) {
            self::LOW => 'ETFs (acc.), obligaties',
            self::MEDIUM => 'Aandelen, ETFs (dist.)',
            self::HIGH => 'Niet-EER fondsen',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Calculate the tax for a given transaction amount.
     * This is the core calculation - intentionally simple and readable.
     */
    public function calculateTax(float $transactionAmount): float
    {
        // Step 1: Calculate the raw tax
        $rawTax = abs($transactionAmount) * $this->rate();

        // Step 2: Apply the cap (plafond)
        $cappedTax = min($rawTax, $this->cap());

        // Step 3: Round to 2 decimal places
        return round($cappedTax, 2);
    }

    /**
     * Check if the cap would be applied for a given amount.
     */
    public function wouldCapApply(float $transactionAmount): bool
    {
        $rawTax = abs($transactionAmount) * $this->rate();

        return $rawTax > $this->cap();
    }

    /**
     * Get the transaction amount threshold where the cap kicks in.
     */
    public function capThreshold(): float
    {
        // Cap threshold = cap / rate
        // Example: €1,300 / 0.0012 = €1,083,333.33
        return round($this->cap() / $this->rate(), 2);
    }

    /**
     * Get all rates as an array for dropdowns/selects.
     *
     * @return array<int, array{value: string, label: string, description: string, cap: int}>
     */
    public static function options(): array
    {
        return array_map(fn (self $case) => [
            'value' => $case->value,
            'label' => $case->percentage(),
            'description' => $case->description(),
            'cap' => $case->cap(),
        ], self::cases());
    }

    /**
     * Get all rates as a simple key-value array.
     *
     * @return array<string, string>
     */
    public static function asSelectOptions(): array
    {
        return array_map(
            fn (self $case) => "{$case->percentage()} - {$case->shortDescription()}",
            array_combine(
                array_map(fn (self $case) => $case->value, self::cases()),
                self::cases()
            )
        );
    }
}
