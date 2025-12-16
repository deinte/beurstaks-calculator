<?php

declare(strict_types=1);

namespace App\Tob\Data;

use Livewire\Wireable;

/**
 * A serializable period result for Livewire state.
 */
final class PeriodResult implements Wireable
{
    /**
     * @param  array<int, TransactionResult>  $transactions
     */
    public function __construct(
        public string $periodKey,
        public string $periodLabel,
        public string $deadline,
        public bool $isOverdue,
        public int $daysUntilDeadline,
        public float $totalTax,
        public int $transactionCount,
        public array $transactions,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toLivewire(): array
    {
        return [
            'periodKey' => $this->periodKey,
            'periodLabel' => $this->periodLabel,
            'deadline' => $this->deadline,
            'isOverdue' => $this->isOverdue,
            'daysUntilDeadline' => $this->daysUntilDeadline,
            'totalTax' => $this->totalTax,
            'transactionCount' => $this->transactionCount,
            'transactions' => array_map(
                fn (TransactionResult $tx) => $tx->toLivewire(),
                $this->transactions
            ),
        ];
    }

    /**
     * @param  array<string, mixed>  $value
     */
    public static function fromLivewire(mixed $value): self
    {
        return new self(
            periodKey: $value['periodKey'],
            periodLabel: $value['periodLabel'],
            deadline: $value['deadline'],
            isOverdue: $value['isOverdue'],
            daysUntilDeadline: $value['daysUntilDeadline'],
            totalTax: $value['totalTax'],
            transactionCount: $value['transactionCount'],
            transactions: array_map(
                fn (array $tx) => TransactionResult::fromLivewire($tx),
                $value['transactions']
            ),
        );
    }
}
