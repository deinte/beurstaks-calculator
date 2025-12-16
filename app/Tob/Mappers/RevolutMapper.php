<?php

declare(strict_types=1);

namespace App\Tob\Mappers;

use App\Tob\Data\GenericTransaction;
use App\Tob\Enums\TransactionType;
use Carbon\Carbon;
use Illuminate\Support\LazyCollection;
use Spatie\SimpleExcel\SimpleExcelReader;

/**
 * Maps Revolut trading export files to GenericTransaction objects.
 *
 * Revolut exports contain these columns:
 * - Date: Transaction date (ISO format)
 * - Ticker: Stock symbol
 * - Type: BUY - MARKET, SELL - MARKET, DIVIDEND, etc.
 * - Quantity: Number of shares
 * - Price per share: Price in original currency
 * - Total Amount: Total value (may include currency symbol)
 * - Currency: Original currency (USD, EUR, etc.)
 * - FX Rate: Exchange rate to EUR (if applicable)
 */
class RevolutMapper implements TransactionMapperInterface
{
    /**
     * Parse a Revolut export file.
     *
     * @return LazyCollection<int, GenericTransaction>
     */
    public function parse(string $filePath): LazyCollection
    {
        return SimpleExcelReader::create($filePath)
            ->getRows()
            ->filter(fn (array $row) => $this->isTaxableRow($row))
            ->map(fn (array $row) => $this->mapRow($row))
            ->filter(); // Remove null values (failed mappings)
    }

    /**
     * Map a single Revolut row to a GenericTransaction.
     */
    public function mapRow(array $row): ?GenericTransaction
    {
        try {
            $type = $this->parseTransactionType($row['Type'] ?? '');

            if ($type === null) {
                return null;
            }

            return new GenericTransaction(
                transactionDate: $this->parseDate($row['Date'] ?? ''),
                ticker: $this->sanitizeTicker($row['Ticker'] ?? ''),
                type: $type,
                quantity: $this->parseQuantity($row['Quantity'] ?? ''),
                totalAmountEur: $this->parseAmountToEur($row),
            );
        } catch (\Throwable $e) {
            // Log and skip malformed rows
            logger()->warning('RevolutMapper: Failed to parse row', [
                'row' => $row,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Check if a row is a taxable transaction (BUY or SELL).
     *
     * Revolut uses formats like:
     * - "BUY - MARKET"
     * - "SELL - MARKET"
     * - "SELL - LIMIT"
     * - "DIVIDEND" (not taxable for TOB)
     * - "STOCK SPLIT" (not taxable for TOB)
     */
    public function isTaxableRow(array $row): bool
    {
        $type = strtoupper(trim($row['Type'] ?? ''));

        return str_starts_with($type, 'BUY') || str_starts_with($type, 'SELL');
    }

    /*
    |--------------------------------------------------------------------------
    | Private Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Parse the transaction type from Revolut's format.
     */
    private function parseTransactionType(string $type): ?TransactionType
    {
        $type = strtoupper(trim($type));

        if (str_starts_with($type, 'BUY')) {
            return TransactionType::BUY;
        }

        if (str_starts_with($type, 'SELL')) {
            return TransactionType::SELL;
        }

        return null;
    }

    /**
     * Parse the transaction date.
     *
     * Revolut formats:
     * - "2024-01-15T14:30:00.000Z" (ISO 8601)
     * - "2024-01-15" (simple date)
     * - "15/01/2024" (European format)
     */
    private function parseDate(string $dateString): Carbon
    {
        $dateString = trim($dateString);

        // Try parsing with Carbon's smart parser
        return Carbon::parse($dateString)->startOfDay();
    }

    /**
     * Sanitize and normalize the ticker symbol.
     */
    private function sanitizeTicker(string $ticker): string
    {
        // Remove any non-alphanumeric characters except dots and hyphens
        $ticker = preg_replace('/[^A-Za-z0-9.\-]/', '', $ticker);

        return strtoupper(trim($ticker));
    }

    /**
     * Parse quantity (handle different number formats).
     */
    private function parseQuantity(string|int|float $quantity): float
    {
        if (is_numeric($quantity)) {
            return (float) $quantity;
        }

        // Handle European format (comma as decimal separator)
        $quantity = str_replace(',', '.', (string) $quantity);

        return (float) $quantity;
    }

    /**
     * Parse the total amount and convert to EUR if needed.
     *
     * Revolut may include currency symbols in the amount field.
     * If the currency is not EUR, we use the FX Rate to convert.
     *
     * @param  array<string, mixed>  $row
     */
    private function parseAmountToEur(array $row): float
    {
        $totalAmount = $row['Total Amount'] ?? $row['Total amount'] ?? '0';
        $currency = strtoupper(trim($row['Currency'] ?? 'EUR'));
        $fxRate = $this->parseFxRate($row['FX Rate'] ?? $row['Fx Rate'] ?? '1');

        // Extract numeric value from amount (remove currency symbols, spaces)
        $amount = $this->extractNumericValue($totalAmount);

        // Convert to EUR if needed
        if ($currency !== 'EUR' && $fxRate > 0) {
            $amount = $amount / $fxRate;
        }

        // Return absolute value (sells may be negative)
        return abs($amount);
    }

    /**
     * Extract numeric value from a string that may contain currency symbols.
     */
    private function extractNumericValue(string|int|float $value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        // Remove everything except digits, dots, hyphens, and commas
        $cleaned = preg_replace('/[^0-9.\-,]/', '', (string) $value);

        // Handle European format (comma as decimal separator)
        // Check if comma is used as decimal separator
        if (preg_match('/,\d{1,2}$/', $cleaned)) {
            $cleaned = str_replace(',', '.', $cleaned);
        } else {
            // Remove thousand separators
            $cleaned = str_replace(',', '', $cleaned);
        }

        return (float) $cleaned;
    }

    /**
     * Parse FX rate (handle different formats).
     */
    private function parseFxRate(string|int|float $rate): float
    {
        if (is_numeric($rate)) {
            return (float) $rate;
        }

        $rate = str_replace(',', '.', (string) $rate);

        return (float) $rate ?: 1.0;
    }
}
