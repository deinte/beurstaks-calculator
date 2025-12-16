<?php

declare(strict_types=1);

namespace App\Tob\Data;

use Livewire\Wireable;

/**
 * Information about a unique ticker found in the uploaded file.
 */
final class TickerInfo implements Wireable
{
    public function __construct(
        public string $ticker,
        public int $count,
        public float $totalAmount,
        public ?string $suggestedRate,
        public ?string $suggestedName,
        public ?string $suggestedType,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toLivewire(): array
    {
        return [
            'ticker' => $this->ticker,
            'count' => $this->count,
            'totalAmount' => $this->totalAmount,
            'suggestedRate' => $this->suggestedRate,
            'suggestedName' => $this->suggestedName,
            'suggestedType' => $this->suggestedType,
        ];
    }

    /**
     * @param  array<string, mixed>  $value
     */
    public static function fromLivewire(mixed $value): self
    {
        return new self(
            ticker: $value['ticker'],
            count: $value['count'],
            totalAmount: $value['totalAmount'],
            suggestedRate: $value['suggestedRate'],
            suggestedName: $value['suggestedName'],
            suggestedType: $value['suggestedType'],
        );
    }

    public function hasSuggestion(): bool
    {
        return $this->suggestedRate !== null;
    }
}
