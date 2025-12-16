<?php

declare(strict_types=1);

namespace App\Tob\Enums;

/**
 * Transaction types that are subject to Belgian TOB tax.
 *
 * Both BUY and SELL transactions are taxable in Belgium.
 * Other transaction types (dividends, splits, etc.) are NOT taxable.
 */
enum TransactionType: string
{
    case BUY = 'BUY';
    case SELL = 'SELL';

    /**
     * All transaction types in this enum are taxable.
     * This method exists for clarity and potential future changes.
     */
    public function isTaxable(): bool
    {
        return true;
    }

    /**
     * Get the Dutch label for display.
     */
    public function label(): string
    {
        return match ($this) {
            self::BUY => 'Aankoop',
            self::SELL => 'Verkoop',
        };
    }

    /**
     * Get the English label for display.
     */
    public function labelEn(): string
    {
        return match ($this) {
            self::BUY => 'Buy',
            self::SELL => 'Sell',
        };
    }
}
