<?php

declare(strict_types=1);

namespace App\Tob\Services;

use Livewire\Component;

/**
 * Service for tracking analytics events via Umami.
 *
 * Dispatches browser events that are handled by Alpine.js
 * to call window.umami.track() on the client side.
 */
class AnalyticsService
{
    private const EVENT_NAME = 'track-analytics';

    /**
     * Track when a file has been processed.
     */
    public function trackFileProcessed(Component $component, int $tickerCount, int $suggestedCount): void
    {
        $this->track($component, 'file-processed', [
            'ticker_count' => $tickerCount,
            'suggested_count' => $suggestedCount,
        ]);
    }

    /**
     * Track when a calculation has been completed.
     */
    public function trackCalculationCompleted(Component $component, float $totalTax, int $tickerCount, int $periodCount): void
    {
        $this->track($component, 'calculation-completed', [
            'total_tax' => round($totalTax, 2),
            'ticker_count' => $tickerCount,
            'period_count' => $periodCount,
        ]);
    }

    /**
     * Track when results are exported.
     */
    public function trackExport(Component $component, string $format): void
    {
        $this->track($component, 'export', [
            'format' => $format,
        ]);
    }

    /**
     * Track when the calculator is reset.
     */
    public function trackReset(Component $component): void
    {
        $this->track($component, 'calculator-reset');
    }

    /**
     * Track when file upload fails validation or processing.
     */
    public function trackFileUploadError(Component $component, string $reason): void
    {
        $this->track($component, 'file-upload-error', [
            'reason' => $reason,
        ]);
    }

    /**
     * Track when bulk rate assignment is used.
     */
    public function trackBulkRateAssigned(Component $component, string $action, ?string $rate = null): void
    {
        $data = ['action' => $action];
        if ($rate !== null) {
            $data['rate'] = $rate;
        }
        $this->track($component, 'bulk-rate-assigned', $data);
    }

    /**
     * Track when user goes back to rates from results.
     */
    public function trackGoBackToRates(Component $component): void
    {
        $this->track($component, 'go-back-to-rates');
    }

    /**
     * Dispatch an analytics event to the browser.
     *
     * @param  array<string, mixed>  $data
     */
    private function track(Component $component, string $eventName, array $data = []): void
    {
        $component->dispatch(self::EVENT_NAME, name: $eventName, data: $data);
    }
}
