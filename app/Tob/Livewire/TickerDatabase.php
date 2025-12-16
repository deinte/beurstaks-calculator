<?php

declare(strict_types=1);

namespace App\Tob\Livewire;

use App\Tob\Data\TickerSuggestion;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app', [
    'title' => 'Ticker Database - Zoek ETFs en Aandelen | TOB Calculator',
    'description' => 'Doorzoek onze database van 400+ effecten met automatische TOB-tarieven. Vind het juiste tarief voor je ETF of aandeel: 0,12% voor accumulerende ETFs, 0,35% voor aandelen.',
    'schemaType' => 'CollectionPage',
])]
#[Title('Ticker Database - Zoek ETFs en Aandelen | TOB Calculator')]
class TickerDatabase extends Component
{
    private const MAX_SEARCH_RESULTS = 50;

    public string $search = '';

    #[Computed]
    public function results(): array
    {
        if (strlen($this->search) < 1) {
            return [];
        }

        $query = strtoupper(trim($this->search));
        $all = TickerSuggestion::all();
        $results = [];

        foreach ($all as $ticker => $data) {
            if (str_contains($ticker, $query) || str_contains(strtoupper($data['name'] ?? ''), $query)) {
                $results[$ticker] = $data;
                if (count($results) >= self::MAX_SEARCH_RESULTS) {
                    break;
                }
            }
        }

        return $results;
    }

    #[Computed]
    public function totalCount(): int
    {
        return TickerSuggestion::count();
    }

    public function render(): View
    {
        return view('livewire.tob.ticker-database');
    }
}
