# Revolut TOB Calculator - Architecture & Implementation Plan

This document outlines the complete architecture and implementation plan for the Belgian TOB (Tax on Stock Market Transactions) Calculator for Revolut users. The application will be built using the Laravel & Livewire stack (the "TALL" stack).

## 1. Core Philosophy

- **Stack**: Use Laravel 12 and Livewire 3 to create a fast, modern, and interactive application with a PHP-centric backend.
- **Processing**: All financial calculations will be performed on the **server-side** in PHP. This centralizes logic and prepares for a future API.
- **Content**: Informational pages will be driven by simple **Markdown files** with caching, making content easy to update.
- **Trust**: The application will be transparent. A clear disclaimer will be visible, and content pages will cite their sources via YAML front matter in the Markdown files.
- **User Experience**: Livewire's `wire:navigate` feature will be used to provide a fast, single-page application (SPA) feel without the complexity of a full JavaScript framework.
- **Performance**: Memory-efficient streaming for file processing, result pagination, and content caching.
- **Security**: File upload validation, MIME type checking, rate limiting, and immediate file deletion after processing.

## 2. Technology Stack

- **PHP**: 8.4+
- **Backend**: Laravel 12
- **Frontend**: Livewire 3+ & Alpine.js
- **Styling**: Tailwind CSS 4
- **Dependencies**:
    - `livewire/livewire`: Core component framework.
    - `spatie/simple-excel`: Memory-efficient streaming for reading/writing Excel and CSV files (~3-8MB memory even for large files).
    - `league/commonmark`: To parse Markdown content for the informational pages.
    - `symfony/yaml`: To parse the YAML front matter from Markdown files.

## 3. Application Architecture

### 3.1. Directory Structure (Domain-Driven)

```
revolut-tob/
├── app/
│   ├── Tob/                                # TOB calculation domain
│   │   ├── Actions/                        # Single-purpose business logic
│   │   │   ├── CalculateTobForTransactionAction.php
│   │   │   ├── ParseRevolutFileAction.php
│   │   │   ├── GroupTransactionsByPeriodAction.php
│   │   │   └── ExportTobResultsAction.php
│   │   ├── Data/                           # DTOs
│   │   │   ├── GenericTransaction.php
│   │   │   ├── TobCalculationResult.php
│   │   │   └── TobPeriodSummary.php
│   │   ├── Enums/                          # PHP 8.4 backed enums
│   │   │   ├── TransactionType.php
│   │   │   └── TobRate.php
│   │   ├── Livewire/
│   │   │   └── Calculator.php
│   │   ├── Mappers/
│   │   │   ├── TransactionMapperInterface.php
│   │   │   └── RevolutMapper.php
│   │   └── Services/
│   │       └── TobCalculatorService.php
│   ├── Content/                            # Content management domain
│   │   ├── Actions/
│   │   │   └── ParseMarkdownPageAction.php
│   │   ├── Data/
│   │   │   └── MarkdownPage.php
│   │   └── Controllers/
│   │       └── ShowPageController.php
│   └── Http/
│       └── Controllers/
│           └── ShowHomepageController.php
├── resources/
│   ├── content/
│   │   └── pages/
│   │       ├── what-is-tob.md
│   │       ├── how-to-declare.md
│   │       └── rates-and-caps.md
│   └── views/
│       ├── components/
│       │   ├── layouts/
│       │   │   └── app.blade.php
│       │   ├── button.blade.php
│       │   ├── card.blade.php
│       │   └── alert.blade.php
│       ├── livewire/
│       │   └── tob/
│       │       └── calculator.blade.php
│       ├── content/
│       │   └── show.blade.php
│       └── home.blade.php
├── config/
│   └── tob.php                             # TOB-specific configuration
└── routes/
    └── web.php
```

### 3.2. Routing

```php
// routes/web.php
use App\Http\Controllers\ShowHomepageController;
use App\Content\Controllers\ShowPageController;
use App\Tob\Livewire\Calculator;
use Illuminate\Support\Facades\Route;

// Homepage
Route::get('/', ShowHomepageController::class)->name('home');

// Calculator (Livewire full-page component)
Route::get('/calculator', Calculator::class)
    ->middleware(['throttle:calculator'])
    ->name('calculator');

// Informational pages (Markdown-driven)
Route::get('/info/{slug}', ShowPageController::class)
    ->where('slug', '[a-z0-9-]+')
    ->name('page.show');
```

### 3.3. Configuration

```php
// config/tob.php
return [
    'rates' => [
        'low' => [
            'value' => 0.0012,
            'cap' => 1300,
            'label' => '0,12%',
            'description' => 'Accumulerende ETFs/fondsen geregistreerd in EER, obligaties, GVV',
        ],
        'medium' => [
            'value' => 0.0035,
            'cap' => 1600,
            'label' => '0,35%',
            'description' => 'Individuele aandelen, distribuerende ETFs',
        ],
        'high' => [
            'value' => 0.0132,
            'cap' => 4000,
            'label' => '1,32%',
            'description' => 'Beleggingsfondsen NIET geregistreerd in EER',
        ],
    ],

    'file_upload' => [
        'max_size_kb' => 10240,          // 10MB
        'allowed_extensions' => ['xlsx', 'csv'],
        'allowed_mimes' => [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/csv',
            'text/plain',
        ],
    ],

    'pagination' => [
        'results_per_page' => 50,
    ],

    'cache' => [
        'markdown_ttl' => 86400,          // 24 hours
    ],
];
```

## 4. Core Components

### 4.1. PHP 8.4 Enums

```php
// app/Tob/Enums/TransactionType.php
namespace App\Tob\Enums;

enum TransactionType: string
{
    case BUY = 'BUY';
    case SELL = 'SELL';

    public function isTaxable(): bool
    {
        return true; // Both BUY and SELL are taxable
    }

    public function label(): string
    {
        return match($this) {
            self::BUY => 'Aankoop',
            self::SELL => 'Verkoop',
        };
    }
}
```

```php
// app/Tob/Enums/TobRate.php
namespace App\Tob\Enums;

enum TobRate: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';

    public function rate(): float
    {
        return match($this) {
            self::LOW => 0.0012,
            self::MEDIUM => 0.0035,
            self::HIGH => 0.0132,
        };
    }

    public function cap(): int
    {
        return match($this) {
            self::LOW => 1300,
            self::MEDIUM => 1600,
            self::HIGH => 4000,
        };
    }

    public function percentage(): string
    {
        return match($this) {
            self::LOW => '0,12%',
            self::MEDIUM => '0,35%',
            self::HIGH => '1,32%',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::LOW => 'Accumulerende ETFs, obligaties, GVV (max €1.300)',
            self::MEDIUM => 'Aandelen, distribuerende ETFs (max €1.600)',
            self::HIGH => 'Niet-EER fondsen (max €4.000)',
        };
    }

    public static function options(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'label' => $case->percentage(),
            'description' => $case->description(),
        ], self::cases());
    }
}
```

### 4.2. Data Transfer Objects

```php
// app/Tob/Data/GenericTransaction.php
namespace App\Tob\Data;

use App\Tob\Enums\TransactionType;
use Carbon\Carbon;

readonly class GenericTransaction
{
    public function __construct(
        public Carbon $transactionDate,
        public string $ticker,
        public TransactionType $type,
        public float $quantity,
        public float $totalAmountEur,
    ) {}

    public function getPeriodKey(): string
    {
        $year = $this->transactionDate->year;
        $month = $this->transactionDate->month;

        // Group Jan-Feb together as they share a deadline
        if ($month <= 2) {
            return "{$year}-01-02";
        }

        return $this->transactionDate->format('Y-m');
    }

    public function getDeadline(): Carbon
    {
        // Deadline is last working day of 2nd month after transaction
        $deadline = $this->transactionDate->copy()
            ->addMonths(2)
            ->endOfMonth();

        // If weekend, go to Friday
        while ($deadline->isWeekend()) {
            $deadline->subDay();
        }

        return $deadline;
    }
}
```

```php
// app/Tob/Data/TobCalculationResult.php
namespace App\Tob\Data;

use App\Tob\Enums\TobRate;

readonly class TobCalculationResult
{
    public function __construct(
        public GenericTransaction $transaction,
        public TobRate $rate,
        public float $calculatedTax,
        public float $appliedTax,        // After cap
        public bool $capApplied,
    ) {}
}
```

```php
// app/Tob/Data/TobPeriodSummary.php
namespace App\Tob\Data;

use Carbon\Carbon;
use Illuminate\Support\Collection;

readonly class TobPeriodSummary
{
    public function __construct(
        public string $periodKey,
        public string $periodLabel,
        public Carbon $deadline,
        public float $totalTax,
        public int $transactionCount,
        /** @var Collection<TobCalculationResult> */
        public Collection $results,
    ) {}

    public static function fromResults(string $periodKey, Collection $results): self
    {
        $firstResult = $results->first();
        $deadline = $firstResult->transaction->getDeadline();

        // Create human-readable period label
        $parts = explode('-', $periodKey);
        $year = $parts[0];

        if (count($parts) === 3) {
            $periodLabel = "Januari - Februari {$year}";
        } else {
            $month = Carbon::createFromFormat('Y-m', $periodKey)->translatedFormat('F Y');
            $periodLabel = ucfirst($month);
        }

        return new self(
            periodKey: $periodKey,
            periodLabel: $periodLabel,
            deadline: $deadline,
            totalTax: $results->sum('appliedTax'),
            transactionCount: $results->count(),
            results: $results,
        );
    }
}
```

### 4.3. Mapper Interface & Implementation

```php
// app/Tob/Mappers/TransactionMapperInterface.php
namespace App\Tob\Mappers;

use App\Tob\Data\GenericTransaction;
use Illuminate\Support\LazyCollection;

interface TransactionMapperInterface
{
    /**
     * Parse a file and return a lazy collection of transactions.
     */
    public function parse(string $filePath): LazyCollection;

    /**
     * Map a single row to a GenericTransaction.
     */
    public function mapRow(array $row): ?GenericTransaction;

    /**
     * Check if a row represents a taxable transaction.
     */
    public function isTaxableRow(array $row): bool;
}
```

```php
// app/Tob/Mappers/RevolutMapper.php
namespace App\Tob\Mappers;

use App\Tob\Data\GenericTransaction;
use App\Tob\Enums\TransactionType;
use Carbon\Carbon;
use Illuminate\Support\LazyCollection;
use Spatie\SimpleExcel\SimpleExcelReader;

class RevolutMapper implements TransactionMapperInterface
{
    private const EXPECTED_HEADERS = [
        'Date', 'Ticker', 'Type', 'Quantity',
        'Price per share', 'Total Amount', 'Currency', 'FX Rate'
    ];

    public function parse(string $filePath): LazyCollection
    {
        return SimpleExcelReader::create($filePath)
            ->getRows()
            ->filter(fn(array $row) => $this->isTaxableRow($row))
            ->map(fn(array $row) => $this->mapRow($row))
            ->filter(); // Remove nulls
    }

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
                quantity: (float) ($row['Quantity'] ?? 0),
                totalAmountEur: $this->parseAmountToEur($row),
            );
        } catch (\Exception $e) {
            // Log and skip malformed rows
            logger()->warning('Failed to parse Revolut row', [
                'row' => $row,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    public function isTaxableRow(array $row): bool
    {
        $type = $row['Type'] ?? '';
        return str_starts_with($type, 'BUY') || str_starts_with($type, 'SELL');
    }

    private function parseTransactionType(string $type): ?TransactionType
    {
        if (str_starts_with($type, 'BUY')) {
            return TransactionType::BUY;
        }

        if (str_starts_with($type, 'SELL')) {
            return TransactionType::SELL;
        }

        return null;
    }

    private function parseDate(string $dateString): Carbon
    {
        // Revolut format: "2024-01-15T14:30:00.000Z" or "2024-01-15"
        return Carbon::parse($dateString)->startOfDay();
    }

    private function sanitizeTicker(string $ticker): string
    {
        // Remove any non-alphanumeric characters except dots and hyphens
        $ticker = preg_replace('/[^A-Za-z0-9.\-]/', '', $ticker);
        return strtoupper(trim($ticker));
    }

    private function parseAmountToEur(array $row): float
    {
        $totalAmount = $row['Total Amount'] ?? '0';
        $currency = $row['Currency'] ?? 'EUR';
        $fxRate = (float) ($row['FX Rate'] ?? 1);

        // Parse amount (may contain currency symbol)
        $amount = (float) preg_replace('/[^0-9.\-]/', '', $totalAmount);

        // Convert to EUR if needed
        if ($currency !== 'EUR' && $fxRate > 0) {
            $amount = $amount / $fxRate;
        }

        return abs($amount);
    }
}
```

### 4.4. Action Classes

```php
// app/Tob/Actions/CalculateTobForTransactionAction.php
namespace App\Tob\Actions;

use App\Tob\Data\GenericTransaction;
use App\Tob\Data\TobCalculationResult;
use App\Tob\Enums\TobRate;

class CalculateTobForTransactionAction
{
    public function execute(GenericTransaction $transaction, TobRate $rate): TobCalculationResult
    {
        $calculatedTax = $transaction->totalAmountEur * $rate->rate();
        $cap = $rate->cap();
        $appliedTax = min($calculatedTax, $cap);

        return new TobCalculationResult(
            transaction: $transaction,
            rate: $rate,
            calculatedTax: round($calculatedTax, 2),
            appliedTax: round($appliedTax, 2),
            capApplied: $calculatedTax > $cap,
        );
    }
}
```

```php
// app/Tob/Actions/GroupTransactionsByPeriodAction.php
namespace App\Tob\Actions;

use App\Tob\Data\TobCalculationResult;
use App\Tob\Data\TobPeriodSummary;
use Illuminate\Support\Collection;

class GroupTransactionsByPeriodAction
{
    /**
     * @param Collection<TobCalculationResult> $results
     * @return Collection<TobPeriodSummary>
     */
    public function execute(Collection $results): Collection
    {
        return $results
            ->groupBy(fn(TobCalculationResult $result) => $result->transaction->getPeriodKey())
            ->map(fn(Collection $periodResults, string $periodKey) =>
                TobPeriodSummary::fromResults($periodKey, $periodResults)
            )
            ->sortKeys()
            ->values();
    }
}
```

```php
// app/Tob/Actions/ExportTobResultsAction.php
namespace App\Tob\Actions;

use App\Tob\Data\TobPeriodSummary;
use Illuminate\Support\Collection;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ExportTobResultsAction
{
    /**
     * @param Collection<TobPeriodSummary> $summaries
     */
    public function execute(Collection $summaries, string $format = 'xlsx'): string
    {
        $filename = 'tob-berekening-' . now()->format('Y-m-d-His') . '.' . $format;
        $path = storage_path('app/exports/' . $filename);

        // Ensure directory exists
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $writer = SimpleExcelWriter::create($path);

        // Summary sheet header
        $writer->addRow([
            'Periode',
            'Deadline',
            'Aantal Transacties',
            'Totaal TOB (EUR)',
        ]);

        // Summary rows
        foreach ($summaries as $summary) {
            $writer->addRow([
                $summary->periodLabel,
                $summary->deadline->format('d/m/Y'),
                $summary->transactionCount,
                number_format($summary->totalTax, 2, ',', '.'),
            ]);
        }

        // Empty row separator
        $writer->addRow([]);
        $writer->addRow(['--- Detailoverzicht ---']);
        $writer->addRow([]);

        // Detail header
        $writer->addRow([
            'Periode',
            'Datum',
            'Ticker',
            'Type',
            'Bedrag (EUR)',
            'Tarief',
            'TOB (EUR)',
            'Plafond Toegepast',
        ]);

        // Detail rows
        foreach ($summaries as $summary) {
            foreach ($summary->results as $result) {
                $writer->addRow([
                    $summary->periodLabel,
                    $result->transaction->transactionDate->format('d/m/Y'),
                    $result->transaction->ticker,
                    $result->transaction->type->label(),
                    number_format($result->transaction->totalAmountEur, 2, ',', '.'),
                    $result->rate->percentage(),
                    number_format($result->appliedTax, 2, ',', '.'),
                    $result->capApplied ? 'Ja' : 'Nee',
                ]);
            }
        }

        $writer->close();

        return $path;
    }
}
```

### 4.5. Calculator Service

```php
// app/Tob/Services/TobCalculatorService.php
namespace App\Tob\Services;

use App\Tob\Actions\CalculateTobForTransactionAction;
use App\Tob\Actions\GroupTransactionsByPeriodAction;
use App\Tob\Data\GenericTransaction;
use App\Tob\Data\TobPeriodSummary;
use App\Tob\Enums\TobRate;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

class TobCalculatorService
{
    public function __construct(
        private readonly CalculateTobForTransactionAction $calculateAction,
        private readonly GroupTransactionsByPeriodAction $groupAction,
    ) {}

    /**
     * @param LazyCollection<GenericTransaction> $transactions
     * @param array<string, TobRate> $tickerRates  // ticker => TobRate enum
     * @return array{summaries: Collection<TobPeriodSummary>, unmapped: array<string>}
     */
    public function calculate(LazyCollection $transactions, array $tickerRates): array
    {
        $results = collect();
        $unmappedTickers = [];

        foreach ($transactions as $transaction) {
            $rate = $tickerRates[$transaction->ticker] ?? null;

            if ($rate === null) {
                $unmappedTickers[$transaction->ticker] = true;
                continue;
            }

            $results->push(
                $this->calculateAction->execute($transaction, $rate)
            );
        }

        return [
            'summaries' => $this->groupAction->execute($results),
            'unmapped' => array_keys($unmappedTickers),
        ];
    }

    /**
     * Extract unique tickers from transactions for rate mapping.
     */
    public function extractUniqueTickers(LazyCollection $transactions): array
    {
        $tickers = [];

        foreach ($transactions as $transaction) {
            $ticker = $transaction->ticker;
            if (!isset($tickers[$ticker])) {
                $tickers[$ticker] = [
                    'ticker' => $ticker,
                    'count' => 0,
                    'totalAmount' => 0,
                    'firstDate' => $transaction->transactionDate,
                ];
            }
            $tickers[$ticker]['count']++;
            $tickers[$ticker]['totalAmount'] += $transaction->totalAmountEur;
        }

        return array_values($tickers);
    }
}
```

### 4.6. Livewire Calculator Component

```php
// app/Tob/Livewire/Calculator.php
namespace App\Tob\Livewire;

use App\Tob\Actions\ExportTobResultsAction;
use App\Tob\Data\TobPeriodSummary;
use App\Tob\Enums\TobRate;
use App\Tob\Mappers\RevolutMapper;
use App\Tob\Services\TobCalculatorService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('TOB Calculator - Bereken je Belgische Beurstaks')]
class Calculator extends Component
{
    use WithFileUploads;
    use WithPagination;

    // File upload
    #[Validate('required|file|mimes:xlsx,csv|max:10240')]
    public $file;

    // Processing state
    public bool $fileProcessed = false;
    public array $uniqueTickers = [];
    public array $tickerRates = [];

    // Results
    public bool $calculated = false;
    /** @var Collection<TobPeriodSummary>|null */
    public ?Collection $summaries = null;
    public float $grandTotal = 0;
    public array $unmappedTickers = [];

    // Errors
    public ?string $processingError = null;

    // Pagination
    public int $perPage = 50;

    public function updatedFile(): void
    {
        $this->resetState();
        $this->validateOnly('file');
    }

    public function processFile(): void
    {
        $this->validate();
        $this->processingError = null;

        try {
            $mapper = new RevolutMapper();
            $filePath = $this->file->getRealPath();

            // Parse file and extract unique tickers
            $transactions = $mapper->parse($filePath);
            $service = app(TobCalculatorService::class);

            $this->uniqueTickers = $service->extractUniqueTickers($transactions);
            $this->fileProcessed = true;

            // Initialize ticker rates array
            foreach ($this->uniqueTickers as $ticker) {
                $this->tickerRates[$ticker['ticker']] = null;
            }

        } catch (\Exception $e) {
            $this->processingError = 'Er ging iets mis bij het verwerken van je bestand. Controleer of het een geldig Revolut transactiebestand is.';
            logger()->error('File processing failed', [
                'error' => $e->getMessage(),
                'file' => $this->file?->getClientOriginalName(),
            ]);
        }
    }

    public function setAllRates(string $rateValue): void
    {
        $rate = TobRate::tryFrom($rateValue);
        if ($rate) {
            foreach ($this->tickerRates as $ticker => $currentRate) {
                if ($currentRate === null) {
                    $this->tickerRates[$ticker] = $rateValue;
                }
            }
        }
    }

    public function calculate(): void
    {
        // Validate all tickers have rates
        $unmapped = array_filter($this->tickerRates, fn($rate) => $rate === null);
        if (!empty($unmapped)) {
            $this->processingError = 'Gelieve een tarief toe te kennen aan alle tickers.';
            return;
        }

        try {
            $mapper = new RevolutMapper();
            $transactions = $mapper->parse($this->file->getRealPath());

            // Convert string rates to TobRate enums
            $rateEnums = array_map(
                fn($rate) => TobRate::from($rate),
                $this->tickerRates
            );

            $service = app(TobCalculatorService::class);
            $result = $service->calculate($transactions, $rateEnums);

            $this->summaries = $result['summaries'];
            $this->unmappedTickers = $result['unmapped'];
            $this->grandTotal = $this->summaries->sum('totalTax');
            $this->calculated = true;
            $this->processingError = null;

        } catch (\Exception $e) {
            $this->processingError = 'Er ging iets mis bij de berekening. Probeer opnieuw.';
            logger()->error('Calculation failed', ['error' => $e->getMessage()]);
        }
    }

    public function export(string $format = 'xlsx')
    {
        if (!$this->calculated || $this->summaries === null) {
            return;
        }

        $exporter = app(ExportTobResultsAction::class);
        $path = $exporter->execute($this->summaries, $format);

        return response()->download($path)->deleteFileAfterSend();
    }

    public function resetCalculator(): void
    {
        $this->resetState();
        $this->file = null;
    }

    private function resetState(): void
    {
        $this->fileProcessed = false;
        $this->uniqueTickers = [];
        $this->tickerRates = [];
        $this->calculated = false;
        $this->summaries = null;
        $this->grandTotal = 0;
        $this->unmappedTickers = [];
        $this->processingError = null;
    }

    #[Computed]
    public function rateOptions(): array
    {
        return TobRate::options();
    }

    #[Computed]
    public function mappedCount(): int
    {
        return count(array_filter($this->tickerRates, fn($rate) => $rate !== null));
    }

    #[Computed]
    public function totalTickerCount(): int
    {
        return count($this->uniqueTickers);
    }

    #[Computed]
    public function allTickersMapped(): bool
    {
        return $this->mappedCount === $this->totalTickerCount && $this->totalTickerCount > 0;
    }

    public function render()
    {
        return view('livewire.tob.calculator');
    }
}
```

### 4.7. Content Pages with Caching

```php
// app/Content/Actions/ParseMarkdownPageAction.php
namespace App\Content\Actions;

use App\Content\Data\MarkdownPage;
use Illuminate\Support\Facades\Cache;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Environment\Environment;
use Symfony\Component\Yaml\Yaml;

class ParseMarkdownPageAction
{
    public function execute(string $slug): ?MarkdownPage
    {
        $filePath = resource_path("content/pages/{$slug}.md");

        if (!file_exists($filePath)) {
            return null;
        }

        // Cache key includes file modification time for auto-invalidation
        $fileModTime = filemtime($filePath);
        $cacheKey = "markdown-page:{$slug}:{$fileModTime}";
        $cacheTtl = config('tob.cache.markdown_ttl', 86400);

        return Cache::remember($cacheKey, $cacheTtl, fn() => $this->parseFile($filePath, $slug));
    }

    private function parseFile(string $path, string $slug): MarkdownPage
    {
        $content = file_get_contents($path);

        // Split front matter and body
        if (!preg_match('/^---\s*\n(.*?)\n---\s*\n(.*)$/s', $content, $matches)) {
            throw new \RuntimeException("Invalid markdown file format: {$path}");
        }

        $frontMatter = Yaml::parse($matches[1]);
        $markdown = $matches[2];

        // Configure CommonMark with table support
        $environment = new Environment([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
        $environment->addExtension(new TableExtension());

        $converter = new CommonMarkConverter([], $environment);
        $html = $converter->convert($markdown)->getContent();

        return new MarkdownPage(
            slug: $slug,
            title: $frontMatter['title'] ?? 'Untitled',
            content: $html,
            lastUpdated: isset($frontMatter['last_updated'])
                ? \Carbon\Carbon::parse($frontMatter['last_updated'])
                : null,
            sources: $frontMatter['sources'] ?? [],
        );
    }
}
```

```php
// app/Content/Data/MarkdownPage.php
namespace App\Content\Data;

use Carbon\Carbon;

readonly class MarkdownPage
{
    public function __construct(
        public string $slug,
        public string $title,
        public string $content,
        public ?Carbon $lastUpdated,
        public array $sources,
    ) {}
}
```

```php
// app/Content/Controllers/ShowPageController.php
namespace App\Content\Controllers;

use App\Content\Actions\ParseMarkdownPageAction;
use Illuminate\Http\Request;

class ShowPageController
{
    public function __invoke(string $slug, ParseMarkdownPageAction $action)
    {
        $page = $action->execute($slug);

        if (!$page) {
            abort(404);
        }

        return view('content.show', ['page' => $page]);
    }
}
```

## 5. Security Measures

### 5.1. File Upload Validation

```php
// In Calculator Livewire component - already included above
// Additional middleware for rate limiting

// app/Http/Middleware/ThrottleCalculator.php
namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ThrottleRequests;

class ThrottleCalculator extends ThrottleRequests
{
    protected function resolveRequestSignature($request)
    {
        return sha1(
            $request->ip() . '|calculator'
        );
    }
}

// Register in bootstrap/app.php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'throttle:calculator' => \App\Http\Middleware\ThrottleCalculator::class,
    ]);
})
```

### 5.2. Security Headers

```php
// app/Http/Middleware/SecurityHeaders.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        return $response;
    }
}
```

## 6. UI/UX Design

### 6.1. Homepage Sections

1. **Hero Section**
   - Headline: "Bereken je Belgische Beurstaks voor Revolut"
   - Sub-headline explaining the problem and solution
   - Primary CTA: "Start de Calculator"

2. **Trust Badges**
   - 100% Privacy: Files processed locally, deleted immediately
   - Gratis & Open Source
   - Gebaseerd op Officiële Bronnen (FOD Financiën, Wikifin)

3. **How It Works (3 Steps)**
   - Upload je bestand
   - Ken tarieven toe
   - Krijg je overzicht

4. **Knowledge Base Cards**
   - Link to Markdown content pages

### 6.2. Calculator Flow

1. **Step 1: Upload**
   - Drag & drop zone
   - File validation feedback
   - Progress indicator

2. **Step 2: Rate Assignment**
   - Table with unique tickers
   - Dropdown per ticker
   - Bulk assign buttons
   - Search/filter (Alpine.js)

3. **Step 3: Results**
   - Summary cards per period
   - Grand total prominently displayed
   - Expandable transaction details
   - Export buttons (Excel, CSV)
   - Print-friendly view

### 6.3. Accessibility Requirements

- All interactive elements keyboard accessible
- ARIA labels on form inputs
- Color contrast ratio ≥ 4.5:1
- Focus indicators visible
- Loading states announced to screen readers
- Mobile touch targets ≥ 44×44px

## 7. Performance Targets

| Metric | Target |
|--------|--------|
| File processing (5000 tx) | < 3 seconds |
| Memory usage | < 50MB |
| Initial page load | < 200ms |
| Livewire interaction | < 300ms |
| Cached content page | < 50ms |

## 8. Testing Strategy

### 8.1. Unit Tests
- `TobRateTest`: Enum values, caps, percentages
- `TransactionTypeTest`: Taxable check, labels
- `GenericTransactionTest`: Period key, deadline calculation
- `CalculateTobForTransactionActionTest`: Tax calculation, cap application
- `RevolutMapperTest`: Row parsing, type detection, amount conversion

### 8.2. Feature Tests
- Calculator file upload flow
- Rate assignment and calculation
- Export functionality
- Content page rendering
- Error handling scenarios

### 8.3. Browser Tests (Dusk)
- Full calculator workflow
- Mobile responsive testing
- Accessibility testing

## 9. Deployment Checklist

- [ ] PHP 8.4+ configured
- [ ] Composer dependencies installed
- [ ] Environment variables set
- [ ] Storage directories writable
- [ ] Cache configured (file/redis)
- [ ] Rate limiting configured
- [ ] SSL certificate installed
- [ ] Error monitoring (Sentry/Flare)

## 10. TOB Reference Rates

| Rate | Percentage | Cap | Applies To |
|------|------------|-----|------------|
| LOW | 0,12% | €1.300 | Accumulerende ETFs/fondsen (EER), obligaties, GVV |
| MEDIUM | 0,35% | €1.600 | Individuele aandelen, distribuerende ETFs |
| HIGH | 1,32% | €4.000 | Beleggingsfondsen NIET geregistreerd in EER |

**Sources:**
- FOD Financiën: https://financien.belgium.be/nl/experten_partners/investeerders/taks-op-beursverrichtingen
- Wikifin (FSMA): https://www.wikifin.be/nl/belasting-werk-en-inkomen/belastingaangifte/je-roerend-inkomen/de-belastingen-op-je-belgische
