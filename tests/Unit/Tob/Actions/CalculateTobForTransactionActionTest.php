<?php

declare(strict_types=1);

namespace Tests\Unit\Tob\Actions;

use App\Tob\Actions\CalculateTobForTransactionAction;
use App\Tob\Data\GenericTransaction;
use App\Tob\Enums\TobRate;
use App\Tob\Enums\TransactionType;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class CalculateTobForTransactionActionTest extends TestCase
{
    private CalculateTobForTransactionAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new CalculateTobForTransactionAction();
    }

    private function createTransaction(
        float $amount,
        TransactionType $type = TransactionType::BUY,
        string $ticker = 'TEST',
        ?Carbon $date = null
    ): GenericTransaction {
        return new GenericTransaction(
            transactionDate: $date ?? Carbon::now(),
            ticker: $ticker,
            type: $type,
            quantity: 1.0,
            totalAmountEur: $amount,
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Basic Calculations
    |--------------------------------------------------------------------------
    */

    public function test_calculates_low_rate_correctly(): void
    {
        $transaction = $this->createTransaction(10000);
        $result = $this->action->execute($transaction, TobRate::LOW);

        $this->assertSame(12.00, $result->calculatedTax);
        $this->assertSame(12.00, $result->appliedTax);
        $this->assertFalse($result->capApplied);
    }

    public function test_calculates_medium_rate_correctly(): void
    {
        $transaction = $this->createTransaction(10000);
        $result = $this->action->execute($transaction, TobRate::MEDIUM);

        $this->assertSame(35.00, $result->calculatedTax);
        $this->assertSame(35.00, $result->appliedTax);
        $this->assertFalse($result->capApplied);
    }

    public function test_calculates_high_rate_correctly(): void
    {
        $transaction = $this->createTransaction(10000);
        $result = $this->action->execute($transaction, TobRate::HIGH);

        $this->assertSame(132.00, $result->calculatedTax);
        $this->assertSame(132.00, $result->appliedTax);
        $this->assertFalse($result->capApplied);
    }

    /*
    |--------------------------------------------------------------------------
    | Cap Application
    |--------------------------------------------------------------------------
    */

    public function test_applies_low_rate_cap(): void
    {
        $transaction = $this->createTransaction(2000000);
        $result = $this->action->execute($transaction, TobRate::LOW);

        $this->assertSame(2400.00, $result->calculatedTax); // Raw calculation
        $this->assertSame(1300.00, $result->appliedTax);     // Capped
        $this->assertTrue($result->capApplied);
    }

    public function test_applies_medium_rate_cap(): void
    {
        $transaction = $this->createTransaction(1000000);
        $result = $this->action->execute($transaction, TobRate::MEDIUM);

        $this->assertSame(3500.00, $result->calculatedTax); // Raw calculation
        $this->assertSame(1600.00, $result->appliedTax);     // Capped
        $this->assertTrue($result->capApplied);
    }

    public function test_applies_high_rate_cap(): void
    {
        $transaction = $this->createTransaction(500000);
        $result = $this->action->execute($transaction, TobRate::HIGH);

        $this->assertSame(6600.00, $result->calculatedTax); // Raw calculation
        $this->assertSame(4000.00, $result->appliedTax);     // Capped
        $this->assertTrue($result->capApplied);
    }

    /*
    |--------------------------------------------------------------------------
    | Transaction Types
    |--------------------------------------------------------------------------
    */

    public function test_handles_buy_transaction(): void
    {
        $transaction = $this->createTransaction(5000, TransactionType::BUY);
        $result = $this->action->execute($transaction, TobRate::MEDIUM);

        $this->assertSame(17.50, $result->appliedTax);
    }

    public function test_handles_sell_transaction(): void
    {
        $transaction = $this->createTransaction(5000, TransactionType::SELL);
        $result = $this->action->execute($transaction, TobRate::MEDIUM);

        $this->assertSame(17.50, $result->appliedTax);
    }

    public function test_handles_negative_amount_as_absolute(): void
    {
        $transaction = $this->createTransaction(-5000, TransactionType::SELL);
        $result = $this->action->execute($transaction, TobRate::MEDIUM);

        $this->assertSame(17.50, $result->appliedTax);
    }

    /*
    |--------------------------------------------------------------------------
    | Result Object
    |--------------------------------------------------------------------------
    */

    public function test_result_contains_original_transaction(): void
    {
        $transaction = $this->createTransaction(10000, ticker: 'AAPL');
        $result = $this->action->execute($transaction, TobRate::MEDIUM);

        $this->assertSame($transaction, $result->transaction);
        $this->assertSame('AAPL', $result->transaction->ticker);
    }

    public function test_result_contains_rate_used(): void
    {
        $transaction = $this->createTransaction(10000);
        $result = $this->action->execute($transaction, TobRate::LOW);

        $this->assertSame(TobRate::LOW, $result->rate);
    }

    /*
    |--------------------------------------------------------------------------
    | Edge Cases
    |--------------------------------------------------------------------------
    */

    public function test_handles_zero_amount(): void
    {
        $transaction = $this->createTransaction(0);
        $result = $this->action->execute($transaction, TobRate::MEDIUM);

        $this->assertSame(0.00, $result->calculatedTax);
        $this->assertSame(0.00, $result->appliedTax);
        $this->assertFalse($result->capApplied);
    }

    public function test_handles_small_amount_with_rounding(): void
    {
        // €123.45 * 0.35% = €0.432075 -> rounds to €0.43
        $transaction = $this->createTransaction(123.45);
        $result = $this->action->execute($transaction, TobRate::MEDIUM);

        $this->assertSame(0.43, $result->appliedTax);
    }

    public function test_handles_amount_at_exact_cap_threshold(): void
    {
        // At exactly the cap threshold (€457,142.86), the raw tax equals the cap
        // Due to floating point math, this can result in capApplied being true or false
        // What's important is that the applied tax equals the cap
        $threshold = TobRate::MEDIUM->capThreshold();
        $transaction = $this->createTransaction($threshold);
        $result = $this->action->execute($transaction, TobRate::MEDIUM);

        // The applied tax should be at or near the cap
        $this->assertEqualsWithDelta(1600.00, $result->appliedTax, 0.01);
    }

    public function test_handles_amount_just_over_cap_threshold(): void
    {
        // Just over the cap threshold, the cap SHOULD be applied
        $threshold = TobRate::MEDIUM->capThreshold() + 1000;
        $transaction = $this->createTransaction($threshold);
        $result = $this->action->execute($transaction, TobRate::MEDIUM);

        $this->assertSame(1600.00, $result->appliedTax);
        $this->assertTrue($result->capApplied);
    }

    /*
    |--------------------------------------------------------------------------
    | Real-World Scenarios
    |--------------------------------------------------------------------------
    */

    public function test_typical_iwda_monthly_dca(): void
    {
        // Monthly DCA of €500 into IWDA
        $transaction = $this->createTransaction(500, ticker: 'IWDA');
        $result = $this->action->execute($transaction, TobRate::LOW);

        $this->assertSame(0.60, $result->appliedTax);
        $this->assertFalse($result->capApplied);
    }

    public function test_large_one_time_vwce_investment(): void
    {
        // Lump sum of €50,000 into VWCE
        $transaction = $this->createTransaction(50000, ticker: 'VWCE');
        $result = $this->action->execute($transaction, TobRate::LOW);

        $this->assertSame(60.00, $result->appliedTax);
        $this->assertFalse($result->capApplied);
    }

    public function test_apple_stock_purchase(): void
    {
        // €2,500 worth of AAPL shares
        $transaction = $this->createTransaction(2500, ticker: 'AAPL');
        $result = $this->action->execute($transaction, TobRate::MEDIUM);

        $this->assertSame(8.75, $result->appliedTax);
        $this->assertFalse($result->capApplied);
    }
}
