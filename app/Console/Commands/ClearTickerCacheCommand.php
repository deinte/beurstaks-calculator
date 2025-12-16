<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Tob\Data\TickerSuggestion;
use Illuminate\Console\Command;

class ClearTickerCacheCommand extends Command
{
    protected $signature = 'tob:clear-tickers';

    protected $description = 'Clear the ticker suggestions cache (run after editing config/tickers.json)';

    public function handle(): int
    {
        TickerSuggestion::clearCache();

        $count = TickerSuggestion::count();

        $this->info("Ticker cache cleared. Loaded {$count} tickers from config/tickers.json");

        return Command::SUCCESS;
    }
}
