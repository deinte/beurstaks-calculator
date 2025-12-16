<?php

declare(strict_types=1);

namespace App\Tob\Actions;

use App\Tob\Data\TobPeriodSummary;
use Illuminate\Support\Collection;
use Spatie\SimpleExcel\SimpleExcelWriter;

/**
 * Export TOB calculation results to Excel/CSV.
 *
 * Creates a downloadable file with:
 * - Summary per period
 * - Detailed transaction list
 */
class ExportTobResultsAction
{
    /**
     * Export summaries to an Excel or CSV file.
     *
     * @param  Collection<int, TobPeriodSummary>  $summaries
     * @param  string  $format  'xlsx' or 'csv'
     * @return string The path to the generated file
     */
    public function execute(Collection $summaries, string $format = 'xlsx'): string
    {
        $filename = 'tob-berekening-'.now()->format('Y-m-d-His').'.'.$format;
        $path = storage_path('app/exports/'.$filename);

        // Ensure directory exists
        $this->ensureDirectoryExists(dirname($path));

        $writer = SimpleExcelWriter::create($path);

        // Write summary section
        $this->writeSummarySection($writer, $summaries);

        // Write detail section
        $this->writeDetailSection($writer, $summaries);

        $writer->close();

        return $path;
    }

    /**
     * Write the summary section (one row per period).
     *
     * @param  Collection<int, TobPeriodSummary>  $summaries
     */
    private function writeSummarySection(SimpleExcelWriter $writer, Collection $summaries): void
    {
        // Header
        $writer->addRow([
            'SAMENVATTING PER PERIODE',
            '',
            '',
            '',
        ]);

        $writer->addRow([
            'Periode',
            'Deadline',
            'Aantal Transacties',
            'Totaal TOB (EUR)',
        ]);

        // Data rows
        foreach ($summaries as $summary) {
            $writer->addRow([
                $summary->periodLabel,
                $summary->deadline->format('d/m/Y'),
                $summary->transactionCount,
                $this->formatCurrency($summary->totalTax),
            ]);
        }

        // Grand total
        $grandTotal = $summaries->sum('totalTax');
        $totalTransactions = $summaries->sum('transactionCount');

        $writer->addRow([]);
        $writer->addRow([
            'TOTAAL',
            '',
            $totalTransactions,
            $this->formatCurrency($grandTotal),
        ]);
    }

    /**
     * Write the detail section (one row per transaction).
     *
     * @param  Collection<int, TobPeriodSummary>  $summaries
     */
    private function writeDetailSection(SimpleExcelWriter $writer, Collection $summaries): void
    {
        // Separator
        $writer->addRow([]);
        $writer->addRow([]);
        $writer->addRow(['DETAILOVERZICHT']);

        // Header
        $writer->addRow([
            'Periode',
            'Datum',
            'Ticker',
            'Type',
            'Bedrag (EUR)',
            'Tarief',
            'TOB (EUR)',
            'Plafond',
        ]);

        // Data rows
        foreach ($summaries as $summary) {
            foreach ($summary->results as $result) {
                $writer->addRow([
                    $summary->periodLabel,
                    $result->transaction->transactionDate->format('d/m/Y'),
                    $result->transaction->ticker,
                    $result->transaction->type->label(),
                    $this->formatCurrency($result->transaction->totalAmountEur),
                    $result->rate->percentage(),
                    $this->formatCurrency($result->appliedTax),
                    $result->capApplied ? 'Ja' : 'Nee',
                ]);
            }
        }
    }

    /**
     * Format a number as Belgian currency.
     */
    private function formatCurrency(float $amount): string
    {
        return number_format($amount, 2, ',', '.');
    }

    /**
     * Ensure a directory exists.
     */
    private function ensureDirectoryExists(string $directory): void
    {
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
    }
}
