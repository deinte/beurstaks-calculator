<?php

declare(strict_types=1);

namespace Tests\Unit\Tob\Data;

use App\Tob\Data\GenericTransaction;
use App\Tob\Enums\TransactionType;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class GenericTransactionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Freeze time for consistent deadline tests
        Carbon::setTestNow(Carbon::parse('2025-06-15'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function createTransaction(
        Carbon $date,
        string $ticker = 'IWDA',
        TransactionType $type = TransactionType::BUY,
        float $amount = 10000.0
    ): GenericTransaction {
        return new GenericTransaction(
            transactionDate: $date,
            ticker: $ticker,
            type: $type,
            quantity: 10.0,
            totalAmountEur: $amount,
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Period Key Generation
    |--------------------------------------------------------------------------
    */

    public function test_january_and_february_share_period_key(): void
    {
        $january = $this->createTransaction(Carbon::parse('2025-01-15'));
        $february = $this->createTransaction(Carbon::parse('2025-02-20'));

        $this->assertSame('2025-01-02', $january->getPeriodKey());
        $this->assertSame('2025-01-02', $february->getPeriodKey());
    }

    public function test_march_has_own_period_key(): void
    {
        $march = $this->createTransaction(Carbon::parse('2025-03-10'));

        $this->assertSame('2025-03', $march->getPeriodKey());
    }

    public function test_december_has_own_period_key(): void
    {
        $december = $this->createTransaction(Carbon::parse('2025-12-25'));

        $this->assertSame('2025-12', $december->getPeriodKey());
    }

    public function test_period_keys_across_different_months(): void
    {
        $months = [
            ['date' => '2025-01-01', 'expected' => '2025-01-02'],
            ['date' => '2025-02-28', 'expected' => '2025-01-02'],
            ['date' => '2025-03-15', 'expected' => '2025-03'],
            ['date' => '2025-04-01', 'expected' => '2025-04'],
            ['date' => '2025-05-15', 'expected' => '2025-05'],
            ['date' => '2025-06-30', 'expected' => '2025-06'],
            ['date' => '2025-07-04', 'expected' => '2025-07'],
            ['date' => '2025-08-15', 'expected' => '2025-08'],
            ['date' => '2025-09-01', 'expected' => '2025-09'],
            ['date' => '2025-10-31', 'expected' => '2025-10'],
            ['date' => '2025-11-11', 'expected' => '2025-11'],
            ['date' => '2025-12-25', 'expected' => '2025-12'],
        ];

        foreach ($months as $month) {
            $transaction = $this->createTransaction(Carbon::parse($month['date']));
            $this->assertSame(
                $month['expected'],
                $transaction->getPeriodKey(),
                "Failed for date {$month['date']}"
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Period Labels
    |--------------------------------------------------------------------------
    */

    public function test_january_february_combined_label(): void
    {
        $january = $this->createTransaction(Carbon::parse('2025-01-15'));

        $this->assertSame('Januari - Februari 2025', $january->getPeriodLabel());
    }

    public function test_march_label(): void
    {
        $march = $this->createTransaction(Carbon::parse('2025-03-15'));

        $this->assertStringContainsString('2025', $march->getPeriodLabel());
    }

    /*
    |--------------------------------------------------------------------------
    | Deadline Calculations
    |--------------------------------------------------------------------------
    */

    public function test_january_deadline_is_end_of_march(): void
    {
        $transaction = $this->createTransaction(Carbon::parse('2025-01-15'));
        $deadline = $transaction->getDeadline();

        // Should be the last working day of March 2025
        // March 31, 2025 is a Monday, so deadline should be March 31
        $this->assertSame('2025-03-31', $deadline->format('Y-m-d'));
    }

    public function test_february_deadline_is_end_of_april(): void
    {
        $transaction = $this->createTransaction(Carbon::parse('2025-02-10'));
        $deadline = $transaction->getDeadline();

        // Should be the last working day of April 2025
        // April 30, 2025 is a Wednesday, so deadline should be April 30
        $this->assertSame('2025-04-30', $deadline->format('Y-m-d'));
    }

    public function test_march_deadline_is_end_of_may(): void
    {
        $transaction = $this->createTransaction(Carbon::parse('2025-03-15'));
        $deadline = $transaction->getDeadline();

        // Should be the last working day of May 2025
        // May 31, 2025 is a Saturday, so deadline should be May 30 (Friday)
        $this->assertSame('2025-05-30', $deadline->format('Y-m-d'));
    }

    public function test_deadline_moves_to_friday_when_end_of_month_is_weekend(): void
    {
        // November 2025 ends on a Sunday
        $transaction = $this->createTransaction(Carbon::parse('2025-09-15'));
        $deadline = $transaction->getDeadline();

        // November 30, 2025 is a Sunday, so deadline should be November 28 (Friday)
        $this->assertSame('2025-11-28', $deadline->format('Y-m-d'));
    }

    public function test_deadline_is_not_weekend(): void
    {
        // Test multiple months to ensure no deadline falls on a weekend
        for ($month = 1; $month <= 12; $month++) {
            $transaction = $this->createTransaction(Carbon::parse("2025-{$month}-15"));
            $deadline = $transaction->getDeadline();

            $this->assertFalse(
                $deadline->isWeekend(),
                "Deadline for month {$month} falls on a weekend: {$deadline->format('Y-m-d l')}"
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Overdue Status
    |--------------------------------------------------------------------------
    */

    public function test_transaction_is_overdue_when_deadline_passed(): void
    {
        // Current date is 2025-06-15, so January deadline (March 31) has passed
        $transaction = $this->createTransaction(Carbon::parse('2025-01-15'));

        $this->assertTrue($transaction->isOverdue());
    }

    public function test_transaction_is_not_overdue_when_deadline_not_passed(): void
    {
        // Current date is 2025-06-15, so June deadline (August 29) has not passed
        $transaction = $this->createTransaction(Carbon::parse('2025-06-10'));

        $this->assertFalse($transaction->isOverdue());
    }

    /*
    |--------------------------------------------------------------------------
    | Days Until Deadline
    |--------------------------------------------------------------------------
    */

    public function test_days_until_deadline_is_positive_when_not_overdue(): void
    {
        // June transaction, deadline is end of August
        $transaction = $this->createTransaction(Carbon::parse('2025-06-10'));
        $days = $transaction->daysUntilDeadline();

        $this->assertGreaterThan(0, $days);
    }

    public function test_days_until_deadline_is_negative_when_overdue(): void
    {
        // January transaction, deadline was March 31
        $transaction = $this->createTransaction(Carbon::parse('2025-01-15'));
        $days = $transaction->daysUntilDeadline();

        $this->assertLessThan(0, $days);
    }

    /*
    |--------------------------------------------------------------------------
    | Serialization
    |--------------------------------------------------------------------------
    */

    public function test_to_array_serializes_correctly(): void
    {
        $date = Carbon::parse('2025-03-15 10:30:00');
        $transaction = new GenericTransaction(
            transactionDate: $date,
            ticker: 'AAPL',
            type: TransactionType::BUY,
            quantity: 5.5,
            totalAmountEur: 1234.56,
        );

        $array = $transaction->toArray();

        $this->assertArrayHasKey('transactionDate', $array);
        $this->assertArrayHasKey('ticker', $array);
        $this->assertArrayHasKey('type', $array);
        $this->assertArrayHasKey('quantity', $array);
        $this->assertArrayHasKey('totalAmountEur', $array);

        $this->assertSame('AAPL', $array['ticker']);
        $this->assertSame('BUY', $array['type']);
        $this->assertSame(5.5, $array['quantity']);
        $this->assertSame(1234.56, $array['totalAmountEur']);
    }

    public function test_from_array_deserializes_correctly(): void
    {
        $data = [
            'transactionDate' => '2025-03-15T10:30:00+00:00',
            'ticker' => 'IWDA',
            'type' => 'SELL',
            'quantity' => 10.0,
            'totalAmountEur' => 5000.00,
        ];

        $transaction = GenericTransaction::fromArray($data);

        $this->assertSame('IWDA', $transaction->ticker);
        $this->assertSame(TransactionType::SELL, $transaction->type);
        $this->assertSame(10.0, $transaction->quantity);
        $this->assertSame(5000.00, $transaction->totalAmountEur);
        $this->assertSame('2025-03-15', $transaction->transactionDate->format('Y-m-d'));
    }

    public function test_roundtrip_serialization(): void
    {
        $original = new GenericTransaction(
            transactionDate: Carbon::parse('2025-05-20'),
            ticker: 'VWCE',
            type: TransactionType::BUY,
            quantity: 15.25,
            totalAmountEur: 2500.75,
        );

        $array = $original->toArray();
        $restored = GenericTransaction::fromArray($array);

        $this->assertSame($original->ticker, $restored->ticker);
        $this->assertSame($original->type, $restored->type);
        $this->assertSame($original->quantity, $restored->quantity);
        $this->assertSame($original->totalAmountEur, $restored->totalAmountEur);
        $this->assertSame(
            $original->transactionDate->format('Y-m-d'),
            $restored->transactionDate->format('Y-m-d')
        );
    }
}
