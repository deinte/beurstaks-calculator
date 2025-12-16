<?php

declare(strict_types=1);

namespace App\Tob\Data;

use Livewire\Wireable;

/**
 * A serializable transaction result for Livewire state.
 */
final class TransactionResult implements Wireable
{
    public function __construct(
        public string $date,
        public string $ticker,
        public string $type,
        public float $amount,
        public string $rate,
        public string $rateValue,
        public float $tax,
        public bool $capApplied,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toLivewire(): array
    {
        return [
            'date' => $this->date,
            'ticker' => $this->ticker,
            'type' => $this->type,
            'amount' => $this->amount,
            'rate' => $this->rate,
            'rateValue' => $this->rateValue,
            'tax' => $this->tax,
            'capApplied' => $this->capApplied,
        ];
    }

    /**
     * @param  array<string, mixed>  $value
     */
    public static function fromLivewire(mixed $value): self
    {
        return new self(
            date: $value['date'],
            ticker: $value['ticker'],
            type: $value['type'],
            amount: $value['amount'],
            rate: $value['rate'],
            rateValue: $value['rateValue'],
            tax: $value['tax'],
            capApplied: $value['capApplied'],
        );
    }
}
