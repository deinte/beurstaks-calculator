<?php

declare(strict_types=1);

namespace App\Tob\Data;

use App\Tob\Enums\TransactionType;
use Carbon\Carbon;

/**
 * A standardized transaction record.
 *
 * Represents a single buy/sell transaction in a broker-independent format.
 * Mapper classes convert broker-specific data (Revolut, DEGIRO, etc.) into this format.
 */
readonly class GenericTransaction
{
    public function __construct(
        public Carbon $transactionDate,
        public string $ticker,
        public TransactionType $type,
        public float $quantity,
        public float $totalAmountEur,
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Declaration Period Logic
    |--------------------------------------------------------------------------
    |
    | Belgian TOB must be declared within 2 months after the transaction.
    | January and February transactions can be combined into one declaration.
    |
    */

    /**
     * Get the period key for grouping transactions.
     *
     * Format: "YYYY-MM" or "YYYY-01-02" for Jan+Feb combined
     */
    public function getPeriodKey(): string
    {
        $year = $this->transactionDate->year;
        $month = $this->transactionDate->month;

        // January and February are combined into a single period
        // because they share a deadline (end of April)
        if ($month <= 2) {
            return "{$year}-01-02";
        }

        return $this->transactionDate->format('Y-m');
    }

    /**
     * Get a human-readable period label.
     */
    public function getPeriodLabel(): string
    {
        $year = $this->transactionDate->year;
        $month = $this->transactionDate->month;

        if ($month <= 2) {
            return "Januari - Februari {$year}";
        }

        return ucfirst($this->transactionDate->locale('nl')->translatedFormat('F Y'));
    }

    /**
     * Calculate the declaration deadline.
     *
     * The deadline is the last WORKING day of the second month
     * following the transaction month.
     *
     * Example:
     * - January transaction  -> deadline: end of March
     * - February transaction -> deadline: end of April
     * - March transaction    -> deadline: end of May
     */
    public function getDeadline(): Carbon
    {
        $transactionMonth = $this->transactionDate->copy()->startOfMonth();

        // Add 2 months and go to end of that month
        $deadline = $transactionMonth
            ->addMonths(2)
            ->endOfMonth()
            ->startOfDay();

        // If the last day is a weekend, move to Friday
        while ($deadline->isWeekend()) {
            $deadline->subDay();
        }

        return $deadline;
    }

    /**
     * Check if the declaration deadline has passed.
     */
    public function isOverdue(): bool
    {
        return $this->getDeadline()->isPast();
    }

    /**
     * Get days until deadline (negative if overdue).
     */
    public function daysUntilDeadline(): int
    {
        return (int) now()->startOfDay()->diffInDays($this->getDeadline(), false);
    }

    /*
    |--------------------------------------------------------------------------
    | Serialization (for caching)
    |--------------------------------------------------------------------------
    */

    /**
     * Convert to array for caching/serialization.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'transactionDate' => $this->transactionDate->toIso8601String(),
            'ticker' => $this->ticker,
            'type' => $this->type->value,
            'quantity' => $this->quantity,
            'totalAmountEur' => $this->totalAmountEur,
        ];
    }

    /**
     * Create from array (for cache retrieval).
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            transactionDate: Carbon::parse($data['transactionDate']),
            ticker: $data['ticker'],
            type: TransactionType::from($data['type']),
            quantity: (float) $data['quantity'],
            totalAmountEur: (float) $data['totalAmountEur'],
        );
    }
}
