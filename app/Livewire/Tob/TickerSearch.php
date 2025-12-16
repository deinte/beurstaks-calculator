<?php

declare(strict_types=1);

namespace App\Livewire\Tob;

use App\Tob\Data\TickerSuggestion;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TickerSearch extends Component
{
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
                if (count($results) >= 20) {
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

    public function render()
    {
        return view('livewire.tob.ticker-search');
    }
}
