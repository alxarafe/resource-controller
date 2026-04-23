<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Tests\Unit\Null;

use Alxarafe\ResourceController\Contracts\TransactionContract;
use Alxarafe\ResourceController\Null\NullTransaction;
use PHPUnit\Framework\TestCase;

class NullTransactionTest extends TestCase
{
    public function testImplementsContract(): void
    {
        $tx = new NullTransaction();
        $this->assertInstanceOf(TransactionContract::class, $tx);
    }

    public function testBeginDoesNotThrow(): void
    {
        $tx = new NullTransaction();
        $tx->begin();
        $this->assertTrue(true); // No exception = pass
    }

    public function testCommitDoesNotThrow(): void
    {
        $tx = new NullTransaction();
        $tx->commit();
        $this->assertTrue(true);
    }

    public function testRollbackDoesNotThrow(): void
    {
        $tx = new NullTransaction();
        $tx->rollback();
        $this->assertTrue(true);
    }

    public function testWrapExecutesCallbackAndReturnsResult(): void
    {
        $tx = new NullTransaction();
        $result = $tx->wrap(fn() => 42);
        $this->assertSame(42, $result);
    }

    public function testWrapPropagatesException(): void
    {
        $tx = new NullTransaction();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('boom');

        $tx->wrap(function () {
            throw new \RuntimeException('boom');
        });
    }
}
