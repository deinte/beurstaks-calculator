<?php

declare(strict_types=1);

namespace App\Tob\Mappers;

use App\Tob\Data\GenericTransaction;
use Illuminate\Support\LazyCollection;

/**
 * Interface for broker-specific transaction mappers.
 *
 * Each broker (Revolut, DEGIRO, etc.) has its own export format.
 * Mappers convert these formats into GenericTransaction objects.
 */
interface TransactionMapperInterface
{
    /**
     * Parse a file and return a lazy collection of transactions.
     *
     * Uses LazyCollection to handle large files without memory issues.
     *
     * @return LazyCollection<int, GenericTransaction>
     */
    public function parse(string $filePath): LazyCollection;

    /**
     * Map a single row to a GenericTransaction.
     *
     * @param  array<string, mixed>  $row
     */
    public function mapRow(array $row): ?GenericTransaction;

    /**
     * Check if a row represents a taxable transaction (BUY/SELL).
     *
     * @param  array<string, mixed>  $row
     */
    public function isTaxableRow(array $row): bool;
}
