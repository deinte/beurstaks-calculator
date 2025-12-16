<?php

declare(strict_types=1);

namespace Tests\Unit\Tob\Enums;

use App\Tob\Enums\TobRate;
use PHPUnit\Framework\TestCase;

class TobRateTest extends TestCase
{
    /*
    |--------------------------------------------------------------------------
    | Rate Values
    |--------------------------------------------------------------------------
    */

    public function test_low_rate_is_correct(): void
    {
        $this->assertSame(0.0012, TobRate::LOW->rate());
    }

    public function test_medium_rate_is_correct(): void
    {
        $this->assertSame(0.0035, TobRate::MEDIUM->rate());
    }

    public function test_high_rate_is_correct(): void
    {
        $this->assertSame(0.0132, TobRate::HIGH->rate());
    }

    /*
    |--------------------------------------------------------------------------
    | Cap Values
    |--------------------------------------------------------------------------
    */

    public function test_low_cap_is_1300(): void
    {
        $this->assertSame(1300, TobRate::LOW->cap());
    }

    public function test_medium_cap_is_1600(): void
    {
        $this->assertSame(1600, TobRate::MEDIUM->cap());
    }

    public function test_high_cap_is_4000(): void
    {
        $this->assertSame(4000, TobRate::HIGH->cap());
    }

    /*
    |--------------------------------------------------------------------------
    | Tax Calculations - Low Rate (0.12%)
    |--------------------------------------------------------------------------
    */

    public function test_low_rate_calculates_correctly_for_small_amount(): void
    {
        // €10,000 * 0.12% = €12.00
        $this->assertSame(12.00, TobRate::LOW->calculateTax(10000));
    }

    public function test_low_rate_calculates_correctly_for_medium_amount(): void
    {
        // €100,000 * 0.12% = €120.00
        $this->assertSame(120.00, TobRate::LOW->calculateTax(100000));
    }

    public function test_low_rate_applies_cap_when_exceeded(): void
    {
        // €2,000,000 * 0.12% = €2,400 but cap is €1,300
        $this->assertSame(1300.00, TobRate::LOW->calculateTax(2000000));
    }

    public function test_low_rate_cap_threshold_is_correct(): void
    {
        // Cap kicks in at €1,300 / 0.0012 = €1,083,333.33
        $threshold = TobRate::LOW->capThreshold();
        $this->assertEqualsWithDelta(1083333.33, $threshold, 0.01);
    }

    /*
    |--------------------------------------------------------------------------
    | Tax Calculations - Medium Rate (0.35%)
    |--------------------------------------------------------------------------
    */

    public function test_medium_rate_calculates_correctly_for_small_amount(): void
    {
        // €10,000 * 0.35% = €35.00
        $this->assertSame(35.00, TobRate::MEDIUM->calculateTax(10000));
    }

    public function test_medium_rate_calculates_correctly_for_medium_amount(): void
    {
        // €100,000 * 0.35% = €350.00
        $this->assertSame(350.00, TobRate::MEDIUM->calculateTax(100000));
    }

    public function test_medium_rate_applies_cap_when_exceeded(): void
    {
        // €1,000,000 * 0.35% = €3,500 but cap is €1,600
        $this->assertSame(1600.00, TobRate::MEDIUM->calculateTax(1000000));
    }

    public function test_medium_rate_cap_threshold_is_correct(): void
    {
        // Cap kicks in at €1,600 / 0.0035 = €457,142.86
        $threshold = TobRate::MEDIUM->capThreshold();
        $this->assertEqualsWithDelta(457142.86, $threshold, 0.01);
    }

    /*
    |--------------------------------------------------------------------------
    | Tax Calculations - High Rate (1.32%)
    |--------------------------------------------------------------------------
    */

    public function test_high_rate_calculates_correctly_for_small_amount(): void
    {
        // €10,000 * 1.32% = €132.00
        $this->assertSame(132.00, TobRate::HIGH->calculateTax(10000));
    }

    public function test_high_rate_calculates_correctly_for_medium_amount(): void
    {
        // €100,000 * 1.32% = €1,320.00
        $this->assertSame(1320.00, TobRate::HIGH->calculateTax(100000));
    }

    public function test_high_rate_applies_cap_when_exceeded(): void
    {
        // €500,000 * 1.32% = €6,600 but cap is €4,000
        $this->assertSame(4000.00, TobRate::HIGH->calculateTax(500000));
    }

    public function test_high_rate_cap_threshold_is_correct(): void
    {
        // Cap kicks in at €4,000 / 0.0132 = €303,030.30
        $threshold = TobRate::HIGH->capThreshold();
        $this->assertEqualsWithDelta(303030.30, $threshold, 0.01);
    }

    /*
    |--------------------------------------------------------------------------
    | Edge Cases
    |--------------------------------------------------------------------------
    */

    public function test_handles_zero_amount(): void
    {
        $this->assertSame(0.00, TobRate::LOW->calculateTax(0));
        $this->assertSame(0.00, TobRate::MEDIUM->calculateTax(0));
        $this->assertSame(0.00, TobRate::HIGH->calculateTax(0));
    }

    public function test_handles_negative_amount_as_absolute(): void
    {
        // Should use absolute value
        $this->assertSame(35.00, TobRate::MEDIUM->calculateTax(-10000));
    }

    public function test_rounds_to_two_decimal_places(): void
    {
        // €1,234.56 * 0.35% = €4.32096 -> rounds to €4.32
        $this->assertSame(4.32, TobRate::MEDIUM->calculateTax(1234.56));
    }

    public function test_cap_would_apply_returns_true_when_exceeded(): void
    {
        $this->assertTrue(TobRate::LOW->wouldCapApply(2000000));
        $this->assertTrue(TobRate::MEDIUM->wouldCapApply(1000000));
        $this->assertTrue(TobRate::HIGH->wouldCapApply(500000));
    }

    public function test_cap_would_apply_returns_false_when_not_exceeded(): void
    {
        $this->assertFalse(TobRate::LOW->wouldCapApply(10000));
        $this->assertFalse(TobRate::MEDIUM->wouldCapApply(10000));
        $this->assertFalse(TobRate::HIGH->wouldCapApply(10000));
    }

    /*
    |--------------------------------------------------------------------------
    | Display Methods
    |--------------------------------------------------------------------------
    */

    public function test_percentage_returns_correct_format(): void
    {
        $this->assertSame('0,12%', TobRate::LOW->percentage());
        $this->assertSame('0,35%', TobRate::MEDIUM->percentage());
        $this->assertSame('1,32%', TobRate::HIGH->percentage());
    }

    public function test_options_returns_all_rates(): void
    {
        $options = TobRate::options();

        $this->assertCount(3, $options);
        $this->assertSame('low', $options[0]['value']);
        $this->assertSame('medium', $options[1]['value']);
        $this->assertSame('high', $options[2]['value']);
    }

    /*
    |--------------------------------------------------------------------------
    | Real-World Scenarios
    |--------------------------------------------------------------------------
    */

    public function test_typical_etf_purchase_10000_eur(): void
    {
        // Typical IWDA purchase
        $tax = TobRate::LOW->calculateTax(10000);
        $this->assertSame(12.00, $tax);
    }

    public function test_typical_stock_purchase_5000_eur(): void
    {
        // Typical AAPL purchase
        $tax = TobRate::MEDIUM->calculateTax(5000);
        $this->assertSame(17.50, $tax);
    }

    public function test_large_etf_purchase_with_cap(): void
    {
        // Large investment exceeding cap
        $tax = TobRate::LOW->calculateTax(1500000);
        $this->assertSame(1300.00, $tax); // Capped at €1,300
    }
}
